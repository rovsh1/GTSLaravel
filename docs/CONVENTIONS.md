# Dev recommendations

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
|-- /Interface - UI layer
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

## Code style

## Git

#### Commits

```
# commit message format
{preset}({group}?): description

# examples:
feat: travelline api
fix(admin): hotel routes
test(api): add Http\Controllers\GetHotelByIdTest
```

#### Changelog presets:

- breaking_changes - Code changes that potentially causes other components to fail
- feat - New features
- perf - Code changes that improves performance
- fix - Bugs and issues resolution
- refactor - A code change that neither fixes a bug nor adds a feature
- style - Changes that do not affect the meaning of the code
- test - Adding missing tests or correcting existing tests
- build - Changes that affect the build system or external dependencies
- ci - Changes to CI configuration files and scripts
- docs - Documentation changes
- chore - Other changes that don't modify the source code or test files
- revert - Reverts a previous commit
