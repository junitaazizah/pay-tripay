@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container">
    <h2 class="mb-4">Detail Pembayaran</h2>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nomor Referensi: {{ $payment->merchant_ref }}</h5>
            <p class="card-text"><strong>Status:</strong>
                <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'danger' : 'secondary') }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </p>
            <p class="card-text"><strong>Metode Pembayaran:</strong> {{ $tripayData['payment_name'] ?? $payment->method }}</p>
            <p class="card-text"><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            <p class="card-text"><strong>Nama Pelanggan:</strong> {{ $payment->customer_name }}</p>
            <p class="card-text"><strong>Email Pelanggan:</strong> {{ $payment->customer_email }}</p>

            @if($payment->status === 'pending' && isset($tripayData['pay_code']))
            <hr>
            <h6 class="mt-4">Instruksi Pembayaran:</h6>
            <p>Silakan transfer ke:</p>
            <ul>
                <li><strong>Bank/Channel:</strong> {{ $tripayData['payment_name'] }}</li>
                <li><strong>Kode Pembayaran:</strong> {{ $tripayData['pay_code'] }}</li>
                @if(isset($tripayData['pay_url']))
                <li><strong>URL Pembayaran:</strong> <a href="{{ $tripayData['pay_url'] }}" target="_blank">Klik di sini</a></li>
                @endif
            </ul>
            @if(isset($tripayData['instructions']))
            <h6>Langkah-langkah Pembayaran:</h6>
            <ol>
                @foreach($tripayData['instructions'] as $instruction)
                <li>{!! $instruction !!}</li>
                @endforeach
            </ol>
            @endif
            <p><strong>Batas Waktu Pembayaran:</strong> {{ \Carbon\Carbon::parse($tripayData['expired_time'])->format('d M Y H:i') }}</p>
            @endif

            @if($payment->status === 'pending')
            <a href="{{ route('payments.check-status', $payment) }}" class="btn btn-info mt-3">Periksa Status Pembayaran</a>
            @endif

            @if($payment->status === 'paid')
            <hr>
            <h6 class="mt-4">Informasi Pembayaran:</h6>
            <p><strong>Dibayar pada:</strong> {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') : 'N/A' }}</p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('payments.create') }}" class="btn btn-primary">Buat Pembayaran Baru</a>
    </div>
</div>
@endsection