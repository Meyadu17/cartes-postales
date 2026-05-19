// postcards.js
// =====================
// CARTES POSTALES
// =====================
export function initPostcards(config) {
    return $('#tablePostcards').DataTable({
        // Pas de données pour l'instant
        ajax: {
            url: config.urlPostcards,
            dataSrc: ''
        },

        // Tri par défaut sur la colonne "titre" (index 0)
        order: [[0, 'asc']],

        // Définition des colonnes
        columns: [
            { data: 'titre',       width: '20%' },
            { data: 'annee',       width: '10%' },
            { data: 'orientation', width: '10%' },
            { data: 'region',      width: '15%' },
            { data: 'departement', width: '15%' },
            { data: 'ville',       width: '15%' },

            // Colonne actions (désactivées pour l'instant)
            {
                data: null,
                orderable: false,
                width: '15%',
                className: 'text-end',
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-action-edit"
                                disabled
                                title="Modifier (bientôt disponible)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-action-delete"
                                disabled
                                title="Supprimer (bientôt disponible)">
                            <i class="bi bi-trash"></i>
                        </button>`;
                }
            }
        ],

        // Traduction française
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json',
            emptyTable: 'Aucune carte postale pour le moment',
        },

        // Après initialisation complète du tableau
        initComplete: function() {
            const wrapper = $('#tablePostcards_wrapper');

            // Déplacer les contrôles natifs dans une barre personnalisée
            const length = wrapper.find('.dataTables_length').detach();
            const filter = wrapper.find('.dataTables_filter').detach();

            // Créer la barre d'outils personnalisée
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);

            // Bouton "Nouvelle carte postale" (désactivé pour l'instant)
            toolbar.append(`
                <button class="btn btn-sm btn-vintage" id="btn-new-postcard">
                    <i class="bi bi-plus-lg"></i> Nouvelle carte postale
                </button>
            `);

            // Insérer la barre en haut du wrapper
            wrapper.prepend(toolbar);

            // Redirection vers la page de création
            console.log('initComplete postcards exécuté');
            console.log('urlPostcardNew =', config.urlPostcardNew);

            $('#btn-new-postcard').on('click', function() {
                console.log('Clic sur Nouvelle carte postale');
                window.location.href = config.urlPostcardNew;
            });
        }
    });
}
