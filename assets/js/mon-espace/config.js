// =====================
// CONFIG
// =====================

/**
 * Lit les URLs de l'API depuis l'élément HTML #app-config (data-attributes).
 * Centralise toutes les URLs utilisées dans l'application.
 *
 * @returns {Object} config - Objet contenant toutes les URLs
 */
export function readConfig() {
    console.log('⚙️ readConfig appelé — lecture des data-attributes depuis #app-config');

    const config = {
        // ── URLs de récupération des listes (DataTables) ──────────────────
        urlRegions:                $('#app-config').data('url-region-data'),
        urlDepartements:           $('#app-config').data('url-departement-data'),
        urlVilles:                 $('#app-config').data('url-ville-data'),

        // ── URLs de création (formulaires "Nouveau...") ───────────────────
        urlRegionNew:              $('#app-config').data('url-region-new'),
        urlDepartementNew:         $('#app-config').data('url-departement-new'),
        urlVilleNew:               $('#app-config').data('url-ville-new'),

        // ── URLs d'édition (formulaires "Modifier...") ────────────────────
        urlRegionEdit:             $('#app-config').data('url-region-edit'),
        urlDepartementEdit:        $('#app-config').data('url-departement-edit'),
        urlVilleEdit:              $('#app-config').data('url-ville-edit'),

        // ── URLs de suppression (requêtes DELETE) ─────────────────────────
        urlRegionDelete:           $('#app-config').data('url-region-delete'),
        urlDepartementDelete:      $('#app-config').data('url-departement-delete'),
        urlVilleDelete:            $('#app-config').data('url-ville-delete'),

        // ── URLs de chargement des dépendances (avant suppression) ────────
        urlRegionDependances:      $('#app-config').data('url-region-dependances'),
        urlDepartementDependances: $('#app-config').data('url-departement-dependances'),
    };

    // Vérification : signaler les URLs manquantes ou non définies
    Object.entries(config).forEach(([key, value]) => {
        if (!value) {
            console.warn(`   ⚠️ config.${key} est vide ou non défini`);
        }
    });

    console.log('   ✅ Config chargée :', config);

    return config;
}
