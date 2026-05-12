$(document).ready(function() {

    console.log('📌 document ready exécuté');

    // =====================
    // LECTURE DES URLS
    // =====================
    const config = document.getElementById('app-config').dataset;

    // =====================
    // TOAST
    // =====================
    function showToast(message) {
        if ($('#liveToast').length === 0) {
            $('body').append(`
                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body" id="toastMessage"></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
            `);
        }
        $('#toastMessage').text(message);
        const toast = new bootstrap.Toast($('#liveToast')[0], { delay: 2000 });
        toast.show();
    }

    // =====================
    // HELPER : fermer modale
    // =====================
    function closeModal(modalId) {
        $(modalId).modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }

    // =====================
    // HELPER : soumettre formulaire
    // =====================
    function submitForm(formId, modalId, url, table, toastMessage, keepOpen) {
        console.log('📤 submitForm appelé');
        console.log('keepOpen:', keepOpen);

        $.ajax({
            url: url,
            method: 'POST',
            data: $(formId).serialize(),
            success: function(response) {
                console.log('✅ succès, keepOpen:', keepOpen);
                showToast(toastMessage);
                $(formId)[0].reset();
                table.ajax.reload();
                if (!keepOpen) {
                    console.log('🔒 fermeture modale');
                    closeModal(modalId);
                } else {
                    console.log('🔓 modale reste ouverte');
                }
            },
            error: function(xhr) {
                console.log('❌ erreur:', xhr.responseText);
                alert('Erreur lors de la création.');
            }
        });
    }

    // =====================
    // RÉGIONS
    // =====================
    const tableRegions = $('#tableRegions').DataTable({
        ajax: { url: config.urlRegions, dataSrc: '' },
        columns: [
            { data: 'code', width: '15%' },
            { data: 'nom', width: '70%' },
            {
                data: 'id', orderable: false, width: '15%', className: 'text-end',
                render: function(id) {
                    return `<button class="btn btn-sm btn-action-edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-action-delete"><i class="bi bi-trash"></i></button>`;
                }
            }
        ],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
        initComplete: function() {
            const wrapper = $('#tableRegions_wrapper');
            const length = wrapper.find('.dataTables_length').detach();
            const filter = wrapper.find('.dataTables_filter').detach();
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);
            toolbar.append('<a href="#" class="btn btn-sm btn-vintage" data-bs-toggle="modal" data-bs-target="#modalNouvelleRegion"><i class="bi bi-plus-lg"></i> Nouvelle région</a>');
            wrapper.prepend(toolbar);
        }
    });

    // =====================
    // DÉPARTEMENTS
    // =====================
    const tableDepartements = $('#tableDepartements').DataTable({
        ajax: { url: config.urlDepartements, dataSrc: '' },
        columns: [
            { data: 'code', width: '10%' },
            { data: 'nom', width: '40%' },
            { data: 'region', width: '35%' },
            {
                data: 'id', orderable: false, width: '15%', className: 'text-end',
                render: function(id) {
                    return `<button class="btn btn-sm btn-action-edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-action-delete"><i class="bi bi-trash"></i></button>`;
                }
            }
        ],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
        initComplete: function() {
            const wrapper = $('#tableDepartements_wrapper');
            const length = wrapper.find('.dataTables_length').detach();
            const filter = wrapper.find('.dataTables_filter').detach();
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);
            toolbar.append('<a href="#" class="btn btn-sm btn-vintage" data-bs-toggle="modal" data-bs-target="#modalNouveauDepartement"><i class="bi bi-plus-lg"></i> Nouveau département</a>');
            wrapper.prepend(toolbar);
        }
    });

    // =====================
    // VILLES
    // =====================
    const tableVilles = $('#tableVilles').DataTable({
        ajax: { url: config.urlVilles, dataSrc: '' },
        columns: [
            { data: 'nom', width: '45%' },
            { data: 'departement', width: '40%' },
            {
                data: 'id', orderable: false, width: '15%', className: 'text-end',
                render: function(id) {
                    return `<button class="btn btn-sm btn-action-edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-action-delete"><i class="bi bi-trash"></i></button>`;
                }
            }
        ],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
        initComplete: function() {
            const wrapper = $('#tableVilles_wrapper');
            const length = wrapper.find('.dataTables_length').detach();
            const filter = wrapper.find('.dataTables_filter').detach();
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);
            toolbar.append('<a href="#" class="btn btn-sm btn-vintage" data-bs-toggle="modal" data-bs-target="#modalNouvelleVille"><i class="bi bi-plus-lg"></i> Nouvelle ville</a>');
            wrapper.prepend(toolbar);
        }
    });

    // =====================
    // DÉTECTION DU BOUTON CLIQUÉ
    // =====================
    $(document).on('click', '[type="submit"][name="action"]', function(e) {
        e.preventDefault();
        const action = $(this).val();
        const form = $(this).closest('form');
        const keepOpen = action === 'save_and_new';

        console.log('🖱️ bouton cliqué, valeur:', action, '| keepOpen:', keepOpen);
        console.log('📋 form id:', form.attr('id'));

        if (form.attr('id') === 'modalNouvelleRegion_form') {
            submitForm('#modalNouvelleRegion_form', '#modalNouvelleRegion', config.urlRegionNew, tableRegions, 'Région créée !', keepOpen);
        } else if (form.attr('id') === 'modalNouveauDepartement_form') {
            submitForm('#modalNouveauDepartement_form', '#modalNouveauDepartement', config.urlDepartementNew, tableDepartements, 'Département créé !', keepOpen);
        } else if (form.attr('id') === 'modalNouvelleVille_form') {
            submitForm('#modalNouvelleVille_form', '#modalNouvelleVille', config.urlVilleNew, tableVilles, 'Ville créée !', keepOpen);
        }
    });

});
