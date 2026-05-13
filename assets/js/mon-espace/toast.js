export function showToast(message) {
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
    }
    $('#toastMessage').text(message);
    const toast = new bootstrap.Toast($('#liveToast')[0], { delay: 2000 });
    toast.show();
}
