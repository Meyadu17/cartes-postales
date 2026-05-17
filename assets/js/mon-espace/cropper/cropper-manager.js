// =====================
// CROPPER GÉNÉRIQUE (Réutilisable)
// =====================
export class CropperManager {
    constructor(options = {}) {
        this.cropper = null;
        this.options = {
            aspectRatio: options.aspectRatio ?? 1,
            viewMode: options.viewMode ?? 1,
            preview: options.previewSelector ?? null,
            outputWidth: options.outputWidth ?? 400,
            outputHeight: options.outputHeight ?? 400,
            quality: options.quality ?? 0.85,
        };
        this.imageEl = document.getElementById(options.imageElId);
        this.base64Input = document.getElementById(options.base64InputId);
        this.fileInput = document.getElementById(options.fileInputId);
        this.wrappers = options.wrapperIds?.map(id => document.getElementById(id)) ?? [];
    }

    init() {
        this.fileInput?.addEventListener('change', (e) => this._onFileChange(e));
    }

    _onFileChange(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (event) => {
            this.wrappers.forEach(el => el?.classList.remove('d-none'));

            if (this.cropper) this.cropper.destroy();

            this.imageEl.src = event.target.result;

            this.cropper = new Cropper(this.imageEl, {
                aspectRatio: this.options.aspectRatio,
                viewMode: this.options.viewMode,
                preview: this.options.preview,
                movable: true,
                zoomable: true,
                scalable: false,
                cropBoxResizable: true,
            });
        };
        reader.readAsDataURL(file);
    }

    // Appelé avant le submit
    getCroppedBase64() {
        console.log("this.cropper =", this.cropper);
        if (!this.cropper) return null;
        return this.cropper.getCroppedCanvas({
            width: this.options.outputWidth,
            height: this.options.outputHeight,
        }).toDataURL('image/jpeg', this.options.quality);
    }

    injectBase64() {
        const base64 = this.getCroppedBase64();
        console.log("Je suis dans injectBase64 de cropper-manager et base64 = ", base64)
        if (base64 && this.base64Input) {
            this.base64Input.value = base64;
        }
    }

    destroy() {
        this.cropper?.destroy();
        this.cropper = null;
    }
}
