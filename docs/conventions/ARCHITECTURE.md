## Architecture

#### App structure

```
/app
|-- /{Domain1}
|-- /{Domain2}
|-- /{Domain3}
|   /Services - Внешние сервисы и модули
|   |-- /{ServiceDomain1}
|   |-- /{ServiceDomain2}
|-- /Shared - common app & domains classes
|   |-- /Custom - custom classes, framework extends
```

#### Domain structure

```
/{Domain}
|-- /Application - Buisiness logic, rules & services layer
|   |-- /Command - Commands & command handlers
|   |-- /Dto
|   |-- /Event - Domain event handlers
|   |-- /Query - Queries
|   |-- /Service - Used by external consumers to talk to your system (think Web Services). If consumers need access to CRUD operations, they would be exposed here.
|-- /Domain - Buisiness model layer
|   |-- /Entity
|   |-- /Event - Domain events
|   |-- /Exception - Domain exceptions
|   |-- /Repository - Repositories interfaces
|   |-- /ValueObject
|   |-- /Service - Encapsulates business logic that doesn't naturally fit within a domain object, and are NOT typical CRUD operations – those would belong to a Repository.
|-- /Infrastructure - Data layer
|   |-- /Facade - Интерфейсы обращения к модулю 
|   |-- /Adapter - Интерфейсы обращения модуля к другим модулям и сервисам
|   |-- /Model - ORM models
|   |-- /Query - Query handlers
|   |-- /Providers - Domain service providers
|   |-- /Repository - Domain repository implementation
|   |-- /Services - Used to abstract technical concerns (e.g. MSMQ, email provider, etc).
|-- /UI - Interfaces layer
|   |-- /Admin
|   |   |-- /Exception - Exception handler & UI exceptions
|   |   |-- /Http
|   |   |   |-- /Actions
|   |   |   |-- /Controllers
|   |   |   |-- /Middleware
|   |   |-- /Providers - UI service providers
|   |   |-- /routes.php
|   |-- /Api
|   |-- /Site
|-- /Tests
|   |-- /Feature
|   |   |-- /Http
|   |   |   |-- /Admin
|   |   |   |   |-- /Controllers
|   |-- /Unit
```

#### Resources

```
/resources
|-- /admin
|   |-- /sass
|   |   |-- /page - webpack compile css files
|   |-- /js
|   |   |-- /page - webpack compile js files
|   |-- /views
|-- /site
```
