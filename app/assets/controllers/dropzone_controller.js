import { Controller } from "@hotwired/stimulus";
import Modal from "../js/Modal";
import Dropzone from "dropzone";

/**
 * Tab controller
 *
 *
 */
export default class extends Controller {
    /**
     *
     */
    static targets = ["dropzoneArea"];
    static values = {
        uploadUrl: String,
        config: String // JSON parsed to string
    };

    initialize() {
        this.dropzoneArea = this.dropzoneAreaTarget;
        this.uploadUrl = this.uploadUrlValue;

        if (typeof this.configValue !== "string" || this.configValue.trim() === "") {
            throw new Error(`Config is required.`);
        } else {
            this.config = JSON.parse(this.configValue);
        }

        if (!(this.dropzoneArea instanceof Element)) {
            throw new Error(`Dropzone element must be a valid DOM element.`);
        }

        if (!this.uploadUrl) {
            throw new Error(`Upload URL is required.`);
        }

        if (
            (typeof this.config !== "object") ||
            Array.isArray(this.config)
        ) {
            throw new Error(`Couldn't parse config.`);
        }

        this.initDropzone();
    }


    initDropzone() {
        const defaultOptions = {
            url: this.uploadUrl,
            paramName: "file",
            maxFilesize: 2,
            addRemoveLinks: false,
            dictDefaultMessage: "Drag your files here or click to upload them",

            uploadprogress: this.handleUploadProgress.bind(this),
            error: this.handleError.bind(this),
        };

        const options = {...defaultOptions, ...this.config};
        this.dropzoneInstance = new Dropzone(this.dropzoneArea, options);
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
        console.log(message);
        //commented for the modal rewriting into stimulus
        // this.modal.open({
        //     title: "error",
        //     content: message,
        //     buttons: [],
        // });
    }
}


