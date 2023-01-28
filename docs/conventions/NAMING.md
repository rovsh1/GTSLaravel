### Files naming

```
/Application/Command/{Verb}.php                 // Create.php
/Application/Query/{Verb}.php                   // Find.php
/Application/Validation/{Noun}Validator.php     // UserValidator.php

/Domain/Entity/{Noun}.php                       // User.php
/Domain/Event/{Verb Past}.php                   // Created.php
/Domain/ValueObject/{Noun}.php                  // Phone.php

/Infrastructure/Adapter/{Noun}Adapter.php       // UserAdapter.php OR User/Adapter.php
/Infrastructure/Facade/{Noun}Facade.php         // UserFacade.php OR User/Facade.php
/Infrastructure/Repository/{Noun}Repository.php // UserRepository.php OR User/Repository.php

UI/Http/Controllers/{Noun}Controller.php        // UserController.php
UI/Http/Actions/{Verb}Action.php                // CreateAction.php
```

### PHP code

```
interface {ClassName}Interface {}

enum {EnumName}Enum {}

enum {EnumName}Enum {}
```


