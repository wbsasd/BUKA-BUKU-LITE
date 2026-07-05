@props(['id' => 'modal', 'title' => ''])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      @if($title)
      <div class="modal-header">
        <h5 class="modal-title">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      @endif
      <div class="modal-body">
        {{ $slot }}
      </div>
      @if(isset($footer))
      <div class="modal-footer">
        {{ $footer }}
      </div>
      @endif
    </div>
  </div>
</div>
