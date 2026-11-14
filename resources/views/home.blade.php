 @extends('layouts.app')
@section('content')
@php($s = $siteSettings ?? [])
<section class="hero-wrap container-fluid px-lg-4 px-0">
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="6500">
<div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Слайд 1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Слайд 2"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Слайд 3"></button></div>
<div class="carousel-inner">
<div class="carousel-item active">
    <div class="hero-slide hero-slide-one">
        <div class="container hero-content">
            <h1>{{ $s['hero_title'] ?? 'Там, где начинается спокойствие' }}</h1>
            <p>{{ $s['hero_subtitle'] ?? 'Уютные домики, лесной воздух и незабываемые моменты вдали от городской суеты.' }}</p>
            <div class="d-flex gap-3 flex-wrap"><a class="btn btn-light btn-lg btn-hero" href="{{ route('booking.create') }}">Выбрать отдых<i class="bi bi-arrow-up-right ms-2"></i></a>
                <a class="btn btn-outline-light btn-lg btn-hero" href="{{ route('about') }}">Узнать о нас </a></div></div></div></div>
<div class="carousel-item">
    <div class="hero-slide hero-slide-two">
        <div class="container hero-content">
            <h2>Тёплая баня. Свежий воздух. И никаких планов.</h2>
            <p>Подарите себе вечер, который захочется повторить.</p>
            <a class="btn btn-light btn-lg btn-hero" href="{{ route('services.index') }}">Наши услуги <i class="bi bi-arrow-up-right ms-2"></i></a></div></div></div>
<div class="carousel-item">
    <div class="hero-slide hero-slide-three">
        <div class="container hero-content">
            <h2>Самые тёплые воспоминания рождаются здесь</h2>
            <p>Отдых для семьи, встречи с друзьями и маленькие поводы для счастья.</p>
            <a class="btn btn-light btn-lg btn-hero" href="{{ route('booking.create') }}">Забронировать <i class="bi bi-arrow-up-right ms-2"></i></a></div></div></div>
</div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Назад</span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Вперёд</span></button></div>
</section>
<section class="benefits-strip">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 d-flex align-items-center gap-3">
                <span class="benefit-icon">
                    <i class="bi bi-tree"></i></span>
                <div><strong>Природа в каждом моменте</strong>
                    <small>Тишина, лес и чистый воздух</small></div></div>
            <div class="col-md-4 d-flex align-items-center gap-3">
                <span class="benefit-icon"><i class="bi bi-shield-check"></i></span>
                <div><strong>Комфорт без компромиссов</strong>
                    <small>Продумано для вашего отдыха</small></div></div>
            <div class="col-md-4 d-flex align-items-center gap-3"><span class="benefit-icon">
                    <i class="bi bi-calendar2-check"></i></span>
                <div><strong>Простое бронирование</strong><small>Выберите удобное время онлайн</small></div></div></div></div>
