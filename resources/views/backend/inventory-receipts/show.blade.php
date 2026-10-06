@extends('backend.layouts.master')

@section('title', 'Chi tiết phiếu nhập kho')

@section('main-content')
<div class="container-fluid">
    @include('backend.layouts.notification')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Phiếu nhập {{ $receipt->receipt_number }}</h1>
            <p class="text-muted mb-0">Ghi nhận lúc {{ $receipt->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('inventory-receipts.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Danh sách phiếu</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3"><strong>Nhà cung cấp:</strong><br>{{ $receipt->supplier_name ?: '—' }}</div>
                <div class="col-md-4 mb-3"><strong>Mã hóa đơn / chứng từ:</strong><br>{{ $receipt->reference_number ?: '—' }}</div>
                <div class="col-md-4 mb-3"><strong>Người ghi nhận:</strong><br>{{ optional($receipt->receivedBy)->name ?: 'Tài khoản đã xóa' }}</div>
                @if ($receipt->notes)
                    <div class="col-12"><strong>Ghi chú:</strong><br>{{ $receipt->notes }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header"><strong>Chi tiết sản phẩm</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead><tr><th>Sản phẩm</th><th class="text-right">Số lượng</th><th class="text-right">Giá nhập</th><th class="text-right">Thành tiền</th></tr></thead>
                    <tbody>
                        @foreach ($receipt->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}@if (!$item->product)<small class="text-muted d-block">Sản phẩm đã được xóa khỏi danh mục.</small>@endif</td>
                                <td class="text-right">{{ number_format($item->quantity) }}</td>
                                <td class="text-right">{{ number_format((float) $item->unit_cost, 0, ',', '.') }}đ</td>
                                <td class="text-right">{{ number_format((float) $item->line_total, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot><tr><th colspan="3" class="text-right">Tổng cộng</th><th class="text-right">{{ number_format((float) $receipt->items->sum('line_total'), 0, ',', '.') }}đ</th></tr></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection