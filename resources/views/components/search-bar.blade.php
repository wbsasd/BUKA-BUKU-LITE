@props(['placeholder' => 'Cari buku...','name' => 'q','value' => '', 'action' => null])

<form class="d-flex" role="search" method="GET" action="{{ $action ?? url()->current() }}">
  <div class="input-group">
    <input type="search" name="{{ $name }}" value="{{ $value }}" class="form-control" placeholder="{{ $placeholder }}" aria-label="Search">
    <button class="btn btn-outline-secondary" type="submit">
      <i class="bi bi-search"></i>
    </button>
  </div>
</form>
