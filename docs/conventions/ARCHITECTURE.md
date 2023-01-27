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
```

#### Domain structure

```
/{Domain}
|-- /Application - Buisiness logic, rules & services layer
|   |-- /Command - Commands & command handlers
|   |-- /Dto
|   |-- /Event - Domain event handlers
|   |-- /Query - Queries
|-- /Domain - Buisiness model layer
|   |-- /Entity
|   |-- /Event - Domain events
|   |-- /Exception - Domain exceptions
|   |-- /Repository - Repositories interfaces
|   |-- /ValueObject
|   |-- /Service - Aggragates
|-- /Infrastructure - Data layer
|   |-- /Facade - Интерфейсы обращения к модулю 
|   |-- /Adapter - Интерфейсы обращения модуля к другим модулям и сервисам
|   |-- /Model - ORM models
|   |-- /Query - Query handlers
|   |-- /Providers - Domain service providers
|   |-- /Repository - Domain repository implementation
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
```
