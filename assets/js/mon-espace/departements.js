// departements.js
// =====================
// DÉPARTEMENTS
// =====================
export function initDepartements(config) {

    return $('#tableDepartements').DataTable({
        // URL de récupération des données
        ajax: {
            url: config.urlDepartements,
            dataSrc: '',
            // Log avant envoi de la requête
            beforeSend: function() {
                console.log('🔄 Chargement des départements depuis :', config.urlDepartements);
            }
        },

        // Tri par défaut sur la colonne "nom" (index 1)
        order: [[1, 'asc']],

        // Définition des colonnes
        columns: [
            // Code du département
            { data: 'code', width: '8%' },

            // Nom du département
            { data: 'nom', width: '32%' },

            // Région associée
            { data: 'region', width: '30%' },

            // Logo du département
            {data: 'logoNom',
                orderable: false,
                width: '10%',
                className: 'text-center',
                render: function(logoNom, type, row) {
                    if (logoNom) {
                        return `<img src="/uploads/departements/${logoNom}"
                                    alt="logo ${row.nom}"
                                    style="width: 32px; height: 32px; object-fit: contain;">`;
                    }
                    return '<span class="text-muted">—</span>';
                }
            },

            // Colonne actions (édition / suppression)
            {
                data: 'id',
                orderable: false,
                width: '15%',
                className: 'text-end',
                render: function(id, type, row) {
                    return `
                        <button class="btn btn-sm btn-action-edit"
                                data-id="${id}"
                                title="Modifier le département ${row.nom}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-action-delete"
                                data-id="${id}"
                                data-nom="${row.nom}"
                                data-type="departement"
                                title="Supprimer le département ${row.nom}">
                            <i class="bi bi-trash"></i>
                        </button>`;
                }
            }
        ],

        // Traduction française
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },

        // Après initialisation complète du tableau
        initComplete: function() {
            const wrapper = $('#tableDepartements_wrapper');

            // Déplacer les contrôles natifs (longueur + filtre) dans une barre personnalisée
            const length = wrapper.find('.dataTables_length').detach();
            const filter = wrapper.find('.dataTables_filter').detach();

            // Créer la barre d'outils personnalisée
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);

            // Ajouter le bouton "Nouveau département"
            toolbar.append(`
                <a href="#"
                   class="btn btn-sm btn-vintage"
                   data-bs-toggle="modal"
                   data-bs-target="#modalNouveauDepartement">
                    <i class="bi bi-plus-lg"></i> Nouveau département
                </a>
            `);

            // Insérer la barre en haut du wrapper
            wrapper.prepend(toolbar);
        }
    });
}
