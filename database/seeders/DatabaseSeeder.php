<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Extra;
use App\Models\Review;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'phone' => '+7 (999) 000-00-00',
            'email' => 'hello@example.test',
            'address' => 'Адрес и маршрут уточняйте у администратора',
            'map_url' => '',
            'vk_url' => 'https://vk.com',
            'telegram_url' => 'https://t.me',
            'hero_title' => 'Там, где начинается спокойствие',
            'hero_subtitle' => 'Уютные домики, лесной воздух и незабываемые моменты вдали от городской суеты.',
            'about_text' => 'Лесная гавань — место для тихих семейных выходных, душевных встреч и маленьких путешествий без суеты. Мы позаботились о комфорте, чтобы вы могли по-настоящему отдохнуть.',
            'about_history' => 'Всё началось с любви к природе и желания создать пространство, где каждый сможет отвлечься от повседневной спешки. Наша миссия — дарить гостям душевные впечатления, сохраняя красоту окружающего леса.',
            'about_rules' => "Уважайте соседей и соблюдайте тишину в установленные часы.\nБерегите природу, не оставляйте мусор.\nРазводить костры разрешается только в оборудованных местах.\nЕсли планы изменились, заранее свяжитесь с администратором для уточнения условий отмены.",
            'legal_name' => 'Демонстрационный проект — реквизиты уточняются',
            'developers' => 'Команда разработки',
        ] as $key => $value) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // ТОЛЬКО ДЛЯ ДЕМО: обязательно замените пароли и удалите демонстрационные учётные записи перед публикацией.
        User::firstOrCreate(['email' => 'admin@lesnaya.test'], [
            'name' => 'Администратор', 'role' => 'admin', 'password' => Hash::make(env('DEMO_ADMIN_PASSWORD', 'DemoAdmin!2026')),
        ]);
        User::firstOrCreate(['email' => 'manager@lesnaya.test'], [
            'name' => 'Менеджер', 'role' => 'manager', 'password' => Hash::make(env('DEMO_MANAGER_PASSWORD', 'DemoManager!2026')),
        ]);
        $guest = User::firstOrCreate(['email' => 'guest@lesnaya.test'], [
            'name' => 'Анна', 'role' => 'user', 'password' => Hash::make('DemoGuest!2026'),
        ]);

        $services = [
            ['name'=>'Лесной домик','slug'=>'lesnoy-domik','category'=>'Проживание','short_description'=>'Тёплые вечера и уютное утро в домике среди сосен.','description'=>'Комфортное пространство для уютных встреч и выходных на природе. Домик идеально подходит для семейного отдыха и душевных посиделок.','price_per_hour'=>1800,'capacity'=>6,'cover_url'=>'https://images.unsplash.com/photo-1449158743715-0a90ebb6d2d8?w=900&q=80','sort_order'=>1],
            ['name'=>'Баня на дровах','slug'=>'banya-na-drovah','category'=>'Релакс','short_description'=>'Настоящий пар, аромат дерева и отдых от повседневности.','description'=>'Баня на дровах — лучший способ расслабиться и почувствовать прилив сил. Отдыхайте с компанией близких, наслаждайтесь тишиной и свежим воздухом.','price_per_hour'=>2500,'capacity'=>8,'cover_url'=>'https://images.unsplash.com/photo-1510798831971-661eb04b3739?w=900&q=80','sort_order'=>2],
            ['name'=>'Беседка у леса','slug'=>'besedka-u-lesa','category'=>'Для компании','short_description'=>'Встречайтесь с друзьями на свежем воздухе в своём ритме.','description'=>'Крытая беседка для праздника, обеда с семьёй или неспешного вечера. Можно дополнить отдых питанием и арендой инвентаря.','price_per_hour'=>1200,'capacity'=>12,'cover_url'=>'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?w=900&q=80','sort_order'=>3],
            ['name'=>'Площадка для пикника','slug'=>'piknik','category'=>'Активный отдых','short_description'=>'Лесные прогулки, свежий воздух и вкусный обед на природе.','description'=>'Оборудованное место для пикника: простой способ провести день вне города. Приезжайте на несколько часов и отдыхайте от суеты.','price_per_hour'=>750,'capacity'=>10,'cover_url'=>'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?w=900&q=80','sort_order'=>4],
            ['name'=>'Семейная терраса','slug'=>'terrasa','category'=>'Для семьи','short_description'=>'Светлое место, чтобы встретиться с близкими и замедлиться.','description'=>'Комфортная терраса с видом на зелёные просторы подходит для небольших семейных событий и приятных встреч.','price_per_hour'=>1100,'capacity'=>10,'cover_url'=>'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=900&q=80','sort_order'=>5],
            ['name'=>'Зона отдыха у костра','slug'=>'koster','category'=>'Атмосфера','short_description'=>'Вечерние разговоры, огонь и звёзды над головой.','description'=>'Оборудованная зона отдыха у костра: приглашаем на тёплый вечер в хорошей компании. Соблюдайте правила пожарной безопасности.','price_per_hour'=>900,'capacity'=>8,'cover_url'=>'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=900&q=80','sort_order'=>6],
        ];
        foreach ($services as $item) {
            Service::firstOrCreate(['slug' => $item['slug']], $item + ['is_active' => true]);
        }
        foreach ([
            ['name'=>'Аренда мангала','description'=>'Для вкусных вечеров на природе','price'=>600],
            ['name'=>'Прокат инвентаря','description'=>'Игры и активный отдых','price'=>450],
            ['name'=>'Чайный набор','description'=>'Ароматный чай и угощения','price'=>350],
            ['name'=>'Набор для пикника','description'=>'Всё необходимое для компании','price'=>850],
        ] as $extra) Extra::firstOrCreate(['name' => $extra['name']], $extra + ['is_active' => true]);

        if (! Booking::where('user_id', $guest->id)->exists()) {
            $visit = Booking::create([
                'user_id' => $guest->id,
                'service_id' => Service::where('slug','lesnoy-domik')->firstOrFail()->id,
                'start_at' => now()->subDays(18)->setTime(15,0),
                'end_at' => now()->subDays(18)->setTime(18,0),
                'guest_count' => 2, 'status' => 'confirmed', 'total_price' => 5400,
            ]);
            Review::create([
                'user_id' => $guest->id, 'booking_id' => $visit->id, 'rating' => 5,
                'body' => 'Очень уютное место! Понравилась атмосфера, тишина и возможность спокойно отдохнуть вдали от города. Обязательно приедем ещё.', 'status' => 'published',
            ]);
        }
    }
}
