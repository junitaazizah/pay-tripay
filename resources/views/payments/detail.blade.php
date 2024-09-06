@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Pembayaran</h4>
                    <span class="badge bg-info">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, H:i') }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12 col-md-6 mb-3">
                            <h5 class="text-muted">Informasi Transaksi</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>No. Referensi:</strong>
                                    <span class="badge bg-success">{{ $payment->merchant_ref }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Status:</strong>
                                    <span class="badge bg-{{ $payment->status === 'Paid' ? 'success' : ($payment->status === 'Pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Jumlah:</strong>
                                    Rp {{ number_format($tripayData['amount'], 0, ',', '.') }}
                                </li>
                            </ul>
                        </div>
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
                <div class="card-footer text-center">
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection