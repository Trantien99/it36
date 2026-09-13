@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Cài đặt cửa hàng')

@push('styles')
  <link rel="stylesheet" href="{{ asset('backend/summernote/summernote.min.css') }}">
  <style>
    :root{--set-ink:#17324d;--set-sky:#2563eb;--set-mint:#059669;--set-amber:#d97706;--set-border:#e2e8f0;--set-soft:#f8fbff;--set-shadow:0 18px 40px rgba(23,50,77,.08)}
    .setting-page{padding-bottom:2rem}.setting-hero,.setting-card,.setting-panel{border:0;border-radius:1.25rem;box-shadow:var(--set-shadow)}.setting-hero{background:linear-gradient(135deg,#17324d 0%,#255f73 55%,#0f9d94 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.setting-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.setting-copy,.setting-focus{position:relative;z-index:1}.setting-kicker,.setting-card-label,.setting-focus-label,.setting-panel-label,.setting-preview-label{font-size:.78rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase}.setting-kicker{margin-bottom:.8rem}.setting-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:42rem}.setting-hero p{color:rgba(255,255,255,.84);max-width:42rem;margin-bottom:0}.setting-actions-top,.setting-actions-bottom,.setting-meta,.setting-status{display:flex;flex-wrap:wrap;gap:.75rem}.setting-actions-top{margin-top:1.25rem}.setting-actions-top .btn,.setting-actions-bottom .btn,.setting-chip,.setting-check{border-radius:999px;font-weight:700}.setting-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.setting-focus-label{color:rgba(255,255,255,.72)}.setting-focus-title{font-size:1.3rem;font-weight:800;margin:.35rem 0}.setting-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.setting-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.setting-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.setting-focus-box span{display:block;font-size:.74rem;color:rgba(255,255,255,.72);text-transform:uppercase}.setting-focus-box strong{display:block;font-size:1.02rem;margin-top:.35rem}.setting-card{color:#fff;height:100%;overflow:hidden;position:relative}.setting-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.setting-card .card-body{position:relative;z-index:1}.setting-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.setting-card-mint{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.setting-card-amber{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.setting-card-label{margin-bottom:.7rem}.setting-card-value{font-size:1.78rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.setting-card-text{font-size:.88rem;opacity:.88;margin-bottom:.8rem}.setting-chip{background:rgba(255,255,255,.14);display:inline-flex;padding:.35rem .7rem}.setting-panel{background:#fff;overflow:hidden}.setting-panel-header{border-bottom:1px solid var(--set-border);padding:1.35rem 1.45rem 1rem}.setting-panel-body{padding:1.35rem 1.45rem 1.45rem}.setting-panel-label,.setting-preview-label{color:#64748b;margin-bottom:.35rem}.setting-panel-title{font-size:1.08rem;font-weight:800;color:var(--set-ink)}.setting-panel-sub,.setting-help,.setting-preview-sub,.setting-preview-path,.setting-status span{font-size:.85rem;color:#64748b;line-height:1.6}.setting-meta{justify-content:flex-end;margin-top:1rem}.setting-check{display:inline-flex;align-items:center;gap:.35rem;background:var(--set-soft);border:1px solid var(--set-border);color:var(--set-ink);font-size:.8rem;padding:.45rem .8rem}.setting-form-group+.setting-form-group{margin-top:1.25rem}.setting-label{display:inline-block;font-size:.92rem;font-weight:700;color:var(--set-ink);margin-bottom:.55rem}.setting-input,.setting-panel .note-editor.note-frame{border-color:var(--set-border);border-radius:.95rem;box-shadow:none}.setting-input{min-height:3.1rem;padding-left:1rem;padding-right:1rem}.setting-input:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.setting-panel .note-toolbar{background:#f8fbff;border-bottom:1px solid var(--set-border)}.setting-file{display:flex;overflow:hidden;border:1px solid var(--set-border);border-radius:1rem;background:#fff}.setting-file .btn{border:0;border-radius:0;font-weight:700;min-width:8.8rem}.setting-file input{border:0;border-left:1px solid var(--set-border);border-radius:0;box-shadow:none;min-height:3.2rem}.setting-file input:focus{box-shadow:none}.setting-preview{border:1px solid var(--set-border);border-radius:1rem;overflow:hidden;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.setting-preview-media{height:12rem;display:flex;align-items:center;justify-content:center;padding:1rem;background:linear-gradient(135deg,rgba(37,99,235,.08) 0%,rgba(15,118,110,.1) 100%)}.setting-preview-media img{display:none;max-width:100%;max-height:100%;object-fit:cover;border-radius:.85rem;box-shadow:0 14px 28px rgba(23,50,77,.12)}.setting-preview-empty{display:flex;flex-direction:column;gap:.65rem;align-items:center;color:#64748b;text-align:center}.setting-preview-empty i{width:3.1rem;height:3.1rem;border-radius:999px;background:rgba(37,99,235,.12);display:inline-flex;align-items:center;justify-content:center;color:var(--set-sky)}.setting-preview-body{padding:1rem 1.05rem 1.05rem}.setting-status{display:grid;gap:.8rem;padding:0;margin:0}.setting-status li{list-style:none;display:flex;gap:.85rem;align-items:flex-start;border:1px solid var(--set-border);border-radius:1rem;padding:.95rem 1rem}.setting-status i.badge-icon{width:2.65rem;height:2.65rem;border-radius:.9rem;display:inline-flex;align-items:center;justify-content:center}.setting-status i.is-ready{background:rgba(5,150,105,.12);color:var(--set-mint)}.setting-status i.is-pending{background:rgba(217,119,6,.12);color:var(--set-amber)}.setting-status strong{display:block;font-size:.95rem;color:var(--set-ink);margin-bottom:.25rem}.setting-hidden-holder{display:none}@media (max-width:1199.98px){.setting-meta{justify-content:flex-start}}@media (max-width:991.98px){.setting-hero h1{font-size:1.65rem}}@media (max-width:767.98px){.setting-focus-grid{grid-template-columns:1fr}.setting-file{flex-direction:column}.setting-file input{border-left:0;border-top:1px solid var(--set-border)}}
  </style>
@endpush

@section('main-content')
  @php
    $shortDescription = old('short_des', $data->short_des ?? '');
    $description = old('description', $data->description ?? '');
    $logo = old('logo', $data->logo ?? '');
    $photo = old('photo', $data->photo ?? '');
    $address = old('address', $data->address ?? '');
    $email = old('email', $data->email ?? '');
    $phone = old('phone', $data->phone ?? '');
    $shortDescriptionPlain = trim(strip_tags((string) $shortDescription));
    $descriptionPlain = trim(strip_tags((string) $description));
    $completedFields = collect([$shortDescriptionPlain, $descriptionPlain, $logo, $photo, $address, $email, $phone])->filter(fn ($value) => filled($value))->count();
    $brandingReady = collect([$logo, $photo])->filter(fn ($value) => filled($value))->count();
    $contactReady = collect([$address, $email, $phone])->filter(fn ($value) => filled($value))->count();
    $contentReady = collect([$shortDescriptionPlain, $descriptionPlain])->filter(fn ($value) => filled($value))->count();
    $logoFileName = $logo ? basename(parse_url($logo, PHP_URL_PATH) ?: $logo) : null;
    $photoFileName = $photo ? basename(parse_url($photo, PHP_URL_PATH) ?: $photo) : null;
  @endphp

  <div class="container-fluid setting-page">
    @include('backend.layouts.notification')

    <div class="setting-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="setting-copy">
            <div class="setting-kicker">Cài đặt cửa hàng</div>
            <h1>Chỉnh thông tin thương hiệu, liên hệ và hình ảnh nhận diện trong một màn hình rõ ràng hơn.</h1>
            <p>Trang này giúp admin cập nhật các thông tin xuất hiện xuyên suốt website như logo, mô tả, địa chỉ, email và số điện thoại, đồng thời kiểm tra nhanh mức độ hoàn thiện của bộ nhận diện cửa hàng.</p>
            <div class="setting-actions-top">
              <a href="{{ route('file-manager') }}" class="btn btn-light btn-sm"><i class="fas fa-photo-video mr-1"></i> Mở kho tệp</a>
              <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-light btn-sm"><i class="fas fa-external-link-alt mr-1"></i> Xem trang chủ</a>
              <button type="button" id="scrollToSettingsForm" class="btn btn-outline-light btn-sm"><i class="fas fa-pen mr-1"></i> Đi tới biểu mẫu</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="setting-focus">
            <div class="setting-focus-label">Tổng quan hiện tại</div>
            <div class="setting-focus-title">{{ $completedFields }}/7 trường cốt lõi đã hoàn thiện</div>
            <div class="setting-focus-sub">
              @if ($data && $data->updated_at)
                Cập nhật gần nhất {{ $data->updated_at->diffForHumans() }}. Bạn có thể tinh chỉnh toàn bộ thông tin nhận diện trong một lần lưu.
              @else
                Hãy hoàn thiện các trường còn thiếu để website hiển thị thông tin thương hiệu và liên hệ đầy đủ hơn.
              @endif
            </div>
            <div class="setting-focus-grid">
              <div class="setting-focus-box"><span>Nhận diện</span><strong>{{ $brandingReady }}/2 ảnh</strong></div>
              <div class="setting-focus-box"><span>Liên hệ</span><strong>{{ $contactReady }}/3 mục</strong></div>
              <div class="setting-focus-box"><span>Nội dung</span><strong>{{ $contentReady }}/2 phần</strong></div>
              <div class="setting-focus-box"><span>Email</span><strong>{{ $email ?: 'Chưa có' }}</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card setting-card setting-card-primary"><div class="card-body"><div class="setting-card-label">Độ hoàn thiện</div><div class="setting-card-value">{{ number_format($completedFields, 0, ',', '.') }}/7</div><div class="setting-card-text">Tổng số trường quan trọng đã có dữ liệu.</div><span class="setting-chip">{{ number_format(7 - $completedFields, 0, ',', '.') }} mục còn thiếu</span></div></div>
      </div>
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card setting-card setting-card-mint"><div class="card-body"><div class="setting-card-label">Thông tin liên hệ</div><div class="setting-card-value">{{ number_format($contactReady, 0, ',', '.') }}/3</div><div class="setting-card-text">Địa chỉ, email và hotline để khách hàng dễ kết nối.</div><span class="setting-chip">{{ $phone ?: 'Chưa có hotline' }}</span></div></div>
      </div>
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card setting-card setting-card-amber"><div class="card-body"><div class="setting-card-label">Nội dung giới thiệu</div><div class="setting-card-value">{{ number_format($contentReady, 0, ',', '.') }}/2</div><div class="setting-card-text">Mô tả ngắn và mô tả đầy đủ cho thương hiệu.</div><span class="setting-chip">{{ $brandingReady === 2 ? 'Ảnh đã sẵn sàng' : 'Cần bổ sung hình ảnh' }}</span></div></div>
      </div>
    </div>

    <form method="post" action="{{ route('settings.update') }}" id="settingsForm">
      @csrf
      <div class="row">
        <div class="col-xl-8 mb-4">
          <div class="setting-panel mb-4">
            <div class="setting-panel-header">
              <div class="row align-items-lg-center">
                <div class="col-lg-8">
                  <div class="setting-panel-label">Nội dung thương hiệu</div>
                  <div class="setting-panel-title">Mô tả cửa hàng</div>
                  <p class="setting-panel-sub">Chuẩn hóa phần giới thiệu thương hiệu bằng một đoạn tóm tắt ngắn và phần nội dung trình bày chi tiết hơn.</p>
                </div>
                <div class="col-lg-4">
                  <div class="setting-meta">
                    <span class="setting-check"><i class="fas fa-align-left"></i> Tóm tắt: {{ $shortDescriptionPlain ? 'Đã có' : 'Đang thiếu' }}</span>
                    <span class="setting-check"><i class="fas fa-file-alt"></i> Mô tả dài: {{ $descriptionPlain ? 'Đã có' : 'Đang thiếu' }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="setting-panel-body">
              <div class="setting-form-group">
                <label for="settingShortDescription" class="setting-label">Mô tả vắn tắt <span class="text-danger">*</span></label>
                <textarea class="form-control setting-input" id="settingShortDescription" name="short_des">{{ $shortDescription }}</textarea>
                <div class="setting-help">Nên tập trung vào điểm mạnh nổi bật của cửa hàng hoặc ngành hàng chính.</div>
                @error('short_des')<span class="text-danger d-block mt-2">{{ $message }}</span>@enderror
              </div>
              <div class="setting-form-group">
                <label for="settingDescription" class="setting-label">Mô tả đầy đủ <span class="text-danger">*</span></label>
                <textarea class="form-control setting-input" id="settingDescription" name="description">{{ $description }}</textarea>
                <div class="setting-help">Bạn có thể mô tả thương hiệu, cam kết dịch vụ hoặc thế mạnh sản phẩm tại đây.</div>
                @error('description')<span class="text-danger d-block mt-2">{{ $message }}</span>@enderror
              </div>
            </div>
          </div>

          <div class="setting-panel">
            <div class="setting-panel-header">
              <div class="row align-items-lg-center">
                <div class="col-lg-8">
                  <div class="setting-panel-label">Liên hệ công khai</div>
                  <div class="setting-panel-title">Địa chỉ và kênh hỗ trợ</div>
                  <p class="setting-panel-sub">Những thông tin này thường xuất hiện ở footer, trang liên hệ và các điểm chạm hỗ trợ khách hàng.</p>
                </div>
                <div class="col-lg-4">
                  <div class="setting-meta">
                    <span class="setting-check"><i class="fas fa-map-marker-alt"></i> Địa chỉ</span>
                    <span class="setting-check"><i class="fas fa-envelope"></i> Email</span>
                    <span class="setting-check"><i class="fas fa-phone-alt"></i> Hotline</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="setting-panel-body">
              <div class="setting-form-group">
                <label for="settingAddress" class="setting-label">Địa chỉ <span class="text-danger">*</span></label>
                <input type="text" class="form-control setting-input" id="settingAddress" name="address" value="{{ $address }}" required>
                <div class="setting-help">Nên dùng địa chỉ đầy đủ để đồng bộ với trang liên hệ và footer website.</div>
                @error('address')<span class="text-danger d-block mt-2">{{ $message }}</span>@enderror
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="setting-form-group">
                    <label for="settingEmail" class="setting-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control setting-input" id="settingEmail" name="email" value="{{ $email }}" required>
                    <div class="setting-help">Email công khai để khách liên hệ hoặc nhận hỗ trợ.</div>
                    @error('email')<span class="text-danger d-block mt-2">{{ $message }}</span>@enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="setting-form-group">
                    <label for="settingPhone" class="setting-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control setting-input" id="settingPhone" name="phone" value="{{ $phone }}" required>
                    <div class="setting-help">Ưu tiên số hotline dễ nhớ để xử lý đơn hàng và hỗ trợ nhanh.</div>
                    @error('phone')<span class="text-danger d-block mt-2">{{ $message }}</span>@enderror
                  </div>
                </div>
              </div>
              <div class="setting-actions-bottom">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save mr-1"></i> Lưu cài đặt</button>
                <a href="{{ route('file-manager') }}" class="btn btn-outline-primary"><i class="fas fa-photo-video mr-1"></i> Mở kho tệp</a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary"><i class="fas fa-external-link-alt mr-1"></i> Xem website</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 mb-4">
          <div class="setting-panel mb-4">
            <div class="setting-panel-header">
              <div class="setting-panel-label">Nhận diện hình ảnh</div>
              <div class="setting-panel-title">Logo và ảnh đại diện</div>
              <p class="setting-panel-sub">Chọn ảnh từ thư viện media để đồng bộ hình ảnh thương hiệu trên toàn website.</p>
            </div>
            <div class="setting-panel-body">
              <div class="setting-preview mb-3">
                <div class="setting-preview-media">
                  <img id="logoPreviewImage" src="{{ $logo }}" alt="Logo cửa hàng">
                  <div class="setting-preview-empty" id="logoPreviewEmpty"><i class="fas fa-image"></i><span>Chưa chọn logo</span></div>
                </div>
                <div class="setting-preview-body">
                  <div class="setting-preview-label">Logo chính</div>
                  <div class="setting-panel-title mb-2">Ảnh nhận diện thương hiệu</div>
                  <p class="setting-preview-sub mb-0">Phù hợp cho header, footer hoặc các vị trí cần nhận diện nhanh.</p>
                  <p class="setting-preview-path mb-0" id="logoPreviewPath">{{ $logoFileName ?: 'Chưa có tệp nào được chọn.' }}</p>
                </div>
              </div>
              <div class="setting-form-group mt-0">
                <label for="thumbnailLogo" class="setting-label">Logo <span class="text-danger">*</span></label>
                <div class="setting-file">
                  <a id="lfmLogo" data-input="thumbnailLogo" data-preview="holderLogo" class="btn btn-primary"><i class="fas fa-folder-open mr-2"></i> Chọn ảnh</a>
                  <input id="thumbnailLogo" class="form-control" type="text" name="logo" value="{{ $logo }}">
                </div>
                <div id="holderLogo" class="setting-hidden-holder"></div>
                <div class="setting-help">Ưu tiên logo rõ nét, nền trong hoặc nền gọn gàng để hiển thị đẹp hơn.</div>
                @error('logo')<span class="text-danger d-block mt-2">{{ $message }}</span>@enderror
              </div>

              <div class="setting-preview mt-4 mb-3">
                <div class="setting-preview-media">
                  <img id="photoPreviewImage" src="{{ $photo }}" alt="Ảnh đại diện cửa hàng">
                  <div class="setting-preview-empty" id="photoPreviewEmpty"><i class="fas fa-camera-retro"></i><span>Chưa chọn ảnh đại diện</span></div>
                </div>
                <div class="setting-preview-body">
                  <div class="setting-preview-label">Ảnh đại diện shop</div>
                  <div class="setting-panel-title mb-2">Hình ảnh dùng cho khu vực giới thiệu</div>
                  <p class="setting-preview-sub mb-0">Có thể là ảnh cửa hàng, concept hoặc hình ảnh chủ đạo cho thương hiệu.</p>
                  <p class="setting-preview-path mb-0" id="photoPreviewPath">{{ $photoFileName ?: 'Chưa có tệp nào được chọn.' }}</p>
                </div>
              </div>
              <div class="setting-form-group mt-0">
                <label for="thumbnailPhoto" class="setting-label">Ảnh đại diện shop <span class="text-danger">*</span></label>
                <div class="setting-file">
                  <a id="lfmPhoto" data-input="thumbnailPhoto" data-preview="holderPhoto" class="btn btn-primary"><i class="fas fa-folder-open mr-2"></i> Chọn ảnh</a>
                  <input id="thumbnailPhoto" class="form-control" type="text" name="photo" value="{{ $photo }}">
                </div>
                <div id="holderPhoto" class="setting-hidden-holder"></div>
                <div class="setting-help">Nên dùng ảnh ngang chất lượng tốt để khu vực giới thiệu trông chuyên nghiệp hơn.</div>
                @error('photo')<span class="text-danger d-block mt-2">{{ $message }}</span>@enderror
              </div>
            </div>
          </div>

          <div class="setting-panel">
            <div class="setting-panel-header">
              <div class="setting-panel-label">Kiểm tra nhanh</div>
              <div class="setting-panel-title">Checklist hiển thị</div>
              <p class="setting-panel-sub">Nhìn nhanh các phần đã sẵn sàng và những mục nên bổ sung trước khi lưu.</p>
            </div>
            <div class="setting-panel-body">
              <ul class="setting-status">
                <li><i class="badge-icon {{ $logo ? 'is-ready fa-check' : 'is-pending fa-exclamation' }} fas"></i><div><strong>Logo thương hiệu</strong><span>{{ $logo ? 'Đã có logo để dùng ở các khu vực nhận diện chính.' : 'Nên thêm logo để header và footer hiển thị đồng bộ hơn.' }}</span></div></li>
                <li><i class="badge-icon {{ $photo ? 'is-ready fa-check' : 'is-pending fa-exclamation' }} fas"></i><div><strong>Ảnh đại diện shop</strong><span>{{ $photo ? 'Ảnh đại diện đã sẵn sàng cho các block giới thiệu.' : 'Bổ sung ảnh đại diện để phần giới thiệu thương hiệu sinh động hơn.' }}</span></div></li>
                <li><i class="badge-icon {{ $contentReady === 2 ? 'is-ready fa-check' : 'is-pending fa-exclamation' }} fas"></i><div><strong>Nội dung giới thiệu</strong><span>{{ $contentReady === 2 ? 'Cả mô tả ngắn và mô tả đầy đủ đều đã có nội dung.' : 'Nên hoàn thiện phần mô tả để website truyền tải thương hiệu rõ ràng hơn.' }}</span></div></li>
                <li><i class="badge-icon {{ $contactReady === 3 ? 'is-ready fa-check' : 'is-pending fa-exclamation' }} fas"></i><div><strong>Kênh liên hệ công khai</strong><span>{{ $contactReady === 3 ? 'Địa chỉ, email và số điện thoại đã sẵn sàng hiển thị.' : 'Hãy kiểm tra đủ địa chỉ, email và số điện thoại để tránh thiếu thông tin liên hệ.' }}</span></div></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
  <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
  <script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>
  <script>
    $(function () {
      $('#lfmLogo').filemanager('image');
      $('#lfmPhoto').filemanager('image');
      $('#settingShortDescription').summernote({ placeholder: 'Viết đoạn mô tả ngắn cho cửa hàng...', tabsize: 2, height: 140 });
      $('#settingDescription').summernote({ placeholder: 'Viết mô tả chi tiết về thương hiệu, sản phẩm hoặc dịch vụ...', tabsize: 2, height: 220 });

      function syncPreview(inputSelector, imageSelector, emptySelector, pathSelector, emptyText) {
        const value = ($(inputSelector).val() || '').trim();
        const $image = $(imageSelector);
        const $empty = $(emptySelector);
        const $path = $(pathSelector);
        if (value) {
          const fileName = value.split('/').pop() || value;
          $image.attr('src', value).css('display', 'block');
          $empty.hide();
          $path.text(fileName);
        } else {
          $image.attr('src', '').hide();
          $empty.css('display', 'flex');
          $path.text(emptyText);
        }
      }

      function refreshPreviews() {
        syncPreview('#thumbnailLogo', '#logoPreviewImage', '#logoPreviewEmpty', '#logoPreviewPath', 'Chưa có tệp nào được chọn.');
        syncPreview('#thumbnailPhoto', '#photoPreviewImage', '#photoPreviewEmpty', '#photoPreviewPath', 'Chưa có tệp nào được chọn.');
      }

      $('#thumbnailLogo, #thumbnailPhoto').on('change input', refreshPreviews);
      refreshPreviews();
      $('#scrollToSettingsForm').on('click', function () {
        const form = document.getElementById('settingsForm');
        if (form) form.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  </script>
@endpush
