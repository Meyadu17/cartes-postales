// =====================
// VILLES
// =====================
export function initVilles(config) {
    console.log('📊 initVilles appelé');
    console.log('   URL ajax :', config.urlVilles);

    return $('#tableVilles').DataTable({
        // URL de récupération des données
        ajax: {
            url: config.urlVilles,
            dataSrc: '',
            // Log avant envoi de la requête
            beforeSend: function() {
                console.log('🔄 Chargement des villes depuis :', config.urlVilles);
            }
        },

        // Tri par défaut sur la colonne "nom" (index 0)
        order: [[0, 'asc']],

        // Définition des colonnes
        columns: [
            // Nom de la ville
            { data: 'nom', width: '30%' },

            // Code postale
            { data: 'codePostal', width: '15%' },

            // Département associé
            { data: 'departement', width: '40%' },

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
                                title="Modifier la ville ${row.nom}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-action-delete"
                                data-id="${id}"
                                data-nom="${row.nom}"
                                data-type="ville"
                                title="Supprimer la ville ${row.nom}">
                            <i class="bi bi-trash"></i>
                        </button>`;
                }
            }
        ],

        // Traduction française
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },

        // Après initialisation complète du tableau
        initComplete: function() {
            console.log('✅ DataTable villes initialisée');

            const wrapper = $('#tableVilles_wrapper');

            // Déplacer les contrôles natifs (longueur + filtre) dans une barre personnalisée
            const length = wrapper.find('.dataTables_length').detach();
            const filter = wrapper.find('.dataTables_filter').detach();
            console.log('   🔧 Contrôles natifs détachés (length, filter)');

            // Créer la barre d'outils personnalisée
            const toolbar = $('<div class="dt-custom-toolbar"></div>');
            toolbar.append(length).append(filter);

            // Ajouter le bouton "Nouvelle ville"
            toolbar.append(`
                <a href="#"
                   class="btn btn-sm btn-vintage"
                   data-bs-toggle="modal"
                   data-bs-target="#modalNouvelleVille">
                    <i class="bi bi-plus-lg"></i> Nouvelle ville
                </a>
            `);
            console.log('   ➕ Bouton "Nouvelle ville" ajouté à la toolbar');

            // Insérer la barre en haut du wrapper
            wrapper.prepend(toolbar);
            console.log('   ✅ Toolbar insérée dans le wrapper');
        }
    });
}
