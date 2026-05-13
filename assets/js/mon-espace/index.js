// =====================
// IMPORTS
// =====================
import { readConfig }       from './config.js';
import { showToast }        from './toast.js';
import { closeModal, submitForm, initEditModal } from './modal.js';
import { initRegions }      from './regions.js';
import { initDepartements } from './departements.js';
import { initVilles }       from './villes.js';
import { initSuppression }  from './suppression.js';

$(document).ready(function() {
    console.log('📌 document ready exécuté');

    // =====================
    // CONFIGURATION
    // =====================
    const config = readConfig();
    console.log('⚙️ Configuration chargée :', config);

    // =====================
    // INITIALISATION DES DATATABLES
    // =====================
    const tables = {};

    console.log('📊 Initialisation des DataTables...');
    tables.regions      = initRegions(config);
    console.log('   ✅ DataTable régions initialisée');
    tables.departements = initDepartements(config);
    console.log('   ✅ DataTable départements initialisée');
    tables.villes       = initVilles(config);
    console.log('   ✅ DataTable villes initialisée');

    // =====================
    // INITIALISATION DES MODALES D'ÉDITION
    // =====================
    console.log('✏️ Initialisation des modales d\'édition...');

    // Édition région
    initEditModal(
        '#tableRegions',
        '#editRegionModal',
        '/admin/mon-espace/regions/__ID__/edit',
        tables.regions,
        'Région modifiée avec succès',
        showToast,
        closeModal
    );
    console.log('   ✅ Modale édition régions initialisée');

    // Édition département (avec select région)
    initEditModal(
        '#tableDepartements',
        '#editDepartementModal',
        '/admin/mon-espace/departements/__ID__/edit',
        tables.departements,
        'Département modifié avec succès',
        showToast,
        closeModal,
        { regionId: 'region' }  // clé data JSON => nom du champ select dans le formulaire
    );
    console.log('   ✅ Modale édition départements initialisée');

    // Édition ville (avec select département)
    initEditModal(
        '#tableVilles',
        '#editVilleModal',
        '/admin/mon-espace/villes/__ID__/edit',
        tables.villes,
        'Ville modifiée avec succès',
        showToast,
        closeModal,
        { departementId: 'departement' }  // clé data JSON => nom du champ select dans le formulaire
    );
    console.log('   ✅ Modale édition villes initialisée');

    // =====================
    // CRÉATION
    // DÉTECTION DU BOUTON CLIQUÉ (Enregistrer / Enregistrer et nouveau)
    // =====================
    console.log('➕ Initialisation des boutons de création...');

    // Mapping formulaire => configuration
    const map = {
        'modalNouvelleRegion_form':     { modal: '#modalNouvelleRegion',     url: config.urlRegionNew,      table: tables.regions,      msg: 'Région créée !' },
        'modalNouveauDepartement_form': { modal: '#modalNouveauDepartement', url: config.urlDepartementNew, table: tables.departements, msg: 'Département créé !' },
        'modalNouvelleVille_form':      { modal: '#modalNouvelleVille',      url: config.urlVilleNew,       table: tables.villes,       msg: 'Ville créée !' },
    };

    $(document).on('click', '[type="submit"][name="action"]', function(e) {
        e.preventDefault();

        const action   = $(this).val();
        const form     = $(this).closest('form');
        const formId   = form.attr('id');
        const keepOpen = action === 'save_and_new';

        console.log(`🖱️ Bouton submit cliqué`);
        console.log(`   action   : ${action}`);
        console.log(`   formId   : ${formId}`);
        console.log(`   keepOpen : ${keepOpen}`);

        const entry = map[formId];

        if (entry) {
            console.log(`   ✅ Entrée trouvée dans le map pour "${formId}"`);
            console.log(`   modal : ${entry.modal}`);
            console.log(`   url   : ${entry.url}`);
            console.log(`   msg   : ${entry.msg}`);
            submitForm('#' + formId, entry.modal, entry.url, entry.table, entry.msg, keepOpen, showToast);
        } else {
            console.warn(`   ⚠️ Aucune entrée trouvée dans le map pour le formId : "${formId}"`);
        }
    });

    // =====================
    // SUPPRESSION
    // =====================
    console.log('🗑️ Initialisation de la suppression...');
    initSuppression(config, tables, showToast, closeModal);
    console.log('   ✅ Suppression initialisée');
});
