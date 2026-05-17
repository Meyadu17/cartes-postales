// =====================
// TOAST (notification utilisateur)
// =====================

/**
 * Affiche une notification toast Bootstrap en bas à droite de l'écran.
 * Si le toast n'existe pas encore dans le DOM, il est créé dynamiquement.
 *
 * @param {string} message - Le texte à afficher dans le toast
 */
export function showToast(message) {

    // ── Création dynamique du toast s'il n'existe pas encore dans le DOM ──
    if ($('#liveToast').length === 0) {
        $('body').append(`
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>
        `);
        console.log('   ✅ Toast injecté dans le DOM');
    } else {
        console.log('   ✅ Toast déjà présent dans le DOM, réutilisation');
    }

    // ── Mise à jour du message affiché ────────────────────────────────────
    $('#toastMessage').text(message);

    // ── Initialisation et affichage du toast (disparaît après 2 secondes) ─
    const toast = new bootstrap.Toast($('#liveToast')[0], { delay: 2000 });
    toast.show();
}
