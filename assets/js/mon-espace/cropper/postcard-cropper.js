// postcard-cropper.js
import { CropperManager } from './cropper-manager.js';

// Ratios et tailles de sortie selon orientation
const RATIOS = {
    paysage:  { ratio: 3 / 2, width: 900, height: 600 },
    portrait: { ratio: 2 / 3, width: 600, height: 900 },
};

/**
 * Initialise un cropper pour carte postale (création OU édition).
 * @param {object} ids - identifiants DOM
 * @param {string} ids.imageElId
 * @param {string} ids.base64InputId
 * @param {string} ids.fileInputId
 * @param {string} ids.previewSelector
 * @param {string[]} ids.wrapperIds
 * @param {string} ids.orientationSelectId  - id du <select> orientation
 * @param {string} [defaultOrientation='paysage']
 */
function buildPostcardCropper(ids, defaultOrientation = 'paysage') {
    const orientationSelect = document.getElementById(ids.orientationSelectId);

    // Lit la valeur actuelle du select (utile en édition), sinon fallback
    const startOrientation = orientationSelect?.value || defaultOrientation;
    const initial = RATIOS[startOrientation] ?? RATIOS.paysage;

    const manager = new CropperManager({
        aspectRatio:     initial.ratio,
        outputWidth:     initial.width,
        outputHeight:    initial.height,
        imageElId:       ids.imageElId,
        base64InputId:   ids.base64InputId,
        fileInputId:     ids.fileInputId,
        previewSelector: ids.previewSelector,
        wrapperIds:      ids.wrapperIds,
    });

    // Écoute les changements d'orientation
    if (orientationSelect) {
        orientationSelect.addEventListener('change', (e) => {
            const conf = RATIOS[e.target.value] ?? RATIOS.paysage;
            manager.setAspectRatio(conf.ratio, conf.width, conf.height);
        });
    }

    return manager;
}

// ====================
// CRÉATION
// ====================
export function initPostcardCropper() {
    return buildPostcardCropper({
        imageElId:           'cropper-image-postcard',
        base64InputId:       'logoBase64-postcard',
        fileInputId:         'crop-input-postcard',
        previewSelector:     '#cropper-preview-postcard',
        wrapperIds: [
            'cropper-wrapper-postcard',
            'preview-wrapper-postcard'
        ],
        orientationSelectId: 'postcard_orientation',
    });
}

// ====================
// ÉDITION
// ====================
export function initPostcardEditCropper() {
    return buildPostcardCropper({
        imageElId:           'cropper-image-postcard-edit',
        base64InputId:       'logoBase64-postcard-edit',
        fileInputId:         'crop-input-postcard-edit',
        previewSelector:     '#cropper-preview-postcard-edit',
        wrapperIds: [
            'cropper-wrapper-postcard-edit',
            'preview-wrapper-postcard-edit'
        ],
        orientationSelectId: 'postcard_orientation-edit',
    });
}