</section>
<section class="section-padding"><div class="container"><div class="row align-items-center g-5"><div class="col-lg-6"><div class="about-photo-wrap"><img class="about-main-img" src="https://images.unsplash.com/photo-1449158743715-0a90ebb6d2d8?w=1100&q=85" onerror="this.onerror=null;this.src='{{ asset('images/forest.svg') }}'" alt="Деревянный домик среди деревьев" loading="lazy"><div class="about-note"><i class="bi bi-heart-fill"></i><span>Создано с заботой<br>о вашем отдыхе</span></div></div></div><div class="col-lg-6"><div class="eyebrow">Добро пожаловать</div><h2 class="section-title">Место, в которое<br><em>хочется возвращаться</em></h2><p class="section-lead">{{ $s['about_text'] ?? 'Лесная гавань — это уютное пространство среди природы, где можно замедлиться, провести время с близкими и наполниться энергией.' }}</p><p class="text-muted">Мы верим, что лучшие моменты рождаются из простых вещей: разговоров у огня, утреннего лесного воздуха и чувства, что спешить больше некуда.</p><div class="d-flex flex-wrap gap-4 my-4"><div class="feature-small"><i class="bi bi-tree-fill"></i> Рядом с природой</div><div class="feature-small"><i class="bi bi-cup-hot"></i> Атмосфера уюта</div></div><a href="{{ route('about') }}" class="btn btn-outline-primary btn-lg px-4">Больше о нас <i class="bi bi-arrow-right ms-2"></i></a></div></div></div></section>
<section class="section-padding bg-warm"><div class="container"><div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5"><div><div class="eyebrow">Выберите свой формат</div><h2 class="section-title mb-0">Отдых <em>по настроению</em></h2></div><a class="text-link" href="{{ route('services.index') }}">Смотреть все услуги <i class="bi bi-arrow-right ms-2"></i></a></div><div class="row g-4">@forelse($services as $service)<div class="col-md-6 col-lg-4">@include('partials.service-card')</div>@empty<div class="col-12"><p>Скоро здесь появятся наши услуги.</p></div>@endforelse</div></div></section>
<section class="section-padding" id="quick-booking"><div class="container"><div class="row g-5 align-items-center"><div class="col-lg-4"><div class="eyebrow">Бронирование онлайн</div><h2 class="section-title">Запланируйте <em>свой отдых</em></h2><p class="section-lead">Уже знаете, когда хотели бы приехать? Выберите дату, услугу и приятные дополнения. На следующем шаге проверим детали.</p><div class="feature-small"><i class="bi bi-check2-circle"></i> Несколько минут на оформление</div></div><div class="col-lg-8"><div class="white-panel shadow-sm"><form action="{{ route('booking.create') }}" method="GET" class="row g-3"><div class="col-md-6"><label class="form-label" for="quickService">Услуга</label><select id="quickService" name="service" class="form-select" required><option value="">Выберите услугу</option>@foreach($allServices as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach</select></div><div class="col-md-6"><label class="form-label" for="quickDate">Дата</label><input id="quickDate" type="date" name="date" min="{{ now()->format('Y-m-d') }}" class="form-control" required></div><div class="col-md-4"><label class="form-label" for="quickTime">Время</label><select id="quickTime" name="time" class="form-select" required>@for($hour=9;$hour<=22;$hour++)<option value="{{ sprintf('%02d:00',$hour) }}">{{ sprintf('%02d:00',$hour) }}</option>@endfor</select></div><div class="col-md-4"><label class="form-label" for="quickHours">Часов</label><select id="quickHours" name="hours" class="form-select">@for($h=1;$h<=12;$h++)<option value="{{ $h }}" @selected($h===2)>{{ $h }}</option>@endfor</select></div><div class="col-md-4"><label class="form-label" for="quickGuests">Гостей</label><input id="quickGuests" type="number" name="guest_count" class="form-control" value="2" min="1" max="100" required></div>@if($extras->isNotEmpty())<div class="col-12"><div class="form-label mb-2">Дополнительно (по желанию)</div><div class="d-flex flex-wrap gap-3">@foreach($extras as $extra)<label class="small"><input type="checkbox" class="form-check-input me-1" name="extra_ids[]" value="{{ $extra->id }}">{{ $extra->name }}</label>@endforeach</div></div>@endif<div class="col-12"><button class="btn btn-primary btn-lg">Продолжить бронирование <i class="bi bi-arrow-right ms-2"></i></button></div></form></div></div></div></div></section>
<section class="section-padding"><div class="container"><div class="booking-banner row align-items-center g-4"><div class="col-lg-7"><div class="eyebrow text-light">Отдых ближе, чем кажется</div><h2>Уже знаете, когда<br>хотите приехать?</h2><p>Выберите подходящий день, время и любимые развлечения. А всё остальное мы подготовим.</p><a class="btn btn-light btn-lg px-4" href="{{ route('booking.create') }}">Забронировать отдых <i class="bi bi-arrow-up-right ms-2"></i></a></div><div class="col-lg-5 text-lg-end"><div class="booking-banner-icon"><i class="bi bi-calendar2-heart"></i></div></div></div></div></section>
<section class="section-padding bg-warm"><div class="container"><div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5"><div><div class="eyebrow">Впечатления гостей</div><h2 class="section-title mb-0">О нас <em>говорят</em></h2></div><a class="text-link" href="{{ route('reviews.index') }}">Все отзывы <i class="bi bi-arrow-right ms-2"></i></a></div><div class="row g-4">@forelse($reviews as $review)<div class="col-lg-4 col-md-6"><div class="review-card h-100"><div class="stars mb-3">@for($i=0;$i<$review->rating;$i++)<i class="bi bi-star-fill"></i>@endfor</div><p>«{{ Str::limit($review->body, 230) }}»</p><div class="review-author"><span class="avatar-circle">{{ mb_substr($review->user?->name ?? 'Г', 0, 1) }}</span><div><strong>{{ $review->user?->name ?? 'Гость' }}</strong><small>{{ $review->created_at->format('d.m.Y') }}</small></div></div></div></div>@empty<div class="col-12"><p class="text-muted">Первые отзывы скоро появятся здесь.</p></div>@endforelse</div></div></section>
<section class="section-padding pb-4"><div class="container"><div class="row align-items-center g-4"><div class="col-lg-7"><div class="eyebrow">Связаться с нами</div><h2 class="section-title">Остались <em>вопросы?</em></h2><p class="section-lead">С радостью расскажем подробнее, поможем выбрать подходящий формат отдыха и ответим на ваши вопросы.</p><a class="btn btn-primary btn-lg" href="tel:{{ preg_replace('/[^\d+]/','', $s['phone'] ?? '+79990000000') }}"><i class="bi bi-telephone-fill me-2"></i>Позвонить</a><a href="{{ route('contacts') }}" class="btn btn-link text-dark ms-2">Другие способы связи <i class="bi bi-arrow-right"></i></a></div><div class="col-lg-5"><div class="contact-mini"><i class="bi bi-geo-alt"></i><div><small>Наш адрес</small><strong>{{ $s['address'] ?? 'Адрес уточняйте у администратора' }}</strong></div></div><div class="contact-mini"><i class="bi bi-clock"></i><div><small>Мы на связи</small><strong>Ежедневно с 09:00 до 23:00</strong></div></div></div></div></div></section>
@endsection
