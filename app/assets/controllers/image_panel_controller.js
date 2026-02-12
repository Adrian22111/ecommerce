import { Controller } from "@hotwired/stimulus";
import axios from "axios";
import ModalFactory from "../js/classes/ModalFactory";

/**
 * Image panel controller
 *
 *
 */
export default class extends Controller {
    static targets = ["imageWrapper"];
    static values = {
        fetchUrl: String, // URL to fetch images from
        csrfToken: String, // CSRF token for secure requests
        productId: String, // images are rendered for this product
        deleteUrl: String //
    };

    initialize() {
        void this.fetchImages();
        this.setDefaultStyles();
        document.addEventListener('image:uploaded', this.handleImageUploaded.bind(this));
    }

    setDefaultStyles() {
        this.imageWrapperTarget.classList.add(
            "grid",
            "grid-cols-2",
            "gap-4",
            "hover:cursor-pointer"
        );
    }

    async fetchImages() {
        if (!this.hasFetchUrlValue) {
            ModalFactory.create('unexpectedError').open();
            return;
        }
        try{
            const response = await axios.get(this.fetchUrlValue);
            this.renderImages(response.data);
        } catch (error) {
            ModalFactory.create('unexpectedError').open();
        }
    }

    /**
     *
     * @param images - array with image data
     * [
     *      { id: 95, name: "68d10df8ea256.png", src: "/uploads/images/product/blad-68d10df8ea256.png" },
     *      {…},
     *      {…}
     * ]
     * @param parentId - id of related entity, mostly for url genering purposes
     */
    renderImages(images) {
        this.imageWrapperTarget.innerHTML = "";
        images.forEach((image) => {
            let imageItem = this.generateImageItem(image);
            this.imageWrapperTarget.appendChild(imageItem);
        });
    }

    generateImageItem(image) {
        const imageItem = document.createElement("div");
        imageItem.classList.add("relative", "group/imyz", "image_"+image.id);

        const img = document.createElement("img");
        img.src = image.src;
        img.alt = image.name;

        const button = document.createElement("button");
        button.innerHTML = "&times;";
        button.classList.add(
            "absolute",
            "top-1",
            "right-1",
            "text-red-500",
            "p-1",
            "flex",
            "items-center",
            "justify-center",
            "w-6",
            "h-6",
            "opacity-0",
            "group-hover/imyz:opacity-50",
            "group-hover/imyz:bg-gray-800",
            "hover:cursor-pointer",
            "hover:bg-gray-700",
            "hover:opacity-100"
        );

        button.setAttribute("data-action", "click->image-panel#deleteImage");
        button.setAttribute("data-image-id", image.id);

        imageItem.appendChild(img);
        imageItem.appendChild(button);

        return imageItem;
    }

    async deleteImage(event) {
        event.preventDefault();

        const clickedButton = event.currentTarget;
        const imageId = clickedButton.getAttribute("data-image-id");

        if (!imageId) {
            ModalFactory.create('unexpectedError').open();
            return;
        }

        try {
            //Delete in database
            const deleteUrl = this.deleteUrlValue.replace(":IMAGE_ID", imageId);
            const response = await axios.delete(deleteUrl);

            //Delete in Html
            const imageItem = clickedButton.closest(`.image_${imageId}`);
            if (imageItem) {
                imageItem.remove();
            }
        } catch (error) {
            ModalFactory.create('unexpectedError').open();
        }
    }

    handleImageUploaded(event)
    {
        const image = {
            'id': event.detail.databaseId,
            'src': event.detail.uploadDirectory + '/' + event.detail.fileName,
            'name': event.detail.filename,
        };
        let imageItem = this.generateImageItem(image);
        this.imageWrapperTarget.appendChild(imageItem);
    }
}
