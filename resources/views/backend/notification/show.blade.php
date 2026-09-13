<div id="notifications">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <span class="badge badge-danger badge-counter">
            @if(count(auth()->user()->unreadNotifications) > 5)
                <span data-count="5" class="count">5+</span>
            @else
                <span class="count" data-count="{{ count(auth()->user()->unreadNotifications) }}">{{ count(auth()->user()->unreadNotifications) }}</span>
            @endif
        </span>
      </a>
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
          Thông báo
        </h6>
        @foreach(auth()->user()->unreadNotifications as $notification)
            <a class="dropdown-item d-flex align-items-center" target="_blank" href="{{ route('admin.notification', $notification->id) }}">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                    <i class="fas {{ $notification->data['fas'] }} text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</div>
                    <span class="@if($notification->unread()) font-weight-bold @else small text-gray-500 @endif">{{ $notification->data['title'] }}</span>
                </div>
            </a>
            @if($loop->index + 1 == 5)
                @break
            @endif
        @endforeach

        <a class="dropdown-item text-center small text-gray-500" href="{{ route('all.notification') }}">Xem tất cả thông báo</a>
      </div>
</div>
