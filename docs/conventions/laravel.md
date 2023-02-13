## Laravel

### Принцип единственной ответственности (Single responsibility principle)

> Каждый класс и метод должны выполнять лишь одну функцию.

Плохо:

```php
public function getFullNameAttribute()
{
    if (auth()->user() && auth()->user()->hasRole('client') && auth()->user()->isVerified()) {
        return 'Mr. ' . $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    } else {
        return $this->first_name[0] . '. ' . $this->last_name;
    }
}
```

Хорошо:

```php
public function getFullNameAttribute()
{
    return $this->isVerifiedClient() ? $this->getFullNameLong() : $this->getFullNameShort();
}

public function isVerifiedClient()
{
    return auth()->user() && auth()->user()->hasRole('client') && auth()->user()->isVerified();
}

public function getFullNameLong()
{
    return 'Mr. ' . $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
}

public function getFullNameShort()
{
    return $this->first_name[0] . '. ' . $this->last_name;
}
```

*Тонкие контроллеры, толстые модели*
*По своей сути, это лишь один из частных случаев принципа единой ответственности. Выносите работу с данными в модели при работе с Eloquent или в репозитории при работе с Query Builder или "сырыми" SQL
запросами.*

Плохо:

```php
public function index()
{
    $clients = Client::verified()
        ->with(['orders' => function ($q) {
            $q->where('created_at', '>', Carbon::today()->subWeek());
        }])
        ->get();

    return view('index', ['clients' => $clients]);
}
```

Хорошо:

```php
public function index()
{
    return view('index', ['clients' => $this->client->getWithNewOrders()]);
}

class Client extends Model
{
    public function getWithNewOrders()
    {
        return $this->verified()
            ->with(['orders' => function ($q) {
                $q->where('created_at', '>', Carbon::today()->subWeek());
                }])
            ->get();
    }
}
```

### Валидация

> Следуя принципам тонкого контроллера и SRP, выносите валидацию из контроллера в Request классы.

Плохо:

```php
public function store(Request $request)
{
    $request->validate([
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
    'publish_at' => 'nullable|date',
    ]);

    ....
}
```

Хорошо:

```php
public function store(PostRequest $request)
{    
....
}

class PostRequest extends Request
{
    public function rules()
    {
        return [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
            'publish_at' => 'nullable|date',
        ];
    }
}
```

### Бизнес логика в сервис-классах

> Контроллер должен выполнять только свои прямые обязанности, поэтому выносите всю бизнес логику в отдельные классы и сервис классы.

Плохо:

```php
public function store(Request $request)
{
    if ($request->hasFile('image')) {
        $request->file('image')->move(public_path('images') . 'temp');
    }

    ....
}
```

Хорошо:

```php
public function store(Request $request)
{
$this->articleService->handleUploadedImage($request->file('image'));

    ....
}

class ArticleService
{
    public function handleUploadedImage($image)
    {
        if (!is_null($image)) {
            $image->move(public_path('images') . 'temp');
        }
    }
}
```

Не повторяйся (DRY)
Этот принцип призывает вас переиспользовать код везде, где это возможно. Если вы следуете принципу SRP, вы уже избегаете повторений, но Laravel позволяет вам также переиспользовать представления,
части Eloquent запросов и т.д.

Плохо:

```php
public function getActive()
{
    return $this->where('verified', 1)->whereNotNull('deleted_at')->get();
}

public function getArticles()
{
    return $this->whereHas('user', function ($q) {
        $q->where('verified', 1)->whereNotNull('deleted_at');
    })->get();
}
```

Хорошо:

```php
public function scopeActive($q)
{
    return $q->where('verified', 1)->whereNotNull('deleted_at');
}

public function getActive()
{
    return $this->active()->get();
}

public function getArticles()
{
    return $this->whereHas('user', function ($q) {
        $q->active();
    })->get();
}
```

Предпочитайте Eloquent конструктору запросов (query builder) и сырым запросам в БД. Предпочитайте работу с коллекциями работе с массивами
Eloquent позволяет писать максимально читаемый код, а изменять функционал приложения несоизмеримо легче. У Eloquent также есть ряд удобных и мощных инструментов.

Плохо:

```mysql
SELECT *
FROM `articles`
WHERE EXISTS(SELECT *
             FROM `users`
             WHERE `articles`.`user_id` = `users`.`id`
               AND EXISTS(SELECT *
                          FROM `profiles`
                          WHERE `profiles`.`user_id` = `users`.`id`)
               AND `users`.`deleted_at` IS NULL)
  AND `verified` = '1'
  AND `active` = '1'
ORDER BY `created_at` DESC
```

Хорошо:

```php
Article::has('user.profile')->verified()->latest()->get();
```

### Используйте массовое заполнение (mass assignment)

Плохо:

```php
$article = new Article;
$article->title = $request->title;
$article->content = $request->content;
$article->verified = $request->verified;
// Привязать статью к категории.
$article->category_id = $category->id;
$article->save();
```

Хорошо:

```php
$category->article()->create($request->validated());
```

### Не выполняйте запросы в представлениях и используйте нетерпеливую загрузку (проблема N + 1)

Плохо (будет выполнен 101 запрос в БД для 100 пользователей):

```
@foreach (User::all() as $user)
    {{ $user->profile->name }}
@endforeach
```

Хорошо (будет выполнено 2 запроса в БД для 100 пользователей):

