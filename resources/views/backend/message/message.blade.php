<div id="messages">
    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-envelope fa-fw"></i>
        @php $messageCount = count(Helper::messageList()); @endphp
        @if($messageCount > 5)
            <span class="badge badge-danger badge-counter count" data-count="5">5+</span>
        @else
            <span class="badge badge-danger badge-counter count" data-count="{{ $messageCount }}">{{ $messageCount }}</span>
        @endif
    </a>

    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
        <h6 class="dropdown-header">
            Tin nhắn
        </h6>
        <div id="message-items">
            @foreach(Helper::messageList() as $message)
                <a class="dropdown-item d-flex align-items-center" href="{{ route('message.show', $message->id) }}">
                    <div class="dropdown-list-image mr-3">
                        @if($message->photo)
                            <img class="rounded-circle" src="{{ $message->photo }}" alt="Ảnh đại diện">
                        @else
                            <img class="rounded-circle" src="{{ asset('backend/img/avatar.png') }}" alt="Ảnh mặc định">
                        @endif
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">{{ $message->subject }}</div>
                        <div class="small text-gray-500">{{ $message->name }} · {{ $message->created_at->diffForHumans() }}</div>
                    </div>
                </a>
                @if($loop->index + 1 == 5)
                    @break
                @endif
            @endforeach
        </div>
        <a class="dropdown-item text-center small text-gray-500" href="{{ route('message.index') }}">Xem thêm tin nhắn</a>
    </div>
</div>

@push('scripts')
<script>
  $(function () {
    if (typeof window.Echo === 'undefined') {
      return;
    }

    window.Echo.channel('message').listen('MessageSent', function (e) {
      const messageContainer = $('#message-items');
      const messageCounterArea = $('#messages .count');
      const currentCount = parseInt(messageCounterArea.attr('data-count') || '0', 10);
      const nextCount = currentCount + 1;
      const messageLength = $('#message-items > .dropdown-item').length;
      const defaultAvatar = '{{ asset('backend/img/avatar.png') }}';

      messageCounterArea.attr('data-count', nextCount);

      const data = `
        <a class="dropdown-item d-flex align-items-center message-item" href="${e.message.url}">
          <div class="dropdown-list-image mr-3">
            <img class="rounded-circle" src="${e.message.photo || defaultAvatar}" alt="${e.message.name}">
          </div>
          <div class="font-weight-bold">
            <div class="text-truncate">${e.message.subject}</div>
            <div class="small text-gray-500">${e.message.name} · ${e.message.date}</div>
          </div>
        </a>
      `;

      messageContainer.prepend(data);

      if (nextCount <= 5) {
        messageCounterArea.text(nextCount);
      } else {
        messageCounterArea.text('5+');
      }

      if (messageLength >= 5) {
        messageContainer.find('.message-item').last().remove();
      }
    });
  });
</script>
@endpush
