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
export function initEditModal(tableSelector, modalId, urlPattern, tableInstance, toastMessage, showToast, closeModal, extraFields = {} ) {
    $(document).on('click', `${tableSelector} .btn-action-edit`, function () {
    //$(document).on('click', '.btn-action-edit', function () {
        // Vérifier que le bouton appartient bien à ce tableau
        if (!$(this).closest('table').is(tableSelector)) return;

        console.log("toto");
        const id  = $(this).data('id');
        const url = urlPattern.replace('__ID__', id);

        $.get(url, function (data) {
            const form = $(`${modalId} form`);

            // Vider les erreurs précédentes
            form.find('.alert-danger').remove();
            form.find('.is-invalid').removeClass('is-invalid');

            // Pré-remplir les champs simples (nom, code, description...)
            Object.keys(data).forEach(function (key) {
                const field = form.find(`[name$="[${key}]"], [name="${key}"]`);
                if (field.length) field.val(data[key]);
            });

            // Pré-remplir les selects d'entités (region, departement...)
            Object.keys(extraFields).forEach(function (dataKey) {
                const fieldName = extraFields[dataKey];
                form.find(`[name$="[${fieldName}]"]`).val(data[dataKey]);
            });

            $(modalId).data('edit-url', url);
            $(modalId).modal('show');
        }).fail(function(xhr) {
            console.error('Erreur $.get:', xhr.status, xhr.responseText);
        });
    });

    $(modalId).on('submit', 'form', function (e) {
        e.preventDefault();
        const url = $(modalId).data('edit-url');

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                closeModal(modalId);
                showToast(toastMessage);
                tableInstance.ajax.reload();
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    $(`${modalId} .alert-danger`).remove();
                    $(`${modalId} .is-invalid`).removeClass('is-invalid');

                    response.errors.forEach(function (error) {
                        $(`${modalId} form`).prepend(
                            `<div class="alert alert-danger">${error}</div>`
                        );
                    });
                }
            }
        });
    });
}
