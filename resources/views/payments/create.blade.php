@extends('layouts.app')

@section('title', 'Buat Pembayaran')

@section('content')
<div class="container">
    <h2 class="mb-4">Buat Pembayaran Baru</h2>

    @if(empty($channels))
    <div class="alert alert-warning">Tidak ada channel pembayaran yang tersedia.</div>
    @else
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">Jumlah Pembayaran (Rp)</label>
            <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required min="10000">
        </div>
        <div class="mb-3">
            <label for="customer_name" class="form-label">Nama Pelanggan</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
        </div>
        <div class="mb-3">
            <label for="customer_email" class="form-label">Email Pelanggan</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
        </div>
        <div class="mb-3">
            <label for="payment_method" class="form-label">Metode Pembayaran</label>
            <select class="form-select" id="payment_method" name="payment_method" required>
                <option value="">Pilih metode pembayaran</option>
                @foreach ($channels as $channel)
                <option value="{{ $channel['code'] ?? '' }}">
                    {{ $channel['name'] ?? 'Unknown' }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Buat Pembayaran</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const paymentMethodSelect = document.getElementById('payment_method');

        paymentMethodSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const minAmount = selectedOption.getAttribute('data-min-amount');
            if (minAmount) {
                amountInput.min = minAmount;
                amountInput.placeholder = `Minimal Rp ${parseInt(minAmount).toLocaleString('id-ID')}`;
            } else {
                amountInput.min = 10000;
                amountInput.placeholder = '';
            }
        });
    });
</script>
@endsection