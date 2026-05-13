// =====================
// fermer modale
// =====================
export function closeModal(modalId) {
    $(modalId).modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

// =====================
// soumettre formulaire
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

// =====================
// initialisation formulaire
// =====================
export function initEditModal(tableSelector, modalId, urlPattern, tableInstance, toastMessage, showToast, closeModal) {
    $(tableSelector).on('click', '.btn-action-edit', function() {
        const id  = $(this).data('id');
        const url = urlPattern.replace('__ID__', id);

        $.get(url, function(html) {
            const content = $(html).find('.modal-content');
            $(`${modalId} .modal-content`).html(content.html());
            $(modalId).data('edit-url', url);
            $(modalId).modal('show');
        });
    });

    $(modalId).on('submit', 'form', function(e) {
        e.preventDefault();
        const url = $(modalId).data('edit-url');

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function() {
                closeModal(modalId);
                showToast(toastMessage);
                tableInstance.ajax.reload();
            },
            error: function(xhr) {
                const content = $(xhr.responseText).find('.modal-content');
                $(`${modalId} .modal-content`).html(content.html());
            }
        });
    });
}
