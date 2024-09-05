@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container">
    <h2 class="mb-4">Detail Pembayaran</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nomor Referensi: <span class="badge bg-success">{{ $payment->merchant_ref }}</span></h5>
            <p class="card-text">Status:
                <span class="badge bg-{{ $payment->status === 'Paid' ? 'success' : ($payment->status === 'Pending' ? 'danger' : 'secondary') }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </p>
            <p class="card-text">Metode Pembayaran: {{ $tripayData['payment_name'] }}</p>
            <p class="card-text">Jumlah: Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            <p class="card-text">Nama Pelanggan: {{ $payment->customer_name }}</p>
            <p class="card-text">Email Pelanggan: {{ $payment->customer_email }}</p>

            @if($payment->status == 'Pending')
            <h6 class="mt-4">Instruksi Pembayaran:</h6>
            <p>Silakan transfer ke:</p>
            <ul>
                <li>Bank: {{ $tripayData['payment_name'] }}</li>
                <li>Nomor Rekening: {{ $tripayData['pay_code'] }}</li>
                <li>Nomor Rekening: {{ $tripayData['amount'] }}</li>
            </ul>
            <p>Batas Waktu Pembayaran: <span class="badge bg-danger">{{ \Carbon\Carbon::parse($tripayData['expired_time'])->format('d M Y H:i') }}</span></p>
            @endif
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('payments.index') }}" class="btn btn-primary">Kembali</a>
    </div>
</div>
@endsection