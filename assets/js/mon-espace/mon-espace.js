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
import { initEditModal }    from './modal-edit.js';
import { initDeptCropper, initDeptEditCropper }  from './cropper/dept-cropper.js';


let deptCropper = null;
let deptEditCropper = null;

$(document).ready(function() {
    console.log('📌 document ready exécuté');

    // =====================
    // CONFIGURATION
    // =====================
    const config = readConfig();
    console.log('⚙️ Configuration chargée :', config);

    // =====================
    // CROPPER DÉPARTEMENT
    // On crée l'instance ici, mais on NE l'initialise PAS encore.
    // Pourquoi ? Parce que la modale est cachée au chargement de la page,
    // et Cropper.js a besoin que l'image soit visible pour calculer ses dimensions.
    // =====================
    deptCropper = initDeptCropper();
    deptEditCropper = initDeptEditCropper();
    
    // Quand la modale s'ouvre → on initialise le cropper
    $('#modalNouveauDepartement').on('shown.bs.modal', function () {deptCropper.init();});
    $('#modalEditerDepartement').on('shown.bs.modal', function () {deptEditCropper.init();});

    // Quand la modale se ferme → on détruit le cropper et on remet à zéro
    $('#modalNouveauDepartement').on('hidden.bs.modal', function () {deptCropper.destroy();});
    $('#modalEditerDepartement').on('hidden.bs.modal', function () {deptEditCropper.destroy();});

    // =====================
    // INITIALISATION DES DATATABLES
    // =====================
    const tables = {};
    tables.regions      = initRegions(config);
    tables.departements = initDepartements(config);
    tables.villes       = initVilles(config);

    // =====================
    // INITIALISATION DES MODALES D'ÉDITION
    // =====================
    console.log('✏️ Initialisation des modales d\'édition...');

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

    initEditModal({
        tableSelector: '#tableDepartements',
        modalId:       '#modalEditerDepartement',
        urlPattern:    config.urlDepartementEdit,
        tableInstance: tables.departements,
        toastMessage:  'Département modifié avec succès',
        showToast,
        closeModal,
        extraFields: { regionId: 'region' },
        cropper:     deptEditCropper,
        logoUrlKey:  'logoUrl',
    });

    console.log('   ✅ Modale édition départements initialisée');

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
    // =====================
    console.log('➕ Initialisation des boutons de création...');

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

        // Si c'est le formulaire département, on injecte le base64 avant envoi
        if (formId === 'modalNouveauDepartement_form') {
            console.log('   🖼️ Injection base64 du crop département...');
            deptCropper.injectBase64();
        }

        const entry = map[formId];

        if (entry) {
            console.log(`   ✅ Entrée trouvée dans le map pour "${formId}"`);
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
