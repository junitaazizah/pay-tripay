@extends('layouts.app')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Pembayaran</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('payments.create') }}" class="btn btn-primary">Buat Pembayaran Baru</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
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
                        <td>{{ $payment->merchant_ref }}</td>
                        <td>{{ $payment->customer_name }}</td>
                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>{{ $payment->method }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info">Detail</a>
                            @if($payment->status === 'pending')
                            <a href="{{ route('payments.check-status', $payment) }}" class="btn btn-sm btn-warning">Cek Status</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection