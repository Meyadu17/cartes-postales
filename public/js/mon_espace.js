$(document).ready(function() {

    console.log('📌 document ready exécuté');

    // =====================
    // LECTURE DES URLS
    // =====================
    const config = {
        // Listes (DataTables)
        urlRegions:         $('#app-config').data('url-regions'),
        urlDepartements:    $('#app-config').data('url-departements'),
        urlVilles:          $('#app-config').data('url-villes'),

        // Création
        urlRegionNew:       $('#app-config').data('url-region-new'),
        urlDepartementNew:  $('#app-config').data('url-departement-new'),
        urlVilleNew:        $('#app-config').data('url-ville-new'),

        // Édition
        urlRegionEdit:      $('#app-config').data('url-region-edit'),
        urlDepartementEdit: $('#app-config').data('url-departement-edit'),
        urlVilleEdit:       $('#app-config').data('url-ville-edit'),

        // Suppression
        urlRegionDelete:            $('#app-config').data('url-region-delete'),
        urlDepartementDelete:       $('#app-config').data('url-departement-delete'),
        urlVilleDelete:             $('#app-config').data('url-ville-delete'),

        // Dépendances
        urlRegionDependances:       $('#app-config').data('url-region-dependances'),
        urlDepartementDependances:  $('#app-config').data('url-departement-dependances'),
    };

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
        order: [[1, 'asc']],
        columns: [
            { data: 'code', width: '15%' },
            { data: 'nom', width: '70%' },
            {
                data: 'id', orderable: false, width: '15%', className: 'text-end',
                render: function(id, type, row) {
                    return `<button class="btn btn-sm btn-action-edit" data-id="${id}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-action-delete" data-id="${id}" data-nom="${row.nom}" data-type="region"><i class="bi bi-trash"></i></button>`;
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
        order: [[1, 'asc']],
        columns: [
            { data: 'code', width: '10%' },
            { data: 'nom', width: '40%' },
            { data: 'region', width: '35%' },
            {
                data: 'id', orderable: false, width: '15%', className: 'text-end',
                render: function(id, type, row) {
                    return `<button class="btn btn-sm btn-action-edit" data-id="${id}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-action-delete" data-id="${id}" data-nom="${row.nom}" data-type="departement"><i class="bi bi-trash"></i></button>`;
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
        order: [[0, 'asc']],
        columns: [
            { data: 'nom', width: '45%' },
            { data: 'departement', width: '40%' },
            {
                data: 'id', orderable: false, width: '15%', className: 'text-end',
                render: function(id, type, row) {
                    return `<button class="btn btn-sm btn-action-edit" data-id="${id}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-action-delete" data-id="${id}" data-nom="${row.nom}" data-type="ville"><i class="bi bi-trash"></i></button>`;
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
    // DÉTECTION DU BOUTON CLIQUÉ (CRÉATION)
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

    // =====================
    // ÉDITION - RÉGIONS
    // =====================
    $('#tableRegions').on('click', '.btn-action-edit', function() {
        const id = $(this).data('id');
        const url = config.urlRegionEdit.replace('__ID__', id);

        $.get(url, function(html) {
            const content = $(html).find('.modal-content');
            $('#modalEditerRegion .modal-content').html(content.html());
            $('#modalEditerRegion').data('edit-url', url); // ← stocker l'URL
            $('#modalEditerRegion').modal('show');
        });
    });

    $('#modalEditerRegion').on('submit', 'form', function(e) {
        e.preventDefault();
        const url = $('#modalEditerRegion').data('edit-url'); // ← récupérer l'URL

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                closeModal('#modalEditerRegion');
                showToast('Région modifiée !');
                tableRegions.ajax.reload();
            },
            error: function(xhr) {
                const content = $(xhr.responseText).find('.modal-content');
                $('#modalEditerRegion .modal-content').html(content.html());
            }
        });
    });



    // =====================
    // ÉDITION - DÉPARTEMENTS
    // =====================
    $('#tableDepartements').on('click', '.btn-action-edit', function() {
        const id = $(this).data('id');
        const url = config.urlDepartementEdit.replace('__ID__', id);

        $.get(url, function(html) {
            const content = $(html).find('.modal-content');
            $('#modalEditerDepartement .modal-content').html(content.html());
            $('#modalEditerDepartement').data('edit-url', url); // ← stocker l'URL
            $('#modalEditerDepartement').modal('show');
        });
    });

    $('#modalEditerDepartement').on('submit', 'form', function(e) {
        e.preventDefault();
        const url = $('#modalEditerDepartement').data('edit-url'); // ← récupérer l'URL

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                closeModal('#modalEditerDepartement');
                showToast('Département modifiée !');
                tableDepartements.ajax.reload();
            },
            error: function(xhr) {
                const content = $(xhr.responseText).find('.modal-content');
                $('#modalEditerDepartement .modal-content').html(content.html());
            }
        });
    });

    // =====================
    // ÉDITION - VILLES
    // =====================
    $('#tableVilles').on('click', '.btn-action-edit', function() {
        const id = $(this).data('id');
        const url = config.urlVilleEdit.replace('__ID__', id);

        $.get(url, function(html) {
            const content = $(html).find('.modal-content');
            $('#modalEditerVille .modal-content').html(content.html());
            $('#modalEditerVille').data('edit-url', url); // ← stocker l'URL
            $('#modalEditerVille').modal('show');
        });
    });

    $('#modalEditerVille').on('submit', 'form', function(e) {
        e.preventDefault();
        const url = $('#modalEditerVille').data('edit-url'); // ← récupérer l'URL

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                closeModal('#modalEditerVille');
                showToast('Ville modifiée !');
                tableVilles.ajax.reload();
            },
            error: function(xhr) {
                const content = $(xhr.responseText).find('.modal-content');
                $('#modalEditerVille .modal-content').html(content.html());
            }
        });
    });

    // =====================
    // SUPPRESSION
    // =====================
    let suppressionUrl = null;

    $(document).on('click', '.btn-action-delete', function() {
        const id   = $(this).data('id');
        const nom  = $(this).data('nom');
        const type = $(this).data('type');

        const modal       = new bootstrap.Modal('#modalConfirmSupression');
        const message     = document.getElementById('suppressionMessage');
        const dependances = document.getElementById('suppressionDependances');

        message.innerHTML = `Attention, vous vous apprêtez à supprimer <strong>${nom}</strong>. Cette action est irréversible. Êtes-vous sûr de ce choix ?`;
        dependances.innerHTML = '';

        if (type === 'region') {
            suppressionUrl = config.urlRegionDelete.replace('__ID__', id);

            fetch(config.urlRegionDependances.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    if (data.departements && data.departements.length > 0) {
                        let html = '<div class="alert alert-warning mt-2"><strong>Dépendances :</strong><ul class="mb-0 mt-1">';
                        data.departements.forEach(dept => {
                            html += `<li><strong>${dept.nom}</strong>`;
                            if (dept.villes.length > 0) {
                                html += '<ul>';
                                dept.villes.forEach(v => { html += `<li>${v}</li>`; });
                                html += '</ul>';
                            }
                            html += '</li>';
                        });
                        html += '</ul></div>';
                        dependances.innerHTML = html;
                    }
                });

        } else if (type === 'departement') {
            suppressionUrl = config.urlDepartementDelete.replace('__ID__', id);

            fetch(config.urlDepartementDependances.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    if (data.villes && data.villes.length > 0) {
                        let html = '<div class="alert alert-warning mt-2"><strong>Villes concernées :</strong><ul class="mb-0 mt-1">';
                        data.villes.forEach(v => { html += `<li>${v}</li>`; });
                        html += '</ul></div>';
                        dependances.innerHTML = html;
                    }
                });

        } else if (type === 'ville') {
            suppressionUrl = config.urlVilleDelete.replace('__ID__', id);
        }

        modal.show();
    });

    document.getElementById('btnConfirmerSuppression').addEventListener('click', function() {
        if (!suppressionUrl) return;

        fetch(suppressionUrl, { method: 'DELETE' })
            .then(r => r.json())
            .then(data => {
                closeModal('#modalConfirmSupression');
                showToast('Suppression effectuée !');
                tableRegions.ajax.reload();
                tableDepartements.ajax.reload();
                tableVilles.ajax.reload();
                suppressionUrl = null;
            })
            .catch(err => {
                console.error('Erreur suppression:', err);
                alert('Erreur lors de la suppression.');
            });
    });


});