```
$users = User::with('profile')->get();

...

@foreach ($users as $user)
    {{ $user->profile->name }}
@endforeach
```

### Комментируйте код, предпочитайте читаемые имена методов комментариям

Плохо:

```php
if (count((array) $builder->getQuery()->joins) > 0)
```

Лучше:

```php
// Determine if there are any joins.
if (count((array) $builder->getQuery()->joins) > 0)
```

Хорошо:

```php
if ($this->hasJoins())
```

### Выносите JS и CSS из шаблонов Blade и HTML из PHP кода

Плохо:

```php
let article = `{{ json_encode($article) }}`;
```

Лучше:

```html
<input id="article" type="hidden" value='@json($article)'>
```

Или

```html

<button class="js-fav-article" data-article='@json($article)'>{{ $article->name }}
    <button>
```

В Javascript файле:

```js
let article = $('#article').val();
```

*Еще лучше использовать специализированный пакет для передачи данных из бэкенда во фронтенд.*

### Конфиги, языковые файлы и константы вместо текста в коде

> Непосредственно в коде не должно быть никакого текста.

Плохо:

```php
public function isNormal()
{
    return $article->type === 'normal';
}

return back()->with('message', 'Ваша статья была успешно добавлена');
```

Хорошо:

```php
public function isNormal()
{
return $article->type === Article::TYPE_NORMAL;
}

return back()->with('message', __('app.article_added'));
```

### Используйте инструменты и практики принятые сообществом

> Laravel имеет встроенные инструменты для решения часто встречаемых задач. Предпочитайте пользоваться ими использованию сторонних пакетов и инструментов. Laravel разработчику, пришедшему в проект
> после вас, придется изучать и работать с новым для него инструментом, со всеми вытекающими последствиями. Получить помощь от сообщества будет также гораздо труднее. Не заставляйте клиента или
> работодателя платить за ваши велосипеды.

| Задача                           | Стандартные инструмент                | Нестандартные инструмент                            |
|----------------------------------|---------------------------------------|-----------------------------------------------------|
| Авторизация                      | Политики                              | Entrust, Sentinel и др. пакеты, собственное решение |
| Работа с JS, CSS и пр.           | Laravel Mix                           | Grunt, Gulp, сторонние пакеты                       |
| Среда разработки                 | Laravel Sail, Homestead               | Docker                                              |
| Разворачивание приложений        | Laravel Forge                         | Deployer и многие другие                            |
| Тестирование                     | Phpunit, Mockery                      | Phpspec                                             |
| e2e тестирование                 | Laravel Dusk                          | Codeception                                         |
| Работа с БД                      | Eloquent                              | SQL, построитель запросов, Doctrine                 |
| Шаблоны                          | Blade                                 | Twig                                                |
| Работа с данными                 | Коллекции Laravel                     | Массивы                                             |
| Валидация форм                   | Request классы                        | Сторонние пакеты, валидация в контроллере           |
| Аутентификация                   | Встроенный функционал                 | Сторонние пакеты, собственное решение               |
| Аутентификация API               | Laravel Passport, Laravel Sanctum     | Сторонние пакеты, использующие JWT, OAuth           |
| Создание API                     | Встроенный функционал                 | Dingo API и другие пакеты                           |
| Работа со структурой БД          | Миграции                              | Работа с БД напрямую                                |
| Локализация                      | Встроенный функционал                 | Сторонние пакеты                                    |
| Обмен данными в реальном времени | Laravel Echo, Pusher                  | Пакеты и работа с веб сокетами напрямую             |
| Генерация тестовых данных        | Seeder классы, фабрики моделей, Faker | Ручное заполнение и пакеты                          |
| Планирование задач               | Планировщик задач Laravel             | Скрипты и сторонние пакеты                          |
| БД                               | MySQL, PostgreSQL, SQLite, SQL Server | MongoDb                                             |

### Короткий и читаемый синтаксис там, где это возможно

Плохо:

```php
$request->session()->get('cart');
$request->input('name');
```

Хорошо:

```php
session('cart');
$request->name;
```

### Используйте IoC или фасады вместо new Class

> Внедрение классов через синтаксис new Class создает сильное сопряжение между частями приложения и усложняет тестирование. Используйте контейнер или фасады.

Плохо:

```php
$user = new User;
$user->create($request->validated());
```

Хорошо:

```php
public function __construct(User $user)
{
    $this->user = $user;
}

....

$this->user->create($request->validated());
```

### Не работайте с данными из файла .env напрямую

Передайте данные из .env файла в кофигурационный файл и используйте config() в приложении, чтобы использовать эти данными.

Плохо:

```php
$apiKey = env('API_KEY');
```

Хорошо:

```php
// config/api.php
'key' => env('API_KEY'),

// Используйте данные в приложении
$apiKey = config('api.key');
```

### Храните даты в стандартном формате. Используйте читатели и преобразователи для преобразования формата

Плохо:

```php
{{ Carbon::createFromFormat('Y-d-m H-i', $object->ordered_at)->toDateString() }}
{{ Carbon::createFromFormat('Y-d-m H-i', $object->ordered_at)->format('m-d') }}
```

Хорошо:

```php
// Модель
protected $dates = ['ordered_at', 'created_at', 'updated_at'];
// Читатель (accessor)
public function getSomeDateAttribute($date)
{
    return $date->format('m-d');
}

// Шаблон
{{ $object->ordered_at->toDateString() }}
{{ $object->ordered_at->some_date }}
```
