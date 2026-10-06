@extends('backend.layouts.master')

@section('title', 'Tạo phiếu nhập kho')

@section('main-content')
<div class="container-fluid">
    @include('backend.layouts.notification')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Tạo phiếu nhập kho</h1>
            <p class="text-muted mb-0">Tồn kho sẽ được cộng ngay khi phiếu được ghi nhận.</p>
        </div>
        <a href="{{ route('inventory-receipts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Danh sách phiếu
        </a>
    </div>

    @if ($products->isEmpty())
        <div class="alert alert-warning">
            Chưa có sản phẩm nào trong hệ thống. Hãy <a href="{{ route('product.create') }}">thêm sản phẩm</a> trước khi lập phiếu nhập kho.
        </div>
    @endif

    <form action="{{ route('inventory-receipts.store') }}" method="POST">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header"><strong>Thông tin phiếu</strong></div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="supplier_name">Nhà cung cấp</label>
                        <input id="supplier_name" name="supplier_name" class="form-control" maxlength="255" value="{{ old('supplier_name') }}">
                        @error('supplier_name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="reference_number">Mã hóa đơn / chứng từ</label>
                        <input id="reference_number" name="reference_number" class="form-control" maxlength="100" value="{{ old('reference_number') }}">
                        @error('reference_number')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label for="notes">Ghi chú</label>
                    <textarea id="notes" name="notes" class="form-control" rows="2" maxlength="5000">{{ old('notes') }}</textarea>
                    @error('notes')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Sản phẩm nhập</strong>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addReceiptItem"><i class="fas fa-plus mr-1"></i> Thêm dòng</button>
            </div>
            <div class="card-body">
                <div id="receiptItems">
                    @foreach (old('items', [['product_id' => '', 'quantity' => 1, 'unit_cost' => '']]) as $index => $item)
                        <div class="form-row receipt-item align-items-end mb-3">
                            <div class="form-group col-md-5 mb-md-0">
                                <label>Sản phẩm</label>
                                <select name="items[{{ $index }}][product_id]" class="form-control" required>
                                    <option value="">Chọn sản phẩm</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ (string) ($item['product_id'] ?? '') === (string) $product->id ? 'selected' : '' }}>{{ $product->title }} (tồn: {{ $product->stock }})</option>
                                    @endforeach
                                </select>
                                @error('items.' . $index . '.product_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group col-md-2 mb-md-0">
                                <label>Số lượng</label>
                                <input type="number" min="1" max="1000000" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $item['quantity'] ?? 1 }}" required>
                                @error('items.' . $index . '.quantity')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group col-md-3 mb-md-0">
                                <label>Giá nhập / sản phẩm</label>
                                <input type="number" min="0" step="0.01" name="items[{{ $index }}][unit_cost]" class="form-control" value="{{ $item['unit_cost'] ?? '' }}">
                                @error('items.' . $index . '.unit_cost')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group col-md-2 mb-md-0">
                                <button type="button" class="btn btn-outline-danger removeReceiptItem" aria-label="Xóa dòng sản phẩm"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('items')<div class="text-danger mb-3">{{ $message }}</div>@enderror
                <button type="submit" class="btn btn-primary"><i class="fas fa-boxes mr-1"></i> Ghi nhận nhập kho</button>
                <a href="{{ route('inventory-receipts.index') }}" class="btn btn-link">Hủy</a>
            </div>
        </div>
    </form>
</div>

<template id="receiptItemTemplate">
    <div class="form-row receipt-item align-items-end mb-3">
        <div class="form-group col-md-5 mb-md-0">
            <label>Sản phẩm</label>
            <select name="items[__INDEX__][product_id]" class="form-control" required>
                <option value="">Chọn sản phẩm</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->title }} (tồn: {{ $product->stock }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2 mb-md-0">
            <label>Số lượng</label>
            <input type="number" min="1" max="1000000" name="items[__INDEX__][quantity]" class="form-control" value="1" required>
        </div>
        <div class="form-group col-md-3 mb-md-0">
            <label>Giá nhập / sản phẩm</label>
            <input type="number" min="0" step="0.01" name="items[__INDEX__][unit_cost]" class="form-control">
        </div>
        <div class="form-group col-md-2 mb-md-0">
            <button type="button" class="btn btn-outline-danger removeReceiptItem" aria-label="Xóa dòng sản phẩm"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</template>

<script>
    (function () {
        var items = document.getElementById('receiptItems');
        var template = document.getElementById('receiptItemTemplate');
        var nextIndex = 0;
        items.querySelectorAll('[name^="items["]').forEach(function (input) {
            var match = input.name.match(/^items\[(\d+)\]/);
            if (match) {
                nextIndex = Math.max(nextIndex, parseInt(match[1], 10) + 1);
            }
        });

        function refreshRemoveButtons() {
            var rows = items.querySelectorAll('.receipt-item');
            rows.forEach(function (row) {
                row.querySelector('.removeReceiptItem').disabled = rows.length === 1;
            });
        }

        document.getElementById('addReceiptItem').addEventListener('click', function () {
            var html = template.innerHTML.replace(/__INDEX__/g, nextIndex++);
            items.insertAdjacentHTML('beforeend', html);
            refreshRemoveButtons();
        });

        items.addEventListener('click', function (event) {
            var removeButton = event.target.closest('.removeReceiptItem');
            if (removeButton && items.querySelectorAll('.receipt-item').length > 1) {
                removeButton.closest('.receipt-item').remove();
                refreshRemoveButtons();
            }
        });

        refreshRemoveButtons();
    }());
</script>
@endsection