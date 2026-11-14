@extends('layouts.app')
@section('title', 'Панель управления — Лесная гавань')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div><div class="eyebrow">Управление сайтом</div>
            <h1 class="page-title mb-0">Панель администратора</h1></div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">На сайт <i class="bi bi-box-arrow-up-right"></i></a></div>
    <div class="row g-4"><aside class="col-lg-3"><div class="admin-sidebar card border-0 shadow-sm p-3"><a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"> Обзор</a><a class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}"> Бронирования</a><a class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}"> Отзывы</a>@if(auth()->user()->isAdmin())<a class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}"> Услуги</a><a class="{{ request()->routeIs('admin.extras.*') ? 'active' : '' }}" href="{{ route('admin.extras.index') }}"> Доп. услуги</a><a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"> Пользователи</a><a class="{{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}" href="{{ route('admin.feedback.index') }}"> Настройки</a>@endif</div></aside><div class="col-lg-9"><div class="admin-content">@yield('admin-content')</div></div></div>
</div>
@endsection
