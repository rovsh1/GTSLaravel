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
|   |-- /Command - Commands & handlers
|   |-- /Query - Queries
|   |-- /Event - DomainEventHandlers
|-- /Domain - Buisiness model layer
|   |-- /Entity
|   |-- /Event - Domain events
|   |-- /Exception
|   |-- /Repository - interfaces
|   |-- /ValueObject
|   |-- /Service - Aggragates
|-- /Infrastructure - Data layer
|   |-- /Api - Input requests api
|   |-- /Adapter - Output requests adapters
|   |-- /Model - ORM models
|   |-- /Query - Query handlers
|   |-- /Providers - Domain service providers
|   |-- /Repository - Domain repository implementation
|-- /UI - Interfaces layer
|   |-- /Admin
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
