// =====================
// IMPORTS
// =====================
import { readConfig }       from './config.js';
import { showToast }        from './toast.js';
import { initRegions }      from './regions.js';
import { initDepartements } from './departements.js';
import { initVilles }       from './villes.js';
import { initSuppression }  from './suppression.js';
import { closeModal, submitForm } from './modal.js';
import { initEditModal } from './modal-edit.js';

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
    initEditModal({
        tableSelector: '#tableRegions',
        modalId:       '#modalEditerRegion',
        urlPattern:    config.urlRegionEdit,
        tableInstance: tables.regions,
        toastMessage:  'Région modifiée avec succès',
        showToast,
        closeModal,
    });

    console.log('   ✅ Modale édition régions initialisée');

    // Édition département (avec select région)
    initEditModal({
        tableSelector: '#tableDepartements',
        modalId:       '#modalEditerDepartement',
        urlPattern:    config.urlDepartementEdit,
        tableInstance: tables.departements,
        toastMessage:  'Département modifié avec succès',
        showToast,
        closeModal,
        extraFields: { regionId: 'region' },
    });
    console.log('   ✅ Modale édition départements initialisée');

    // Édition ville (avec select département)
    initEditModal({
        tableSelector: '#tableVilles',
        modalId:       '#modalEditerVille',
        urlPattern:    config.urlVilleEdit,
        tableInstance: tables.villes,
        toastMessage:  'Ville modifiée avec succès',
        showToast,
        closeModal,
        extraFields: { departementId: 'departement' },
    });
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
