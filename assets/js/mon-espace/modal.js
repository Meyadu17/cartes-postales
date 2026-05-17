// =====================
// fermer modale
// =====================
export function closeModal(modalId) {
    $(modalId).modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

// =====================
// soumettre formulaire (création)
// =====================
export function submitForm(formId, modalId, url, table, toastMessage, keepOpen, showToast) {
    $.ajax({
        url: url,
        method: 'POST',
        data: $(formId).serialize(),
        success: function() {
            showToast(toastMessage);
            $(formId)[0].reset();
            table.ajax.reload();
            if (!keepOpen) {
                closeModal(modalId);
            }
        },
        error: function(xhr) {
            console.error('❌ Erreur submitForm:', xhr.responseText);
            alert('Erreur lors de la soumission.');
        }
    });
}
