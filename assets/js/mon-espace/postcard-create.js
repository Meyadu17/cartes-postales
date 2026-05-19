// postcard-create.js
document.addEventListener('DOMContentLoaded', function () {
    const departementSelect = document.querySelector('#postcard_departement');
    const villeSelect = document.querySelector('#postcard_ville');
    const villeWrapper = document.querySelector('.ville-wrapper');

    console.log('departementSelect:', departementSelect);
    console.log('villeSelect:', villeSelect);
    console.log('villeWrapper:', villeWrapper);

    if (!departementSelect) {
        console.log('departementSelect introuvable, script arrêté');
        return;
    }

    // Cacher la ville au départ
    if (villeWrapper) {
        villeWrapper.style.display = 'none';
        console.log('villeWrapper caché');
    } else {
        console.log('villeWrapper introuvable, impossible de le cacher');
    }

    departementSelect.addEventListener('change', function () {
        const departementId = this.value;
        console.log('Département sélectionné, id:', departementId);

        if (!departementId) {
            if (villeWrapper) villeWrapper.style.display = 'none';
            if (villeSelect) villeSelect.innerHTML = '<option value="">Sélectionner une ville</option>';
            return;
        }

        const url = `/admin/mon-espace/villes/par-departement/${departementId}`;
        console.log('Fetch URL:', url);

        fetch(url)
            .then(res => {
                console.log('Réponse HTTP status:', res.status);
                return res.json();
            })
            .then(villes => {
                console.log('Villes reçues:', villes);

                if (!villeSelect) return;
                villeSelect.innerHTML = '<option value="">Sélectionner une ville</option>';

                villes.forEach(ville => {
                    const option = document.createElement('option');
                    option.value = ville.id;
                    option.textContent = ville.nom;
                    villeSelect.appendChild(option);
                });

                if (villeWrapper) villeWrapper.style.display = 'block';
            })
            .catch(err => {
                console.error('Erreur fetch:', err);
            });
    });
});
