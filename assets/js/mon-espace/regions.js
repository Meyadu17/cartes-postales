// =====================
// RÉGIONS
// =====================
export function initRegions(config) {
    return $('#tableRegions').DataTable({
        ajax: { url: config.urlRegions, dataSrc: '' },
        order: [[1, 'asc']],
        columns: [
            { data: 'code', width: '15%' },
            { data: 'nom', width: '30%' },
            { data: 'description', width: '45%' },
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
            const length  = wrapper.find('.dataTables_length').detach();
            const filter  = wrapper.find('.dataTables_filter').detach();
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);
            toolbar.append('<a href="#" class="btn btn-sm btn-vintage" data-bs-toggle="modal" data-bs-target="#modalNouvelleRegion"><i class="bi bi-plus-lg"></i> Nouvelle région</a>');
            wrapper.prepend(toolbar);
        }
    });
}
