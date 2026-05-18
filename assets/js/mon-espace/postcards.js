// postcards.js
// =====================
// CARTES POSTALES
// =====================
export function initPostcards(config) {

    return $('#tablePostcards').DataTable({
        // URL de récupération des données
        ajax: {
            url: config.urlPostcards,
            dataSrc: '',
            beforeSend: function() {
                console.log('🔄 Chargement des cartes postales depuis :', config.urlPostcards);
            }
        },

        // Tri par défaut sur la colonne "titre" (index 0)
        order: [[0, 'asc']],

        // Définition des colonnes
        columns: [
            // Titre
            { data: 'titre', width: '15%' },

            // Description (tronquée si trop longue)
            {
                data: 'description',
                width: '25%',
                render: function(description) {
                    if (!description) {
                        return '<span class="text-muted">—</span>';
                    }
                    const max = 80;
                    return description.length > max
                        ? `<span title="${description.replace(/"/g, '&quot;')}">${description.substring(0, max)}…</span>`
                        : description;
                }
            },

            // Année
            {
                data: 'annee',
                width: '7%',
                className: 'text-center',
                render: function(annee) {
                    return annee ? annee : '<span class="text-muted">—</span>';
                }
            },

            // Orientation (paysage / portrait)
            {
                data: 'orientation',
                width: '8%',
                className: 'text-center',
                render: function(orientation) {
                    if (!orientation) {
                        return '<span class="text-muted">—</span>';
                    }
                    const icon = orientation === 'paysage'
                        ? '<i class="bi bi-image" title="Paysage"></i>'
                        : '<i class="bi bi-phone" title="Portrait"></i>';
                    return `${icon} ${orientation}`;
                }
            },

            // Ville
            {
                data: 'ville',
                width: '12%',
                render: function(ville) {
                    return ville ? ville : '<span class="text-muted">—</span>';
                }
            },

            // Département
            { data: 'departement', width: '12%' },

            // Région
            { data: 'region', width: '12%' },

            // Colonne actions (édition / suppression)
            {
                data: 'id',
                orderable: false,
                width: '9%',
                className: 'text-end',
                render: function(id, type, row) {
                    return `
                        <button class="btn btn-sm btn-action-edit"
                                data-id="${id}"
                                title="Modifier la carte postale ${row.titre}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-action-delete"
                                data-id="${id}"
                                data-nom="${row.titre}"
                                data-type="postcard"
                                title="Supprimer la carte postale ${row.titre}">
                            <i class="bi bi-trash"></i>
                        </button>`;
                }
            }
        ],

        // Traduction française
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },

        // Après initialisation complète du tableau
        initComplete: function() {
            const wrapper = $('#tablePostcards_wrapper');

            // Déplacer les contrôles natifs (longueur + filtre) dans une barre personnalisée
            const length = wrapper.find('.dataTables_length').detach();
            const filter = wrapper.find('.dataTables_filter').detach();

            // Créer la barre d'outils personnalisée
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);

            // Ajouter le bouton "Nouvelle carte postale"
            toolbar.append(`
                <a href="#"
                   class="btn btn-sm btn-vintage"
                   data-bs-toggle="modal"
                   data-bs-target="#modalNouvellePostcard">
                    <i class="bi bi-plus-lg"></i> Nouvelle carte postale
                </a>
            `);

            // Insérer la barre en haut du wrapper
            wrapper.prepend(toolbar);
        }
    });
}
