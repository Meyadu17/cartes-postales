// postcard-cropper.js
import { CropperManager } from './cropper-manager.js';

export class PostcardCropperManager extends CropperManager {

    constructor(options = {}) {
        super(options);
        this.portraitBtn  = document.getElementById(options.portraitBtnId);
        this.landscapeBtn = document.getElementById(options.landscapeBtnId);
        this.orientationInput = document.getElementById(options.orientationInputId);
    }

    init() {
        super.init(); // gère le fileInput change

        this.portraitBtn?.addEventListener('click', () => {
            this._setOrientation('portrait', 2/3, 400, 600);
        });

        this.landscapeBtn?.addEventListener('click', () => {
            this._setOrientation('landscape', 3/2, 600, 400);
        });
    }

    _setOrientation(value, ratio, w, h) {
        this.setAspectRatio(ratio, w, h);
        this.orientationInput.value = value;

        // Feedback visuel sur les boutons
        this.portraitBtn?.classList.toggle('active', value === 'portrait');
        this.landscapeBtn?.classList.toggle('active', value === 'landscape');
    }

    _onFileChange(e) {
        super._onFileChange(e);
        // Afficher les boutons d'orientation après chargement de l'image
        document.getElementById('orientation-buttons')?.classList.remove('d-none');
    }

}
