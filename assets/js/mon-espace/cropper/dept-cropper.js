// dept-cropper.js
import { CropperManager } from './cropper-manager.js';

export function initDeptCropper() {
    return new CropperManager({
        aspectRatio:     1,           // carré
        imageElId:       'cropper-image-dept',
        base64InputId:   'logoBase64-departement',
        fileInputId:     'crop-input-dept',
        previewSelector: '#cropper-preview-dept',
        wrapperIds: [
            'cropper-wrapper-dept', 
            'preview-wrapper-dept'
        ],
        outputWidth: 400,
        outputHeight: 400,
    });
}

export function initDeptEditCropper() {
    return new CropperManager({
        aspectRatio:     1,
        imageElId:       'cropper-image-dept-edit',
        base64InputId:   'logoBase64-departement-edit',
        fileInputId:     'crop-input-dept-edit',
        previewSelector: '#cropper-preview-dept-edit',
        wrapperIds: [
            'cropper-wrapper-dept-edit', 
            'preview-wrapper-dept-edit'
        ],
        outputWidth: 400,
        outputHeight: 400,
    });
}
