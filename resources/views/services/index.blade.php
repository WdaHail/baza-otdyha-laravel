@extends('layouts.app')
@section('title', 'Услуги и цены — Лесная гавань')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">Каждому — своё настроение</span><h1 class="page-title">Наши <em>услуги</em></h1><p>Выбирайте отдых, который подходит именно вам, а мы позаботимся о деталях.</p></div></section>
<section class="section-padding bg-warm"><div class="container"><div class="row g-4">@forelse($services as $service)<div class="col-lg-4 col-md-6">@include('partials.service-card')</div>@empty<div class="col-12 text-center py-5">Сейчас нет доступных услуг.</div>@endforelse</div><div class="mt-5">{{ $services->links('pagination::bootstrap-5') }}</div></div></section>
@endsection
