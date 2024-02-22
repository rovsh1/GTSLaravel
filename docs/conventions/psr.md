# PSR

- All PHP files MUST use the Unix LF (linefeed) line ending.

## Соблюдайте соглашения сообщества об именовании

### Следуйте стандартам PSR при написании кода.

### Также, соблюдайте другие cоглашения об именовании:

| Что                                 | Правило                                     | Принято                                 | Не принято                                          |
|-------------------------------------|---------------------------------------------|-----------------------------------------|-----------------------------------------------------|
| Контроллер                          | ед. ч.                                      | ArticleController                       | ~~ArticlesController~~                              |
| Action Контроллера                  | глагол                                      | CreateAction                            | ~~Create, UserAction~~                              |
| Adapter                             | ед. ч.                                      | UserAdapter OR User/Adapter             |                                                     |
| Facade                              | ед. ч.                                      | UserFacade OR User/Facade               |                                                     |
| Repository                          | ед. ч.                                      | UserRepository OR User/Repository       |                                                     |
| Services                            | ед. ч.                                      | EmailNotifier                           |                                                     |
| Entity                              | ед. ч.                                      | User                                    | ~~UserEntity, Users~~                               |
| Event                               | глагол в прош. времени                      | Created                                 | ~~CreateEvent~~                                     |
| ValueObject                         | ед. ч.                                      | Phone                                   |                                                     |
| Command                             | глагол                                      | Create                                  | ~~CreateCommand~~                                   |
| Query                               | глагол                                      | Find                                    |                                                     |
| Validation                          | ед. ч.                                      | UserValidator                           |                                                     |
| Маршруты                            | мн. ч.                                      | articles/1                              | ~~article/1~~                                       |
| Имена маршрутов                     | snake_case                                  | users.show_active                       | ~~users.show-active, show-active-users~~            |
| Модель                              | ед. ч.                                      | User                                    | ~~Users~~                                           |
| Отношения hasOne и belongsTo        | ед. ч.                                      | articleComment                          | ~~articleComments, article_comment~~                |
| Все остальные отношения             | мн. ч.                                      | articleComments                         | ~~articleComment, article_comments~~                |
| Таблица                             | мн. ч.                                      | article_comments                        | ~~article_comment, articleComments~~                |
| Pivot таблица                       | имена моделей в алфавитном порядке в ед. ч. | article_user                            | ~~user_article, articles_users~~                    |
| Столбец в таблице                   | snake_case без имени модели                 | meta_title                              | ~~MetaTitle; article_meta_title~~                   |
| Свойство модели                     | snake_case                                  | $model->created_at                      | ~~$model->createdAt~~                               |
| Внешний ключ имя модели             | ед. ч. и _id                                | article_id                              | ~~ArticleId, id_article, articles_id~~              |
| Первичный ключ                      | -                                           | id                                      | ~~custom_id~~                                       |
| Миграция                            | -                                           | 2017_01_01_000000_create_articles_table | ~~2017_01_01_000000_articles~~                      |
| Метод                               | camelCase                                   | getAll                                  | ~~get_all~~                                         |
| Метод в контроллере ресурсов        | таблица                                     | store                                   | ~~saveArticle~~                                     |
| Метод в тесте                       | camelCase                                   | testGuestCannotSeeArticle               | ~~test_guest_cannot_see_article~~                   |
| Переменные                          | camelCase                                   | $articlesWithAuthor                     | ~~$articles_with_author~~                           |
| Коллекция                           | описательное, мн. ч.                        | $activeUsers = User::active()->get()    | ~~$active, $data~~                                  |
| Объект                              | описательное, ед. ч.                        | $activeUser = User::active()->first()   | ~~$users, $obj~~                                    |
| Индексы в конфиге и языковых файлах | snake_case                                  | articles_enabled                        | ~~ArticlesEnabled; articles-enabled~~               |
| Представление                       | kebab-case                                  | show-filtered.blade.php                 | ~~showFiltered.blade.php, show_filtered.blade.php~~ |
| Конфигурационный файл               | snake_case                                  | google_calendar.php                     | ~~googleCalendar.php, google-calendar.php~~         |
| Контракт (интерфейс)                | прилагательное или существительное          | AuthenticationInterface                 | ~~Authenticatable, IAuthentication~~                |
| Абстрактный класс                   | -                                           | AbstractUser                            | ~~BaseUser, Human~~                                 |
| Перечисление                        | ед. ч.                                      | StatusEnum                              | ~~Statues~~                                         |
| Трейт                               | прилагательное                              | Notifiable                              | ~~NotificationTrait~~                               |

### Class format

```
namespace

use FRAMEWORKS

use CUSTOM

use GTS

use VENDORS

class ExampleClass {
    public function importantMethod() {}
    
    private function importantMethod() {}
    
    private function rareMethod() {}
    
    protected function rareMethod() {}
}
```
