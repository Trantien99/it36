<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin') }}">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-chart-pie"></i>
      </div>
      <div class="sidebar-brand-text mx-3">Admin BI</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('admin') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Bảng điều khiển BI</span>
      </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Vai trò chuyên sâu
    </div>

    <li class="nav-item {{ request()->routeIs('admin.bi-analyst') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.bi-analyst') }}">
        <i class="fas fa-fw fa-search-dollar"></i>
        <span>Phân tích kinh doanh</span>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.data-engineer') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.data-engineer') }}">
        <i class="fas fa-fw fa-database"></i>
        <span>Kiểm tra dữ liệu</span>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.data-scientist') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.data-scientist') }}">
        <i class="fas fa-fw fa-brain"></i>
        <span>Mô hình hóa tín hiệu</span>
      </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.technical-sales') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.technical-sales') }}">
        <i class="fas fa-fw fa-bullseye"></i>
        <span>Hỗ trợ bán hàng</span>
      </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Banner
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('file-manager') }}">
            <i class="fas fa-fw fa-photo-video"></i>
            <span>Quản lý tệp</span>
        </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-image"></i>
        <span>Banner</span>
      </a>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Tùy chọn banner:</h6>
          <a class="collapse-item" href="{{ route('banner.index') }}">Danh sách banner</a>
          <a class="collapse-item" href="{{ route('banner.create') }}">Thêm banner</a>
        </div>
      </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Cửa hàng
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#categoryCollapse" aria-expanded="true" aria-controls="categoryCollapse">
          <i class="fas fa-sitemap"></i>
          <span>Danh mục sản phẩm</span>
        </a>
        <div id="categoryCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tùy chọn danh mục:</h6>
            <a class="collapse-item" href="{{ route('category.index') }}">Danh mục sản phẩm</a>
            <a class="collapse-item" href="{{ route('category.create') }}">Thêm danh mục sản phẩm</a>
          </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#productCollapse" aria-expanded="true" aria-controls="productCollapse">
          <i class="fas fa-cubes"></i>
          <span>Sản phẩm</span>
        </a>
        <div id="productCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tùy chọn sản phẩm:</h6>
            <a class="collapse-item" href="{{ route('product.index') }}">Danh sách sản phẩm</a>
            <a class="collapse-item" href="{{ route('product.create') }}">Thêm sản phẩm</a>
          </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#brandCollapse" aria-expanded="true" aria-controls="brandCollapse">
          <i class="fas fa-copyright"></i>
          <span>Thương hiệu</span>
        </a>
        <div id="brandCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tùy chọn thương hiệu:</h6>
            <a class="collapse-item" href="{{ route('brand.index') }}">Danh sách thương hiệu</a>
            <a class="collapse-item" href="{{ route('brand.create') }}">Thêm thương hiệu</a>
          </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#shippingCollapse" aria-expanded="true" aria-controls="shippingCollapse">
          <i class="fas fa-truck"></i>
          <span>Phí giao hàng</span>
        </a>
        <div id="shippingCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tùy chọn vận chuyển:</h6>
            <a class="collapse-item" href="{{ route('shipping.index') }}">Danh sách phí giao hàng</a>
            <a class="collapse-item" href="{{ route('shipping.create') }}">Thêm phí giao hàng</a>
          </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('order.index') }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Đơn hàng</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('review.index') }}">
            <i class="fas fa-comments"></i>
            <span>Đánh giá</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
      Bài viết
    </div>

    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#postCollapse" aria-expanded="true" aria-controls="postCollapse">
        <i class="fas fa-fw fa-folder"></i>
        <span>Bài viết</span>
      </a>
      <div id="postCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Lựa chọn:</h6>
          <a class="collapse-item" href="{{ route('post.index') }}">Danh sách bài viết</a>
          <a class="collapse-item" href="{{ route('post.create') }}">Thêm bài viết</a>
        </div>
      </div>
    </li>

     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#postCategoryCollapse" aria-expanded="true" aria-controls="postCategoryCollapse">
          <i class="fas fa-sitemap"></i>
          <span>Danh mục bài viết</span>
        </a>
        <div id="postCategoryCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Lựa chọn:</h6>
            <a class="collapse-item" href="{{ route('post-category.index') }}">Danh mục bài viết</a>
            <a class="collapse-item" href="{{ route('post-category.create') }}">Thêm danh mục bài viết</a>
          </div>
        </div>
      </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tagCollapse" aria-expanded="true" aria-controls="tagCollapse">
            <i class="fas fa-tags"></i>
            <span>Thẻ</span>
        </a>
        <div id="tagCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Lựa chọn:</h6>
            <a class="collapse-item" href="{{ route('post-tag.index') }}">Danh sách thẻ</a>
            <a class="collapse-item" href="{{ route('post-tag.create') }}">Thêm thẻ</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('comment.index') }}">
            <i class="fas fa-comment-dots"></i>
            <span>Bình luận</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="sidebar-heading">
        Cài đặt chung
    </div>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('coupon.index') }}">
          <i class="fas fa-percent"></i>
          <span>Mã giảm giá</span>
      </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-users"></i>
            <span>Người dùng</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('settings') }}">
            <i class="fas fa-cog"></i>
            <span>Cài đặt</span>
        </a>
    </li>

    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
