import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";
import Modal from "./Modal";

class DropzoneHandler {
    constructor(dropzoneElement, formElement, uploadUrl, modal, config = {}) {
        this.dropzoneElement = dropzoneElement;
        this.form = formElement;
        this.uploadUrl = uploadUrl;
        this.modal = modal;
        this.config = config;

        if (!(this.dropzoneElement instanceof Element)) {
            throw new Error(`Dropzone element must be a valid DOM element.`);
        }

        if (!(this.form instanceof Element)) {
            throw new Error(`Form element must be a valid DOM element.`);
        }

        if (!this.uploadUrl) {
            throw new Error(`Upload URL is required.`);
        }

        if (!this.modal || !(this.modal instanceof Modal)) {
            throw new Error(`Modal instance is required.`);
        }

        if (
            (this.config !== null && typeof this.config !== "object") ||
            Array.isArray(this.config)
        ) {
            throw new Error(`Config must be an object.`);
        }

        this.initialize();
    }

    initialize() {
        const defaultOptions = {
            url: this.uploadUrl,
            paramName: "file",
            maxFilesize: 2,
            addRemoveLinks: false,
            dictDefaultMessage: "Drag your files here or click to upload them",

            uploadprogress: this.handleUploadProgress.bind(this),
            error: this.handleError.bind(this),
        };

        const options = { ...defaultOptions, ...this.config };
        this.dropzoneInstance = new Dropzone(this.dropzoneElement, options);
    }

    handleUploadProgress(file, progress) {
        const preview = file.previewElement;
        const progressBar = preview.querySelector(".dz-upload");

        if (progressBar) {
            progressBar.style.width = progress + "%";
        }

        if (progress >= 100 && !preview.dataset.fading) {
            preview.dataset.fading = "true";
            setTimeout(() => {
                preview.style.transition = "opacity 0.5s ease";
                preview.style.opacity = "0";

                preview.addEventListener(
                    "transitionend",
                    () => {
                        this.dropzoneInstance.removeFile(file);
                    },
                    { once: true }
                );
            }, 500);
        }
    }

    handleError(file, message, xhr) {
        this.dropzoneInstance.removeFile(file);
        this.modal.open({
            title: "error",
            content: message,
            buttons: [],
        });
    }
}

export default DropzoneHandler;
