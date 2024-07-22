# Development

Main service: **AfterRefactoring/BusinessLogic/CommissionCalculatorService.php** 

## Debug

You can use x-debug for docker containers.

## Code analysis

```php
make analyse
```

## Auto tests

```php
make test
```

## Add new API
Add new client to **src/AfterRefactoring/Infrastructure/ExternalApis** folder.

## Add new API methods
Add new RequestDto to
- **AfterRefactoring/Infrastructure/ExternalApis/BinListApi/Dtos/RequestDtos**
- **AfterRefactoring/Infrastructure/ExternalApis/ExchangeRatesApi/Dtos/RequestDtos**

## Logging
All logs places in **var/logs/app.log**

Test logs places in **var/logs/app.test.log**