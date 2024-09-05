<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-labelledby="paymentDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentDetailModalLabel">Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>No. Referensi:</strong>
                    <span id="detail-merchant-ref"></span>
                </div>
                <div class="mb-3">
                    <strong>Nama Pelanggan:</strong>
                    <span id="detail-customer-name"></span>
                </div>
                <div class="mb-3">
                    <strong>Email Pelanggan:</strong>
                    <span id="detail-customer-email"></span>
                </div>
                <div class="mb-3">
                    <strong>Jumlah:</strong>
                    <span id="detail-amount"></span>
                </div>
                <div class="mb-3">
                    <strong>Metode Pembayaran:</strong>
                    <span id="detail-method"></span>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <span id="detail-status"></span>
                </div>
                <div class="mb-3">
                    <strong>Tanggal Pembayaran:</strong>
                    <span id="detail-created-at"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>