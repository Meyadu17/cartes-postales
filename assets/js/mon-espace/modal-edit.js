// =====================
// initialisation formulaire
// =====================
export function initEditModal(config) {
    const { tableSelector, modalId, urlPattern, tableInstance, toastMessage, showToast, closeModal, extraFields = {}, cropper = null, logoUrlKey = null } = config;

    $(document).on('click', `${tableSelector} .btn-action-edit`, function () {
        if (!$(this).closest('table').is(tableSelector)) return;

        const id  = $(this).data('id');
        const url = urlPattern.replace('__ID__', id);
        const $modal = $(modalId);
        const $form  = $modal.find('form');

        $.get(url)
            .done(function (data) {
                // Réinitialiser erreurs
                $form.find('.alert-danger').remove();
                $form.find('.is-invalid').removeClass('is-invalid');

                // Pré-remplir champs simples
                Object.keys(data).forEach(function (key) {
                    $form.find(`[name$="[${key}]"], [name="${key}"]`).val(data[key]);
                });

                // Pré-remplir selects d'entités (ex: regionId → [name$="[region]"])
                Object.keys(extraFields).forEach(function (dataKey) {
                    const fieldName = extraFields[dataKey];
                    $form.find(`[name$="[${fieldName}]"]`).val(data[dataKey]);
                });

                // Affichage du logo existant
                if (logoUrlKey && data[logoUrlKey]) {
                    const $img = $modal.find(`[id$="_current_logo"]`);
                    $img.attr('src', data[logoUrlKey]).removeClass('d-none');
                }

                $modal.data('edit-url', url);
                const modal = new bootstrap.Modal(document.querySelector(modalId));
                modal.show();
            })
            .fail(function (xhr) {
                console.error('Erreur chargement édition:', xhr.status, xhr.responseText);
            });
    });

    $(modalId).on('submit', 'form', function (e) {
        e.preventDefault();
        const $form = $(this);
        const url   = $(modalId).data('edit-url');

         // 🖼️ Injection base64 si cropper présent
        if (cropper) cropper.injectBase64();

        $.ajax({
            url,
            method: 'POST',
            data: $form.serialize(),
            success: function () {
                closeModal(modalId);
                showToast(toastMessage);
                tableInstance.ajax.reload();
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                $form.find('.alert-danger').remove();
                $form.find('.is-invalid').removeClass('is-invalid');

                if (response?.errors) {
                    response.errors.forEach(function (error) {
                        $form.prepend(`<div class="alert alert-danger">${error}</div>`);
                    });
                }
            }
        });
    });
}
