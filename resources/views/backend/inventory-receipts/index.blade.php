@extends('backend.layouts.master')

@section('title', 'Quản lý phiếu nhập kho')

@section('main-content')
<div class="container-fluid">
    @include('backend.layouts.notification')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Phiếu nhập kho</h1>
            <p class="text-muted mb-0">Theo dõi hàng nhập và biến động tồn kho.</p>
        </div>
        <a href="{{ route('inventory-receipts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Tạo phiếu nhập
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Mã phiếu</th>
                            <th>Nhà cung cấp</th>
                            <th>Mã tham chiếu</th>
                            <th>Số dòng</th>
                            <th>Tổng số lượng</th>
                            <th>Người nhận</th>
                            <th>Thời gian</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($receipts as $receipt)
                            <tr>
                                <td><strong>{{ $receipt->receipt_number }}</strong></td>
                                <td>{{ $receipt->supplier_name ?: '—' }}</td>
                                <td>{{ $receipt->reference_number ?: '—' }}</td>
                                <td>{{ $receipt->items_count }}</td>
                                <td>{{ number_format($receipt->total_quantity) }}</td>
                                <td>{{ optional($receipt->receivedBy)->name ?: 'Tài khoản đã xóa' }}</td>
                                <td>{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('inventory-receipts.show', $receipt->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Xem phiếu nhập">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">Chưa có phiếu nhập kho.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $receipts->links() }}</div>
        </div>
    </div>
</div>
@endsection