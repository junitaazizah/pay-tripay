@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Detail Pembayaran</h4>
                </div>
                <div class="card-body">
                    @if($payment->status == 'Pending')
                    <ul class="nav nav-tabs" id="paymentTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="transaction-info-tab" data-bs-toggle="tab" data-bs-target="#transaction-info" type="button" role="tab" aria-controls="transaction-info" aria-selected="true">Informasi Transaksi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-instructions-tab" data-bs-toggle="tab" data-bs-target="#payment-instructions" type="button" role="tab" aria-controls="payment-instructions" aria-selected="false">Instruksi Pembayaran</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="paymentTabContent">
                        <div class="tab-pane fade show active" id="transaction-info" role="tabpanel" aria-labelledby="transaction-info-tab">
                            <div class="row mb-4 mt-3">
                                <div class="col-12 col-md-6">
                                    <h5 class="text-muted mb-3">Informasi Transaksi</h5>
                                    <p><strong>No. Referensi:</strong> <span class="badge bg-success">{{ $payment->merchant_ref }}</span></p>
                                    <p><strong>Status:</strong>
                                        <span class="badge bg-{{ $payment->status === 'Paid' ? 'success' : ($payment->status === 'Pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </p>
                                    <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h5 class="text-muted mb-3">Informasi Pelanggan</h5>
                                    <p><strong>Nama:</strong> {{ $payment->customer_name }}</p>
                                    <p><strong>Email:</strong> {{ $payment->customer_email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="payment-instructions" role="tabpanel" aria-labelledby="payment-instructions-tab">
                            <div class="alert alert-info mt-3">
                                <h5 class="alert-heading">Instruksi Pembayaran</h5>
                                <p class="mb-0">Silakan transfer ke:</p>
                                <ul class="list-unstyled mt-2">
                                    <li class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <strong>{{ $tripayData['payment_name'] }}</strong>
                                        </div>
                                    </li>
                                    @if(strtolower($tripayData['payment_name']) === 'qris')
                                    <li class="mb-2">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <img src="{{ $tripayData['qr_url'] }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                        </div>
                                    </li>
                                    @else
                                    <li class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span><strong>Nomor Rekening:</strong></span>
                                            <div>
                                                <span id="payCode" class="me-2">{{ $tripayData['pay_code'] }}</span>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('payCode', this)">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <span class="copy-feedback" style="display: none; color: green; margin-left: 5px;">Disalin</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span><strong>Jumlah Transfer:</strong></span>
                                            <div>
                                                <span id="amount" class="me-2">Rp {{ number_format($tripayData['amount'], 0, ',', '.') }}</span>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('amount', this)">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <span class="copy-feedback" style="display: none; color: green; margin-left: 5px;">Disalin</span>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                                <p class="mt-3 mb-0"><strong>Batas Waktu Pembayaran:</strong></p>
                                <p class="text-danger">{{ \Carbon\Carbon::parse($tripayData['expired_time'])->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row mb-4">
                        <div class="col-12 col-md-6">
                            <h5 class="text-muted mb-3">Informasi Transaksi</h5>
                            <p><strong>No. Referensi:</strong> <span class="badge bg-success">{{ $payment->merchant_ref }}</span></p>
                            <p><strong>Status:</strong>
                                <span class="badge bg-success">Sudah Lunas</span>
                            </p>
                            <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <h5 class="text-muted mb-3">Informasi Pelanggan</h5>
                            <p><strong>Nama:</strong> {{ $payment->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $payment->customer_email }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
                    @if($payment->status == 'Pending')
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#paymentStatusModalPending">Cek Status Pembayaran</button>
                    @elseif($payment->status === 'Paid')
                    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#paymentStatusModalPaid">Lihat Detail Pembayaran</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Pending Status -->
<div class="modal fade" id="paymentStatusModalPending" tabindex="-1" aria-labelledby="paymentStatusModalPendingLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentStatusModalPendingLabel">Status Pembayaran (Pending)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>No. Referensi:</strong> {{ $payment->merchant_ref }}</p>
                <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
                <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                <p><strong>Nama Pelanggan:</strong> {{ $payment->customer_name }}</p>
                <p><strong>Email Pelanggan:</strong> {{ $payment->customer_email }}</p>
                <p><strong>Batas Waktu Pembayaran:</strong> {{ \Carbon\Carbon::parse($tripayData['expired_time'])->format('d M Y H:i') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Paid Status -->
<div class="modal fade" id="paymentStatusModalPaid" tabindex="-1" aria-labelledby="paymentStatusModalPaidLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentStatusModalPaidLabel">Detail Pembayaran (Sudah Lunas)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>No. Referensi:</strong> {{ $payment->merchant_ref }}</p>
                <p><strong>Status:</strong> Sudah Lunas</p>
                <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                <p><strong>Nama Pelanggan:</strong> {{ $payment->customer_name }}</p>
                <p><strong>Email Pelanggan:</strong> {{ $payment->customer_email }}</p>
                <p><strong>Tanggal Pembayaran:</strong> {{ $payment->updated_at->format('d M Y H:i') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(elementId, button) {
        var text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(function() {
            var feedback = button.nextElementSibling;
            feedback.style.display = 'inline';
            setTimeout(function() {
                feedback.style.display = 'none';
            }, 2000);
        }, function(err) {
            console.error('Gagal menyalin teks: ', err);
        });
    }
</script>
@endpush

@push('styles')
<style>
    .copy-feedback {
        font-size: 0.8rem;
        font-style: italic;
    }
</style>
@endpush