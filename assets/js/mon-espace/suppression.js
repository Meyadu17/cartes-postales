// =====================
// SUPPRESSION
// =====================
export function initSuppression(config, tables, showToast, closeModal) {
    // URL de suppression construite dynamiquement selon l'élément cliqué
    let suppressionUrl = null;

    // ── Clic sur un bouton "Supprimer" ──────────────────────────────────────
    $(document).on('click', '.btn-action-delete', function() {
        const id   = $(this).data('id');
        const nom  = $(this).data('nom');
        const type = $(this).data('type');

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

            // Chargement des dépendances (départements + villes rattachées)
            fetch(config.urlRegionDependances.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    if (data.departements?.length > 0) {
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
                    }
                })
                .catch(err => console.error('   ❌ Erreur chargement dépendances région :', err));

        // ── Cas : suppression d'un DÉPARTEMENT ────────────────────────────
        } else if (type === 'departement') {
            suppressionUrl = config.urlDepartementDelete.replace('__ID__', id);

            // Chargement des dépendances (villes rattachées)
            fetch(config.urlDepartementDependances.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    if (data.villes?.length > 0) {
                        let html = '<div class="alert alert-warning mt-2"><strong>Villes concernées :</strong><ul class="mb-0 mt-1">';
                        html += data.villes.map(v => `<li>${v}</li>`).join('');
                        html += '</ul></div>';
                        dependances.innerHTML = html;
                    }
                })
                .catch(err => console.error('   ❌ Erreur chargement dépendances département :', err));

        // ── Cas : suppression d'une VILLE ─────────────────────────────────
        } else if (type === 'ville') {
            suppressionUrl = config.urlVilleDelete.replace('__ID__', id);
            // Pas de dépendances à charger pour une ville
        }

        // Affichage de la modale de confirmation
        modal.show();
    });

    // ── Clic sur "Confirmer la suppression" ─────────────────────────────────
    document.getElementById('btnConfirmerSuppression').addEventListener('click', function() {
        if (!suppressionUrl) {
            console.warn('⚠️ btnConfirmerSuppression cliqué mais suppressionUrl est null — abandon');
            return;
        }

        fetch(suppressionUrl, { method: 'DELETE' })
            .then(r => r.json())
            .then(() => {
                // Fermeture de la modale
                closeModal('#modalConfirmSupression');

                // Notification utilisateur
                showToast('Suppression effectuée !');

                // Rechargement des trois DataTables pour refléter la suppression
                tables.regions.ajax.reload();
                tables.departements.ajax.reload();
                tables.villes.ajax.reload();

                // Réinitialisation de l'URL de suppression
                suppressionUrl = null;
            })
            .catch(err => {
                console.error('   ❌ Erreur lors de la suppression :', err);
                alert('Erreur lors de la suppression.');
            });
    });
}
