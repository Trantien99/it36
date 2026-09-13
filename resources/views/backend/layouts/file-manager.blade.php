@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Trung tâm quản lý tệp')

@push('styles')
  <style>
    :root {
      --fm-ink: #17324d;
      --fm-sky: #2563eb;
      --fm-cyan: #0891b2;
      --fm-mint: #059669;
      --fm-amber: #d97706;
      --fm-rose: #dc2626;
      --fm-border: #e2e8f0;
      --fm-soft: #f8fbff;
      --fm-shadow: 0 18px 40px rgba(23, 50, 77, 0.08);
    }

    .fm-page {
      padding-bottom: 2rem;
    }

    .fm-hero,
    .fm-card,
    .fm-panel,
    .fm-tip {
      border: 0;
      border-radius: 1.25rem;
      box-shadow: var(--fm-shadow);
    }

    .fm-hero {
      background: linear-gradient(135deg, #17324d 0%, #255f73 55%, #0f9d94 100%);
      color: #fff;
      overflow: hidden;
      padding: 1.75rem;
      position: relative;
    }

    .fm-hero::after,
    .fm-hero::before {
      border-radius: 999px;
      content: '';
      position: absolute;
      background: rgba(255, 255, 255, 0.08);
    }

    .fm-hero::after {
      height: 14rem;
      right: -4rem;
      top: -4rem;
      width: 14rem;
    }

    .fm-hero::before {
      bottom: -3rem;
      height: 9rem;
      left: 38%;
      width: 9rem;
    }

    .fm-copy,
    .fm-focus {
      position: relative;
      z-index: 1;
    }

    .fm-kicker,
    .fm-card-label,
    .fm-focus-label,
    .fm-panel-label,
    .fm-tip-label {
      font-size: 0.78rem;
      font-weight: 800;
      letter-spacing: 0.16em;
      text-transform: uppercase;
    }

    .fm-kicker {
      margin-bottom: 0.8rem;
    }

    .fm-hero h1 {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1.12;
      margin-bottom: 0.9rem;
      max-width: 42rem;
    }

    .fm-hero p {
      color: rgba(255, 255, 255, 0.84);
      margin-bottom: 0;
      max-width: 42rem;
    }

    .fm-actions-top {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      margin-top: 1.25rem;
    }

    .fm-actions-top .btn,
    .fm-helper-link {
      border-radius: 999px;
      font-weight: 700;
    }

    .fm-focus {
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.14);
      border-radius: 1rem;
      height: 100%;
      padding: 1.15rem;
    }

    .fm-focus-label {
      color: rgba(255, 255, 255, 0.72);
    }

    .fm-focus-title {
      font-size: 1.3rem;
      font-weight: 800;
      margin: 0.35rem 0;
    }

    .fm-focus-sub {
      color: rgba(255, 255, 255, 0.84);
      font-size: 0.92rem;
    }

    .fm-focus-grid {
      display: grid;
      gap: 0.75rem;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      margin-top: 1rem;
    }

    .fm-focus-box {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 0.95rem;
      padding: 0.85rem 0.95rem;
    }

    .fm-focus-box span {
      color: rgba(255, 255, 255, 0.72);
      display: block;
      font-size: 0.74rem;
      text-transform: uppercase;
    }

    .fm-focus-box strong {
      display: block;
      font-size: 1.02rem;
      margin-top: 0.35rem;
    }

    .fm-card {
      color: #fff;
      height: 100%;
      overflow: hidden;
      position: relative;
    }

    .fm-card::after {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 999px;
      content: '';
      height: 6.5rem;
      position: absolute;
      right: -1.5rem;
      top: -1.5rem;
      width: 6.5rem;
    }

    .fm-card .card-body {
      position: relative;
      z-index: 1;
    }

    .fm-card-primary {
      background: linear-gradient(140deg, #1d4ed8 0%, #2563eb 100%);
    }

    .fm-card-cyan {
      background: linear-gradient(140deg, #0f766e 0%, #0891b2 100%);
    }

    .fm-card-mint {
      background: linear-gradient(140deg, #047857 0%, #059669 100%);
    }

    .fm-card-label {
      margin-bottom: 0.7rem;
    }

    .fm-card-value {
      font-size: 1.78rem;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 0.55rem;
    }

    .fm-card-text {
      font-size: 0.88rem;
      margin-bottom: 0.8rem;
      opacity: 0.88;
    }

    .fm-card-chip,
    .fm-badge,
    .fm-step-chip {
      align-items: center;
      border-radius: 999px;
      display: inline-flex;
      font-weight: 700;
    }

    .fm-card-chip {
      background: rgba(255, 255, 255, 0.14);
      font-size: 0.78rem;
      padding: 0.35rem 0.7rem;
    }

    .fm-panel,
    .fm-tip {
      background: #fff;
      overflow: hidden;
    }

    .fm-panel-header,
    .fm-tip-header {
      border-bottom: 1px solid var(--fm-border);
      padding: 1.35rem 1.45rem 1rem;
    }

    .fm-panel-body,
    .fm-tip-body {
      padding: 1.35rem 1.45rem 1.45rem;
    }

    .fm-panel-label,
    .fm-tip-label {
      color: #64748b;
      margin-bottom: 0.35rem;
    }

    .fm-panel-title,
    .fm-tip-title {
      color: var(--fm-ink);
      font-size: 1.08rem;
      font-weight: 800;
      margin-bottom: 0.4rem;
    }

    .fm-panel-sub,
    .fm-tip-sub {
      color: #64748b;
      font-size: 0.9rem;
      line-height: 1.6;
      margin-bottom: 0;
    }

    .fm-panel-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 0.6rem;
      justify-content: flex-end;
      margin-top: 1rem;
    }

    .fm-badge {
      background: var(--fm-soft);
      border: 1px solid var(--fm-border);
      color: var(--fm-ink);
      font-size: 0.8rem;
      gap: 0.35rem;
      padding: 0.45rem 0.8rem;
    }

    .fm-frame-shell {
      background: linear-gradient(180deg, #f8fbff 0%, #eef6ff 100%);
      border: 1px solid var(--fm-border);
      border-radius: 1.15rem;
      overflow: hidden;
    }

    .fm-frame-topbar {
      align-items: center;
      background: #fff;
      border-bottom: 1px solid var(--fm-border);
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      justify-content: space-between;
      padding: 0.95rem 1rem;
    }

    .fm-frame-title {
      align-items: center;
      color: var(--fm-ink);
      display: inline-flex;
      font-size: 0.98rem;
      font-weight: 800;
      gap: 0.65rem;
    }

    .fm-dot-group {
      display: inline-flex;
      gap: 0.35rem;
    }

    .fm-dot {
      border-radius: 999px;
      display: inline-block;
      height: 0.72rem;
      width: 0.72rem;
    }

    .fm-dot-red {
      background: #fb7185;
    }

    .fm-dot-yellow {
      background: #fbbf24;
    }

    .fm-dot-green {
      background: #34d399;
    }

    .fm-frame-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.55rem;
    }

    .fm-helper-link {
      background: #fff;
      border: 1px solid var(--fm-border);
      color: #475569;
      display: inline-flex;
      gap: 0.45rem;
      padding: 0.58rem 0.85rem;
      text-decoration: none;
    }

    .fm-helper-link:hover,
    .fm-helper-link:focus {
      border-color: rgba(37, 99, 235, 0.3);
      color: var(--fm-sky);
      text-decoration: none;
    }

    .fm-frame {
      background: #fff;
      border: 0;
      display: block;
      height: 78vh;
      min-height: 46rem;
      width: 100%;
    }

    .fm-tip-list,
    .fm-mini-list {
      display: grid;
      gap: 0.75rem;
      margin: 0;
      padding: 0;
    }

    .fm-tip-item,
    .fm-mini-item {
      align-items: flex-start;
      border: 1px solid var(--fm-border);
      border-radius: 1rem;
      display: flex;
      gap: 0.85rem;
      list-style: none;
      padding: 0.95rem 1rem;
    }

    .fm-tip-icon,
    .fm-mini-icon {
      align-items: center;
      border-radius: 0.9rem;
      display: inline-flex;
      flex: 0 0 2.7rem;
      height: 2.7rem;
      justify-content: center;
    }

    .fm-tip-icon {
      background: rgba(37, 99, 235, 0.1);
      color: var(--fm-sky);
    }

    .fm-mini-icon {
      background: rgba(5, 150, 105, 0.1);
      color: var(--fm-mint);
    }

    .fm-tip-item strong,
    .fm-mini-item strong {
      color: var(--fm-ink);
      display: block;
      font-size: 0.95rem;
      margin-bottom: 0.25rem;
    }

    .fm-tip-item span,
    .fm-mini-item span {
      color: #64748b;
      display: block;
      font-size: 0.85rem;
      line-height: 1.55;
    }

    .fm-step-chip {
      background: rgba(37, 99, 235, 0.12);
      color: var(--fm-sky);
      font-size: 0.74rem;
      justify-content: center;
      min-width: 2.1rem;
      padding: 0.35rem 0.55rem;
    }

    @media (max-width: 1199.98px) {
      .fm-panel-meta {
        justify-content: flex-start;
      }
    }

    @media (max-width: 991.98px) {
      .fm-hero h1 {
        font-size: 1.65rem;
      }

      .fm-frame {
        height: 70vh;
        min-height: 38rem;
      }
    }

    @media (max-width: 767.98px) {
      .fm-focus-grid {
        grid-template-columns: 1fr;
      }

      .fm-frame {
        height: 65vh;
        min-height: 32rem;
      }

      .fm-frame-topbar {
        align-items: flex-start;
        flex-direction: column;
      }
    }
  </style>
@endpush

@section('main-content')
  <div class="container-fluid fm-page">
    @include('backend.layouts.notification')

    <div class="fm-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="fm-copy">
            <div class="fm-kicker">Trung tâm tài nguyên</div>
            <h1>Quản lý hình ảnh và tệp tin gọn gàng hơn để cập nhật sản phẩm, banner và nội dung nhanh hơn.</h1>
            <p>Không gian này gom toàn bộ thư viện media vào một chỗ, giúp admin tải lên, sắp xếp theo thư mục và lấy liên kết dùng lại cho logo, ảnh sản phẩm, banner hoặc trình soạn thảo bài viết.</p>
            <div class="fm-actions-top">
              <a href="/laravel-filemanager" target="_blank" rel="noopener" class="btn btn-light btn-sm"><i class="fas fa-external-link-alt mr-1"></i> Mở toàn màn hình</a>
              <button type="button" id="fmRefreshFrame" class="btn btn-outline-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới khung</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="fm-focus">
            <div class="fm-focus-label">Luồng làm việc gợi ý</div>
            <div class="fm-focus-title">Tạo thư mục trước, chọn tệp sau, chèn URL ngay vào form quản trị.</div>
            <div class="fm-focus-sub">Một thư viện được sắp xếp tốt sẽ giúp thao tác ở trang sản phẩm, bài viết và cài đặt nhanh hơn rất nhiều, nhất là khi cửa hàng có nhiều banner hoặc ảnh biến thể.</div>
            <div class="fm-focus-grid">
              <div class="fm-focus-box"><span>Bước 1</span><strong>Phân nhóm theo sản phẩm, banner, bài viết</strong></div>
              <div class="fm-focus-box"><span>Bước 2</span><strong>Tải ảnh chuẩn tên và đúng kích thước</strong></div>
              <div class="fm-focus-box"><span>Bước 3</span><strong>Sao chép đường dẫn hoặc chọn trực tiếp</strong></div>
              <div class="fm-focus-box"><span>Bước 4</span><strong>Quay lại form để lưu thay đổi</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card fm-card fm-card-primary">
          <div class="card-body">
            <div class="fm-card-label">Một thư viện cho mọi nơi</div>
            <div class="fm-card-value">Sản phẩm, banner, bài viết</div>
            <div class="fm-card-text">Dùng chung một nơi lưu trữ để giảm ảnh trùng lặp và giữ đường dẫn thống nhất trên toàn bộ khu vực quản trị.</div>
            <span class="fm-card-chip"><i class="fas fa-images mr-1"></i> Đồng bộ media toàn hệ thống</span>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card fm-card fm-card-cyan">
          <div class="card-body">
            <div class="fm-card-label">Tối ưu thao tác</div>
            <div class="fm-card-value">Tải lên, đổi tên, tạo thư mục</div>
            <div class="fm-card-text">Phù hợp khi cần chuẩn hóa kho ảnh cho từng chiến dịch, bộ sưu tập hoặc landing page theo mùa.</div>
            <span class="fm-card-chip"><i class="fas fa-folder-tree mr-1"></i> Sắp xếp gọn hơn trước</span>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card fm-card fm-card-mint">
          <div class="card-body">
            <div class="fm-card-label">Sẵn sàng chèn vào form</div>
            <div class="fm-card-value">Dùng tốt với trình chọn ảnh</div>
            <div class="fm-card-text">Khi form có nút chọn media, bạn chỉ cần mở thư viện, chọn đúng tệp và để hệ thống tự điền đường dẫn.</div>
            <span class="fm-card-chip"><i class="fas fa-link mr-1"></i> Tương thích với file manager</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-8 mb-4">
        <div class="fm-panel">
          <div class="fm-panel-header">
            <div class="row align-items-lg-center">
              <div class="col-lg-8">
                <div class="fm-panel-label">Không gian làm việc</div>
                <div class="fm-panel-title">Trình quản lý tệp</div>
                <p class="fm-panel-sub">Khung bên dưới hiển thị trực tiếp Laravel File Manager để bạn thao tác mà không cần rời khỏi dashboard quản trị. Nếu muốn tập trung hơn, có thể mở toàn màn hình bằng nút ở phía trên.</p>
              </div>
              <div class="col-lg-4">
                <div class="fm-panel-meta">
                  <span class="fm-badge"><i class="fas fa-photo-video"></i> Hỗ trợ hình ảnh và tệp tin</span>
                  <span class="fm-badge"><i class="fas fa-cloud-upload-alt"></i> Tải lên ngay trong khung</span>
                </div>
              </div>
            </div>
          </div>
          <div class="fm-panel-body">
            <div class="fm-frame-shell">
              <div class="fm-frame-topbar">
                <div class="fm-frame-title">
                  <span class="fm-dot-group">
                    <span class="fm-dot fm-dot-red"></span>
                    <span class="fm-dot fm-dot-yellow"></span>
                    <span class="fm-dot fm-dot-green"></span>
                  </span>
                  <span>Trình duyệt thư viện nội bộ</span>
                </div>
                <div class="fm-frame-actions">
                  <a href="/laravel-filemanager" target="_blank" rel="noopener" class="fm-helper-link"><i class="fas fa-expand-arrows-alt"></i> Toàn màn hình</a>
                  <button type="button" id="fmRefreshFrameSecondary" class="fm-helper-link"><i class="fas fa-redo"></i> Tải lại</button>
                </div>
              </div>
              <iframe id="fileManagerFrame" src="/laravel-filemanager" class="fm-frame" title="Trình quản lý tệp"></iframe>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 mb-4">
        <div class="fm-tip mb-4">
          <div class="fm-tip-header">
            <div class="fm-tip-label">Mẹo tổ chức</div>
            <div class="fm-tip-title">Sắp xếp thư viện dễ quản trị hơn</div>
            <p class="fm-tip-sub">Giữ cấu trúc thư mục rõ ràng để cả team dễ tìm lại media khi cần chỉnh sửa nhanh.</p>
          </div>
          <div class="fm-tip-body">
            <ul class="fm-tip-list">
              <li class="fm-tip-item">
                <div class="fm-tip-icon"><i class="fas fa-box-open"></i></div>
                <div>
                  <strong>Tách thư mục theo khu vực sử dụng</strong>
                  <span>Dùng các nhóm như `product`, `banner`, `post`, `brand` để tránh ảnh bị lẫn giữa các module.</span>
                </div>
              </li>
              <li class="fm-tip-item">
                <div class="fm-tip-icon"><i class="fas fa-spell-check"></i></div>
                <div>
                  <strong>Đặt tên file dễ hiểu</strong>
                  <span>Ưu tiên tên không dấu, có ngữ nghĩa rõ để sau này tìm kiếm theo từ khóa nhanh hơn.</span>
                </div>
              </li>
              <li class="fm-tip-item">
                <div class="fm-tip-icon"><i class="fas fa-compress-alt"></i></div>
                <div>
                  <strong>Tối ưu ảnh trước khi tải lên</strong>
                  <span>Giảm dung lượng ảnh banner và ảnh sản phẩm sẽ giúp trang tải nhanh hơn cho cả admin lẫn khách hàng.</span>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <div class="fm-tip">
          <div class="fm-tip-header">
            <div class="fm-tip-label">Dùng ở đâu</div>
            <div class="fm-tip-title">Những chỗ thường cần thư viện này</div>
            <p class="fm-tip-sub">Bạn có thể quay lại các màn hình bên dưới sau khi chọn hoặc tải media mới.</p>
          </div>
          <div class="fm-tip-body">
            <div class="fm-mini-list">
              <div class="fm-mini-item">
                <span class="fm-step-chip">01</span>
                <div>
                  <strong>Cài đặt cửa hàng</strong>
                  <span>Cập nhật logo, ảnh đại diện và hình ảnh nhận diện cho toàn bộ website.</span>
                </div>
              </div>
              <div class="fm-mini-item">
                <span class="fm-step-chip">02</span>
                <div>
                  <strong>Sản phẩm và thương hiệu</strong>
                  <span>Dùng ảnh chính xác cho thẻ sản phẩm, danh mục, brand và các banner khuyến mãi.</span>
                </div>
              </div>
              <div class="fm-mini-item">
                <span class="fm-step-chip">03</span>
                <div>
                  <strong>Bài viết và nội dung</strong>
                  <span>Chèn ảnh vào trình soạn thảo để bài blog hoặc landing page trông chuyên nghiệp hơn.</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    (function () {
      const frame = document.getElementById('fileManagerFrame');
      const refreshButtons = [document.getElementById('fmRefreshFrame'), document.getElementById('fmRefreshFrameSecondary')];

      refreshButtons.forEach(function (button) {
        if (!button) {
          return;
        }

        button.addEventListener('click', function () {
          if (frame) {
            frame.src = frame.src;
          }
        });
      });
    })();
  </script>
@endpush
