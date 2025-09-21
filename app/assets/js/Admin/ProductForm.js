import DropzoneHandler from "../DropzoneHandler";

document.addEventListener("DOMContentLoaded", function () {
    const dropzoneElement = document.getElementById("product-dropzone");
    const form = document.getElementById("product-form");

    const dropzoneHandler = new DropzoneHandler(
        dropzoneElement,
        form,
        `/admin/product/${form.dataset.productId}/upload-image`,
        modal,
        {
            paramName: "image", //
            acceptedFiles: "image/*",
        }
    );
});
