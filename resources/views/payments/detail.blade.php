@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container">
    <h2 class="mb-4">Detail Pembayaran</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nomor Referensi: <span class="badge bg-success">{{ $payment->merchant_ref }}</span></h5>
            <p class="card-text">Status: {{ $payment->status }}</p>
            <p class="card-text">Metode Pembayaran: {{ $tripayData['payment_name'] }}</p>
            <p class="card-text">Jumlah: Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            <p class="card-text">Nama Pelanggan: {{ $payment->customer_name }}</p>
            <p class="card-text">Email Pelanggan: {{ $payment->customer_email }}</p>

            @if($payment->status == 'pending')
            <h6 class="mt-4">Instruksi Pembayaran:</h6>
            <p>Silakan transfer ke:</p>
            <ul>
                <li>Bank: {{ $tripayData['pay_code'] }}</li>
                <li>Nomor Rekening: {{ $tripayData['pay_code'] }}</li>
                <li>Atas Nama: {{ $tripayData['merchant_name'] }}</li>
            </ul>
            <p>Batas Waktu Pembayaran: {{ \Carbon\Carbon::parse($tripayData['expired_time'])->format('d M Y H:i') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection