<!-- Tambahkan ini di bagian atas file, setelah tombol "Buat Pembayaran Baru" -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel">Tambah Pembayaran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama Pelanggan</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" value="Muhamad Widyantoro" placeholder="Masukkan Nama lengkap" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_email" class="form-label">Email Pelanggan</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" value="contoh@gmail.com" placeholder="Masukkan Email Aktif" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Kategori pembayaraan</label>
                        <select name="payment_kategori" class="form-select">
                            <option value="#">Silahkan Pilih</option>
                            <option value="BPJS PESERTA MAGANG">BPJS PESERTA MAGANG</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="amount" name="amount" min="0" step="1000" value="100000" placeholder="Masukan Nominal" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="method" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="method" name="payment_method" required>
                            <option value="">Pilih metode pembayaran</option>
                            @foreach ($channels as $channel)
                            <option value="{{ $channel['code'] ?? '' }}">
                                {{ $channel['name'] ?? 'Unknown' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var addPaymentModal = document.getElementById('addPaymentModal');
        addPaymentModal.addEventListener('shown.bs.modal', function() {
            document.getElementById('customer_name').focus();
        });
    });
</script>
@endpush