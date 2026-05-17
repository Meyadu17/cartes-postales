import { CropperManager } from './cropper-manager.js';

export function initDeptCropper() {
    return new CropperManager({
        aspectRatio: 1,           // carré
        imageElId: 'cropper-image',
        base64InputId: 'departement_logoBase64',
        fileInputId: 'dept-logo-input',
        previewSelector: '#cropper-preview',
        wrapperIds: ['cropper-wrapper', 'preview-wrapper'],
        outputWidth: 400,
        outputHeight: 400,
    });
}
