import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";

document.addEventListener("DOMContentLoaded", function () {
    const dropzoneElement = document.getElementById("product-dropzone");
    const form = document.getElementById("product-form");

    if (dropzoneElement && form.dataset.productId) {
        const productId = form.dataset.productId;

        new Dropzone(dropzoneElement, {
            // Konfiguracja Dropzone
            url: `/admin/product/${productId}/upload-image`,
            paramName: "image", // Nazwa parametru w żądaniu POST
            maxFilesize: 2, // Rozmiar pliku w MB
            acceptedFiles: "image/*", // Akceptowane typy plików
            addRemoveLinks: true, // Dodaj linki do usuwania plików
            dictDefaultMessage:
                "Przeciągnij pliki tutaj lub kliknij, aby je przesłać",
            success: function (file, response) {
                // Log the response from the server
                console.log("File upload successful:", response);
            },

            // Handle file upload errors
            error: function (file, message, xhr) {
                console.error("File upload error:", message);
            },
        });
    }
});
