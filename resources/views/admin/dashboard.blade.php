@extends('layouts.admin')

@section('content')
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Hello, {{ auth()->user()->name }}</h4>
      <p class="text-muted">Selamat datang di panel administrasi.</p>
    </div>
  </div>
@endsection


