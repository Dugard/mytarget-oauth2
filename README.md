# MyTarget Oauth2
- Получение токенов для взаимодействия с [MyTarget Api](https://target.my.com/adv/api-marketing).
- Оффициальная документация по получению токенов [MyTarget Oauth2](https://target.my.com/adv/api-marketing/doc/authorization).
- После получения токена, его можно использовать для управления ресурсами, воспользовавшись смежной библиотекой [dugard/mytarget-api-client](https://github.com/dugard/mytarget)

## Требования
 * PHP 7.0 и выше
 
## Установка  

Через composer:

```
composer require dugard/mytarget-oauth2
```

## Использование
```php
use dugard\myTarget\oauth2\Oauth2;

// инициализация клиента с конфигурацией по умолчанию
$oauth2 = new Oauth2();
```

```php
// инициализация клиента с конфигурацией пользователя
$oauth2 = new Oauth2([
    // по умолчанию false. Если true, запросы будут отправляться к песочнице myTarget.
    'sandbox' => true,
    // по умолчанию true. Если true, ответом на запросы к myTarget будет ассоциативный массив, 
    // в противном случае объект.
    'assoc' => false,
    // по умолчанию false. Включает опцию debug 
    // http://docs.guzzlephp.org/en/stable/request-options.html#debug.
    'debug' => true,
]);
```

```php
// получение клиентского токена
$token = $oauth2->clientCredentialsGrant('client_id', 'client_secret')->request();
```

```php
// получение токена агенства
$token = $oauth2->agencyCredentialsGrant(
    'client_id',
    'client_secret',
    'agency_client_name')->request();
```

```php
// получение токена клиента|менеджера агенства
$token = $oauth2->agencyCredentialsGrant(
    'client_id',
    'client_secret',
    'agency_client_name',
    'agency_access_token')->request();
```

```php
// получение токена по коду
use dugard\myTarget\oauth2\Scopes;

// в классе dugard\myTarget\oauth2\Scopes существует несколько методов с различными
// вариантами прав и константы для своего набора прав.
$scopes = Scopes::all();  
$code = $oauth2->authorize('client_id', 'state', $scopes)->request();
$token = $oauth2->authorizationCodeGrant($code, 'client_id')->request();
```

```php
// обновление токена
$tokenWithNewAccessToken = $oauth2->refreshToken(
    'refresh_token',
    'client_id',
    'client_secret')->request();
```

```php
// удаление токена
$oauth2->deleteToken('client_id', 'client_sercret')->request();
```
