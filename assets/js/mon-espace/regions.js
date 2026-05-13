// =====================
// RÉGIONS
// =====================
export function initRegions(config) {
    console.log('📊 initRegions appelé');
    console.log('   URL ajax :', config.urlRegions);

    return $('#tableRegions').DataTable({
        // URL de récupération des données
        ajax: {
            url: config.urlRegions,
            dataSrc: '',
            // Log avant envoi de la requête
            beforeSend: function() {
                console.log('🔄 Chargement des régions depuis :', config.urlRegions);
            }
        },

        // Tri par défaut sur la colonne "nom" (index 1)
        order: [[1, 'asc']],

        // Définition des colonnes
        columns: [
            // Code de la région
            { data: 'code', width: '15%' },

            // Nom de la région
            { data: 'nom', width: '30%' },

            // Description de la région
            { data: 'description', width: '45%' },

            // Colonne actions (édition / suppression)
            {
                data: 'id',
                orderable: false,
                width: '15%',
                className: 'text-end',
                render: function(id, type, row) {
                    console.log(`   🏗️ Rendu actions pour région id=${id}, nom="${row.nom}"`);
                    return `
                        <button class="btn btn-sm btn-action-edit"
                                data-id="${id}"
                                title="Modifier la région ${row.nom}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-action-delete"
                                data-id="${id}"
                                data-nom="${row.nom}"
                                data-type="region"
                                title="Supprimer la région ${row.nom}">
                            <i class="bi bi-trash"></i>
                        </button>`;
                }
            }
        ],

        // Traduction française
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },

        // Après initialisation complète du tableau
        initComplete: function() {
            console.log('✅ DataTable régions initialisée');

            const wrapper = $('#tableRegions_wrapper');

            // Déplacer les contrôles natifs (longueur + filtre) dans une barre personnalisée
            const length = wrapper.find('.dataTables_length').detach();
            const filter = wrapper.find('.dataTables_filter').detach();
            console.log('   🔧 Contrôles natifs détachés (length, filter)');

            // Créer la barre d'outils personnalisée
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);

            // Ajouter le bouton "Nouvelle région"
            toolbar.append(`
                <a href="#"
                   class="btn btn-sm btn-vintage"
                   data-bs-toggle="modal"
                   data-bs-target="#modalNouvelleRegion">
                    <i class="bi bi-plus-lg"></i> Nouvelle région
                </a>
            `);
            console.log('   ➕ Bouton "Nouvelle région" ajouté à la toolbar');

            // Insérer la barre en haut du wrapper
            wrapper.prepend(toolbar);
            console.log('   ✅ Toolbar insérée dans le wrapper');
        }
    });
}
