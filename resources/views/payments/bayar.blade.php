@extends('layouts.app')

@section('title', 'Detail Pembayaran Pending')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm rounded">
                <div class="card-header {{ $payment->status === 'Paid' ? 'bg-success' : 'bg-warning' }} text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        @if($payment->status === 'Paid')
                        <span class="badge bg-primary text-white">Riwayat Pembayaran</span>
                        @else
                        <span class="badge bg-primary text-white">Detail Pembayaran</span><span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </h4>
                    <span class="badge bg-light text-dark">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:i') }}</span>
                </div>
                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="paymentDetailsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button" role="tab" aria-controls="detail" aria-selected="true">Detail Pembayaran</button>
                        </li>
                        @if($payment->status === 'Pending')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-instructions-tab" data-bs-toggle="tab" data-bs-target="#payment-instructions" type="button" role="tab" aria-controls="instruction" aria-selected="false">Instruksi Pembayaran</button>
                        </li>
                        @else
                        @endif
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content mt-4" id="paymentDetailsTabContent">
                        <!-- Detail Pembayaran Tab -->
                        <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                            @if($payment->status === 'Pending')
                            <div class="text-center mb-4">
                                <i class="fas fa-exclamation-circle text-warning" style="font-size: 80px;"></i>
                                <h5 class="mt-3">Pembayaran Anda Masih Dalam Proses</h5>
                                <p class="text-muted">Silakan tunggu hingga pembayaran selesai diproses.</p>
                            </div>
                            @elseif($payment->status === 'Paid')
                            <div class="text-center mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                                <h5 class="mt-3">Pembayaran Anda Telah Selesai</h5>
                                <p class="text-muted">Terima kasih, pembayaran Anda telah berhasil diproses.</p>
                            </div>
                            @else
                            <div class="text-center mb-4">
                                <i class="fas fa-question-circle text-secondary" style="font-size: 80px;"></i>
                                <h5 class="mt-3">Status Pembayaran Tidak Dikenal</h5>
                                <p class="text-muted">Hubungi kami untuk informasi lebih lanjut.</p>
                            </div>
                            @endif

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
                                            <strong>Kategori</strong>
                                            <span class="badge bg-primary text-white">{{ $payment->payment_kategori }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Status:</strong>
                                            <span class="badge bg-{{ $payment->status === 'Paid' ? 'primary' : ($payment->status === 'Pending' ? 'warning' : 'secondary') }} text-white">
                                                {{ $payment->status === 'Paid' ? 'Sudah Bayar' : ($payment->status === 'Pending' ? 'Belum Bayar' : 'Tidak Dikenal') }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Pembayaran</strong>
                                            <span class="badge bg-primary text-white">{{ $tripayData['payment_name'] }}</span>
                                        </li>
                                        <!-- Payment Info Based on Status -->
                                        @if($payment->status === 'Pending')
                                        @if(strtolower($tripayData['payment_name']) === 'qris')
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>QR Code:</strong>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $tripayData['qr_url'] }}" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                                            </div>
                                        </li>
                                        @else
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Rekening:</strong>
                                            <span class="badge bg-primary text-white">{{ $tripayData['pay_code'] }}</span>
                                        </li>
                                        @endif
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Batas Pembayaran:</strong>
                                            <span class="badge bg-danger text-white">{{ \Carbon\Carbon::parse($tripayData['expired_time'])->format('d M Y H:i') }}</span>
                                        </li>
                                        @endif
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Jumlah:</strong>
                                            <span class="badge bg-primary text-white"> Rp {{ number_format($tripayData['amount'], 0, ',', '.') }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Customer Information -->
                                <div class="col-12 col-md-6">
                                    <h5 class="text-muted">Informasi Pelanggan</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Nama:</strong>
                                            <span class="badge bg-primary text-white">{{ $payment->customer_name }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Email:</strong>
                                            <span class="badge bg-primary text-white">{{ $payment->customer_email }}</span>
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