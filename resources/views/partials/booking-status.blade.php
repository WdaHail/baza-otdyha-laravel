@switch($status)
@case('confirmed')<span class="badge rounded-pill text-bg-success">Подтверждено</span>@break
@case('cancelled')<span class="badge rounded-pill text-bg-secondary">Отменено</span>@break
@default <span class="badge rounded-pill text-bg-warning">Ожидает</span>
@endswitch
