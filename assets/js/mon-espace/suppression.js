// =====================
// SUPPRESSION
// =====================
export function initSuppression(config, tables, showToast, closeModal) {
    console.log('🗑️ initSuppression appelé');

    // URL de suppression construite dynamiquement selon l'élément cliqué
    let suppressionUrl = null;

    // ── Clic sur un bouton "Supprimer" ──────────────────────────────────────
    $(document).on('click', '.btn-action-delete', function() {
        const id   = $(this).data('id');
        const nom  = $(this).data('nom');
        const type = $(this).data('type');
        console.log(`🗑️ Clic suppression détecté — type="${type}", id=${id}, nom="${nom}"`);

        // Récupération de la modale et de ses zones de contenu
        const modal       = new bootstrap.Modal('#modalConfirmSupression');
        const message     = document.getElementById('suppressionMessage');
        const dependances = document.getElementById('suppressionDependances');

        // Message de confirmation générique
        message.innerHTML = `Attention, vous vous apprêtez à supprimer <strong>${nom}</strong>. Cette action est irréversible. Êtes-vous sûr de ce choix ?`;
        dependances.innerHTML = '';

        // ── Cas : suppression d'une RÉGION ────────────────────────────────
        if (type === 'region') {
            suppressionUrl = config.urlRegionDelete.replace('__ID__', id);
            console.log('   🌍 Suppression région — URL construite :', suppressionUrl);

            // Chargement des dépendances (départements + villes rattachées)
            console.log('   🔍 Chargement des dépendances région depuis :', config.urlRegionDependances.replace('__ID__', id));
            fetch(config.urlRegionDependances.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    console.log('   📦 Dépendances région reçues :', data);
                    if (data.departements?.length > 0) {
                        console.log(`   ⚠️ ${data.departements.length} département(s) dépendant(s) détecté(s)`);
                        let html = '<div class="alert alert-warning mt-2"><strong>Dépendances :</strong><ul class="mb-0 mt-1">';
                        data.departements.forEach(dept => {
                            html += `<li><strong>${dept.nom}</strong>`;
                            if (dept.villes.length > 0) {
                                html += '<ul>' + dept.villes.map(v => `<li>${v}</li>`).join('') + '</ul>';
                            }
                            html += '</li>';
                        });
                        html += '</ul></div>';
                        dependances.innerHTML = html;
                    } else {
                        console.log('   ✅ Aucune dépendance pour cette région');
                    }
                })
                .catch(err => console.error('   ❌ Erreur chargement dépendances région :', err));

        // ── Cas : suppression d'un DÉPARTEMENT ────────────────────────────
        } else if (type === 'departement') {
            suppressionUrl = config.urlDepartementDelete.replace('__ID__', id);
            console.log('   🏛️ Suppression département — URL construite :', suppressionUrl);

            // Chargement des dépendances (villes rattachées)
            console.log('   🔍 Chargement des dépendances département depuis :', config.urlDepartementDependances.replace('__ID__', id));
            fetch(config.urlDepartementDependances.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    console.log('   📦 Dépendances département reçues :', data);
                    if (data.villes?.length > 0) {
                        console.log(`   ⚠️ ${data.villes.length} ville(s) dépendante(s) détectée(s)`);
                        let html = '<div class="alert alert-warning mt-2"><strong>Villes concernées :</strong><ul class="mb-0 mt-1">';
                        html += data.villes.map(v => `<li>${v}</li>`).join('');
                        html += '</ul></div>';
                        dependances.innerHTML = html;
                    } else {
                        console.log('   ✅ Aucune dépendance pour ce département');
                    }
                })
                .catch(err => console.error('   ❌ Erreur chargement dépendances département :', err));

        // ── Cas : suppression d'une VILLE ─────────────────────────────────
        } else if (type === 'ville') {
            suppressionUrl = config.urlVilleDelete.replace('__ID__', id);
            console.log('   🏙️ Suppression ville — URL construite :', suppressionUrl);
            // Pas de dépendances à charger pour une ville
        }

        // Affichage de la modale de confirmation
        console.log('   📂 Ouverture de la modale de confirmation');
        modal.show();
    });

    // ── Clic sur "Confirmer la suppression" ─────────────────────────────────
    document.getElementById('btnConfirmerSuppression').addEventListener('click', function() {
        if (!suppressionUrl) {
            console.warn('⚠️ btnConfirmerSuppression cliqué mais suppressionUrl est null — abandon');
            return;
        }
        console.log('✅ Confirmation suppression — envoi DELETE vers :', suppressionUrl);

        fetch(suppressionUrl, { method: 'DELETE' })
            .then(r => r.json())
            .then(() => {
                console.log('   ✅ Suppression effectuée avec succès');

                // Fermeture de la modale
                closeModal('#modalConfirmSupression');

                // Notification utilisateur
                showToast('Suppression effectuée !');

                // Rechargement des trois DataTables pour refléter la suppression
                console.log('   🔄 Rechargement des DataTables...');
                tables.regions.ajax.reload();
                tables.departements.ajax.reload();
                tables.villes.ajax.reload();
                console.log('   ✅ DataTables rechargées');

                // Réinitialisation de l'URL de suppression
                suppressionUrl = null;
            })
            .catch(err => {
                console.error('   ❌ Erreur lors de la suppression :', err);
                alert('Erreur lors de la suppression.');
            });
    });
}
