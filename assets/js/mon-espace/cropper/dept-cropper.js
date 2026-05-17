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

export function initDeptEditCropper() {
    return new CropperManager({
        aspectRatio: 1,
        imageElId:       'modalEditerDepartement_cropperImage',
        base64InputId:   'modalEditerDepartement_logoBase64',
        fileInputId:     'modalEditerDepartement_fileInput',
        previewSelector: '#modalEditerDepartement_cropperPreview',
        wrapperIds: [
            'modalEditerDepartement_cropperWrapper',
            'modalEditerDepartement_previewWrapper',
        ],
        outputWidth: 400,
        outputHeight: 400,
    });
}


