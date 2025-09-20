import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";

document.addEventListener("DOMContentLoaded", function () {
    const dropzoneElement = document.getElementById("product-dropzone");
    const form = document.getElementById("product-form");

    if (dropzoneElement && form.dataset.productId) {
        const productId = form.dataset.productId;

        const myDropzone = new Dropzone(dropzoneElement, {
            url: `/admin/product/${productId}/upload-image`,
            paramName: "image", //
            maxFilesize: 2, // File size in MB
            acceptedFiles: "image/*",
            addRemoveLinks: false,
            dictDefaultMessage:
                "Przeciągnij pliki tutaj lub kliknij, aby je przesłać",
            uploadprogress: function (file, progress) {
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
                                myDropzone.removeFile(file);
                            },
                            { once: true }
                        );
                    }, 500);
                }
            },
            success: function (file, response) {},

            error: function (file, message, xhr) {
                myDropzone.removeFile(file);
                modal.open({
                    title: "error",
                    content: message,
                    buttons: [],
                });
            },
        });
    }
});
