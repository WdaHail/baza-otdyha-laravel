<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Лесная гавань — отдых среди сосен, уютные домики, баня и впечатления на природе.">
    <title>@yield('title', 'Лесная гавань — отдых ближе к природе')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @stack('styles')
</head>
<body>
@php($s = $siteSettings ?? [])
<div class="top-strip d-none d-lg-block"><div class="container d-flex justify-content-between align-items-center"><span><i class="bi bi-geo-alt me-1"></i> {{ $s['address'] ?? 'За городом, среди сосен' }}</span><span><i class="bi bi-clock me-1"></i> Принимаем гостей ежедневно, 09:00–23:00 <span class="mx-3 opacity-50">|</span><a href="tel:{{ preg_replace('/[^\d+]/','', $s['phone'] ?? '+79990000000') }}">{{ $s['phone'] ?? '+7 (999) 000-00-00' }}</a></span></div></div>
<nav class="navbar navbar-expand-lg site-nav sticky-top">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}"><span class="brand-icon"><i class="bi bi-tree-fill"></i></span><span class="brand-text">Лесная <strong>гавань</strong><small>Пространство для отдыха</small></span></a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Открыть меню"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-lg-2 align-items-lg-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">О базе</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Услуги</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('booking.*') ? 'active' : '' }}" href="{{ route('booking.create') }}">Бронирование</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}" href="{{ route('reviews.index') }}">Отзывы</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contacts') ? 'active' : '' }}" href="{{ route('contacts') }}">Контакты</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                @auth
                    <a class="btn btn-link text-dark text-decoration-none fw-semibold px-2" href="{{ route('account.index') }}"><i class="bi bi-person-circle me-1"></i> Кабинет</a>
                    @if(auth()->user()->isStaff()) <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.dashboard') }}">Админ</a> @endif
                @else
                    <a class="btn btn-link text-dark text-decoration-none fw-semibold px-2" href="{{ route('login') }}"><i class="bi bi-person-circle me-1"></i> Войти</a>
                @endauth
                <a class="btn btn-primary btn-book" href="{{ route('booking.create') }}">Забронировать <i class="bi bi-arrow-up-right ms-1"></i></a>
            </div>
        </div>
    </div>
</nav>
<main>
    <div class="container alert-area">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show mt-3" role="alert"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
        @if($errors->any())<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert"><div class="fw-bold mb-1">Пожалуйста, проверьте данные:</div><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    </div>
    @yield('content')
</main>
<footer class="site-footer mt-5">
    <div class="container py-5">
        <div class="row gy-4">
            <div class="col-lg-4"><a class="footer-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('home') }}"><span class="brand-icon light"><i class="bi bi-tree-fill"></i></span><span>Лесная гавань</span></a><p class="mt-3 footer-muted">Место, где шум города остаётся позади, а впереди — природа, тишина и время с близкими.</p><div class="d-flex gap-3 fs-5"><a class="footer-social" href="{{ $s['vk_url'] ?? 'https://vk.com' }}" target="_blank" rel="noopener" aria-label="ВКонтакте"><i class="bi bi-chat-heart"></i></a><a class="footer-social" href="{{ $s['telegram_url'] ?? 'https://t.me' }}" target="_blank" rel="noopener" aria-label="Telegram"><i class="bi bi-telegram"></i></a></div></div>
            <div class="col-6 col-lg-2"><h6>Навигация</h6><a href="{{ route('about') }}">О базе</a><a href="{{ route('services.index') }}">Услуги</a><a href="{{ route('reviews.index') }}">Отзывы</a><a href="{{ route('contacts') }}">Контакты</a></div>
            <div class="col-6 col-lg-2"><h6>Гостям</h6><a href="{{ route('booking.create') }}">Бронирование</a><a href="{{ route('account.index') }}">Личный кабинет</a><a href="{{ route('about') }}#rules">Правила отдыха</a><a href="{{ route('contacts') }}">Как добраться</a></div>
            <div class="col-lg-4"><h6>Остаёмся на связи</h6><a class="footer-phone" href="tel:{{ preg_replace('/[^\d+]/','', $s['phone'] ?? '+79990000000') }}">{{ $s['phone'] ?? '+7 (999) 000-00-00' }}</a><span class="footer-muted d-block mt-2"><i class="bi bi-geo-alt me-2"></i>{{ $s['address'] ?? 'Адрес уточняйте у администратора' }}</span></div>
        </div>
        <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2 mt-5 pt-4"><span>© {{ date('Y') }} Лесная гавань. Все права защищены.</span></div>
    </div>
</footer>
<a class="call-fab" href="tel:{{ preg_replace('/[^\d+]/','', $s['phone'] ?? '+79990000000') }}" aria-label="Позвонить администратору"><i class="bi bi-telephone-fill"></i><span>Позвонить</span></a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/site.js') }}" defer></script>
@stack('scripts')
</body>
</html>
