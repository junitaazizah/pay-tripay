@extends('layouts.app')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Pembayaran</h2>
        <div>
            <a href="{{ route('payments.reset') }}" class="btn btn-warning">
                <i class="fas fa-redo"></i> Reset Semua
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                <i class="fas fa-plus-circle"></i> Buat Pembayaran Baru
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Referensi</th>
                            <th>Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td><span class="badge bg-primary">{{ $payment->merchant_ref }}</span></td>
                            <td>{{ $payment->customer_name }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->method }}</td>
                            <td>
                                @php
                                $statusClass = $payment->status === 'Paid' ? 'success' : ($payment->status === 'Pending' ? 'warning' : 'secondary');
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($payment->status === 'Paid')
                                    <a href="{{ route('payments.detail', $payment) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('payments.bayar', $payment) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> Detail 2
                                    </a>
                                    @elseif($payment->status === 'Pending')
                                    <a href="{{ route('payments.bayar', $payment) }}" class="btn btn-sm btn-outline-danger">
                                        Bayar
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Tidak ada data pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $payments->links() }}
    </div>
</div>
@include('payments.modal.add-modal')
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endpush