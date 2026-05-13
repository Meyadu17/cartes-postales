// =====================
// SUPPRESSION
// =====================
export function initSuppression(config, tables, showToast, closeModal) {
    let suppressionUrl = null;

    $(document).on('click', '.btn-action-delete', function() {
        const id   = $(this).data('id');
        const nom  = $(this).data('nom');
        const type = $(this).data('type');

        const modal       = new bootstrap.Modal('#modalConfirmSupression');
        const message     = document.getElementById('suppressionMessage');
        const dependances = document.getElementById('suppressionDependances');

        message.innerHTML = `Attention, vous vous apprêtez à supprimer <strong>${nom}</strong>. Cette action est irréversible. Êtes-vous sûr de ce choix ?`;
        dependances.innerHTML = '';

        if (type === 'region') {
            console.log('urlRegionDelete:', config.urlRegionDelete);
            console.log('id:', id);
            suppressionUrl = config.urlRegionDelete.replace('__ID__', id);
            console.log('suppressionUrl:', suppressionUrl);

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
                });

        } else if (type === 'departement') {
            suppressionUrl = config.urlDepartementDelete.replace('__ID__', id);

            fetch(config.urlDepartementDependances.replace('__ID__', id))
                .then(r => r.json())
                .then(data => {
                    if (data.villes?.length > 0) {
                        let html = '<div class="alert alert-warning mt-2"><strong>Villes concernées :</strong><ul class="mb-0 mt-1">';
                        html += data.villes.map(v => `<li>${v}</li>`).join('');
                        html += '</ul></div>';
                        dependances.innerHTML = html;
                    }
                });

        } else if (type === 'ville') {
            suppressionUrl = config.urlVilleDelete.replace('__ID__', id);
        }

        modal.show();
    });

    document.getElementById('btnConfirmerSuppression').addEventListener('click', function() {
        if (!suppressionUrl) return;

        fetch(suppressionUrl, { method: 'DELETE' })
            .then(r => r.json())
            .then(() => {
                closeModal('#modalConfirmSupression');
                showToast('Suppression effectuée !');
                tables.regions.ajax.reload();
                tables.departements.ajax.reload();
                tables.villes.ajax.reload();
                suppressionUrl = null;
            })
            .catch(err => {
                console.error('Erreur suppression:', err);
                alert('Erreur lors de la suppression.');
            });
    });
}
