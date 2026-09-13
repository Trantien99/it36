      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Bản quyền &copy; <a href="https://www.facebook.com/Thinh.Dzep.Zai/" target="_blank">Admin</a> {{ date('Y') }}</span>
          </div>
        </div>
      </footer>

    </div>

  </div>

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Bạn muốn đăng xuất?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Đóng">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">Chọn "Đăng xuất" bên dưới nếu bạn muốn kết thúc phiên làm việc hiện tại.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Hủy</button>
          <a
            class="btn btn-primary"
            href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('footer-logout-form').submit();"
          >
            Đăng xuất
          </a>
          <form id="footer-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('backend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <script src="{{ asset('backend/js/sb-admin-2.min.js') }}"></script>
  <script src="{{ asset('backend/vendor/chart.js/Chart.min.js') }}"></script>

  @stack('scripts')

  <script>
    setTimeout(function () {
      $('.alert').slideUp();
    }, 4000);
  </script>
