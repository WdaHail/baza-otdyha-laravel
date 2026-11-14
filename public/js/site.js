'use strict';
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-booking-form]');
    if (!form) return;
    const service = document.getElementById('service');
    const hours = document.getElementById('hours');
    const guests = document.getElementById('guestCount');
    const money = n => new Intl.NumberFormat('ru-RU').format(n) + ' ₽';
    function recalculate() {
        const option = service.options[service.selectedIndex];
        const price = Number(option?.dataset.price || 0);
        const capacity = Number(option?.dataset.capacity || 100);
        const count = Number(hours.value || 1);
        const extraPrice = [...form.querySelectorAll('.extra-checkbox:checked')].reduce((sum, input) => sum + Number(input.dataset.price || 0), 0);
        document.getElementById('summaryService').textContent = price ? option.textContent.split(' · ')[0] : 'Не выбрана';
        document.getElementById('summaryHours').textContent = count + (count === 1 ? ' час' : count < 5 ? ' часа' : ' часов');
        document.getElementById('summaryExtras').textContent = money(extraPrice);
        document.getElementById('summaryTotal').textContent = money(price * count + extraPrice);
        guests.max = String(capacity);
        document.getElementById('capacityHint').textContent = price ? 'Не более ' + capacity + ' гостей' : 'Вместимость зависит от услуги';
    }
    for (const input of form.querySelectorAll('select, input')) input.addEventListener('change', recalculate);
    recalculate();
});
