@extends('layouts.app')

@section('title', 'Detail Pembayaran Pending')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Pembayaran <span class="badge bg-danger">{{ $payment->status }}</span></h4>
                    <span class="badge bg-light text-dark">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:i') }}</span>
                </div>
                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="paymentDetailsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button" role="tab" aria-controls="detail" aria-selected="true">Detail Pembayaran</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-instructions-tab" data-bs-toggle="tab" data-bs-target="#payment-instructions" type="button" role="tab" aria-controls="instruction" aria-selected="false">Instruksi Pembayaran</button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content mt-4" id="paymentDetailsTabContent">
                        <!-- Detail Pembayaran Tab -->
                        <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                            <div class="text-center mb-4">
                                <i class="fas fa-exclamation-circle text-warning" style="font-size: 80px;"></i>
                                <h5 class="mt-3">Pembayaran Anda Masih Dalam Proses</h5>
                                <p class="text-muted">Silakan tunggu hingga pembayaran selesai diproses.</p>
                            </div>

                            <!-- Transaction Information -->
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-3">
                                    <h5 class="text-muted">Informasi Transaksi</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>No. Referensi:</strong>
                                            <span class="badge bg-primary">{{ $payment->merchant_ref }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Status:</strong>
                                            <span class="badge bg-warning text-dark">{{ ucfirst($payment->status) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Pembayaran</strong>
                                            <span class="badge bg-primary text-white">{{ $tripayData['payment_name'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Rekening</strong>
                                            <span class="badge bg-primary text-white">{{ $tripayData['pay_code'] }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Jumlah:</strong>
                                            Rp {{ number_format($tripayData['amount'], 0, ',', '.') }}
                                        </li>
                                    </ul>
                                </div>

                                <!-- Customer Information -->
                                <div class="col-12 col-md-6">
                                    <h5 class="text-muted">Informasi Pelanggan</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Nama:</strong>
                                            {{ $payment->customer_name }}
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Email:</strong>
                                            {{ $payment->customer_email }}
                                        </li>
                                    </ul>
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

                    <!-- Footer Section -->
                    <div class="card-footer text-center">
                        <a href="{{ route('payments.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection