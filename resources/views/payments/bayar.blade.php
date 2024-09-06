@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Pembayaran</h4>
                </div>
                <div class="card-body">
                    <!-- Nav tabs for switching between transaction info and payment instructions -->
                    <ul class="nav nav-tabs" id="paymentTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="transaction-info-tab" data-bs-toggle="tab" data-bs-target="#transaction-info" type="button" role="tab" aria-controls="transaction-info" aria-selected="true">Informasi Transaksi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-instructions-tab" data-bs-toggle="tab" data-bs-target="#payment-instructions" type="button" role="tab" aria-controls="payment-instructions" aria-selected="false">Instruksi Pembayaran</button>
                        </li>
                    </ul>
                    <!-- Tab content -->
                    <div class="tab-content" id="paymentTabContent">
                        <!-- Transaction Info Tab -->
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
                                    <p><strong>Jumlah:</strong> Rp {{ number_format($tripayData['amount'], 0, ',', '.') }}</p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h5 class="text-muted mb-3">Informasi Pelanggan</h5>
                                    <p><strong>Nama:</strong> {{ $payment->customer_name }}</p>
                                    <p><strong>Email:</strong> {{ $payment->customer_email }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Payment Instructions Tab -->
                        <div class="tab-pane fade" id="payment-instructions" role="tabpanel" aria-labelledby="payment-instructions-tab">
                            <div class="alert alert-info mt-3">
                                <h5 class="alert-heading text-center">Instruksi Pembayaran</h5>
                                <p class="mb-2 text-center">Silakan transfer ke:</p>
                                <ul class="list-unstyled mt-2 text-center">
                                    <li class="mb-2">
                                        <strong>{{ $tripayData['payment_name'] }}</strong>
                                    </li>
                                    <!-- QRIS Payment -->
                                    @if(strtolower($tripayData['payment_name']) === 'qris')
                                    <li class="mb-3">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ $tripayData['qr_url'] }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                        </div>
                                    </li>
                                    @else
                                    <!-- Non-QRIS Payment -->
                                    <li class="mb-2">
                                        <strong>Nomor Rekening:</strong> <span id="payCode" class="me-2">{{ $tripayData['pay_code'] }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong>Jumlah Transfer:</strong> <span id="amount" class="me-2">Rp {{ number_format($tripayData['amount'], 0, ',', '.') }}</span>
                                    </li>
                                    @endif
                                </ul>
                                <p class="mt-3 mb-0 text-center"><strong>Batas Waktu Pembayaran:</strong></p>
                                <p class="text-danger text-center">{{ \Carbon\Carbon::parse($tripayData['expired_time'])->format('d M Y H:i') }}</p>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-muted mb-3">Instruksi Pembayaran</h5>
                                    <div class="alert alert-info">
                                        <!-- Displaying payment instructions dynamically -->
                                        @foreach ($tripayData['instructions'] as $instruction)
                                        <div class="mb-3">
                                            <h6>{{ $instruction['title'] }}</h6>
                                            <ol>
                                                @foreach ($instruction['steps'] as $step)
                                                <li>{!! $step !!}</li>
                                                @endforeach
                                            </ol>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Button -->
                    <div class="card-footer text-center">
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection