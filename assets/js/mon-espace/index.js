import { readConfig }       from './config.js';
import { showToast }        from './toast.js';
import { closeModal, submitForm, initEditModal } from './modal.js';
import { initRegions }      from './regions.js';
import { initDepartements } from './departements.js';
import { initVilles }       from './villes.js';
import { initSuppression }  from './suppression.js';

$(document).ready(function() {
    console.log('📌 document ready exécuté');

    const config = readConfig();
    const tables = {};

    tables.regions      = initRegions(config);
    tables.departements = initDepartements(config);
    tables.villes       = initVilles(config);

    // Édition via modale générique
    initEditModal('#tableRegions',      '#modalEditerRegion',      config.urlRegionEdit,      tables.regions,      'Région modifiée !',      showToast, closeModal);
    initEditModal('#tableDepartements', '#modalEditerDepartement', config.urlDepartementEdit, tables.departements, 'Département modifié !',  showToast, closeModal);
    initEditModal('#tableVilles',       '#modalEditerVille',       config.urlVilleEdit,       tables.villes,       'Ville modifiée !',       showToast, closeModal);

    // =====================
    // CRÉATION
    // DÉTECTION DU BOUTON CLIQUÉ
    // =====================
    $(document).on('click', '[type="submit"][name="action"]', function(e) {
        e.preventDefault();
        const action   = $(this).val();
        const form     = $(this).closest('form');
        const keepOpen = action === 'save_and_new';
        const formId   = '#' + form.attr('id');

        const map = {
            'modalNouvelleRegion_form':      { modal: '#modalNouvelleRegion',      url: config.urlRegionNew,      table: tables.regions,      msg: 'Région créée !' },
            'modalNouveauDepartement_form':  { modal: '#modalNouveauDepartement',  url: config.urlDepartementNew, table: tables.departements, msg: 'Département créé !' },
            'modalNouvelleVille_form':       { modal: '#modalNouvelleVille',       url: config.urlVilleNew,       table: tables.villes,       msg: 'Ville créée !' },
        };

        const entry = map[form.attr('id')];
        if (entry) {
            submitForm(formId, entry.modal, entry.url, entry.table, entry.msg, keepOpen, showToast);
        }
    });

    // Suppression
    initSuppression(config, tables, showToast, closeModal);
});
