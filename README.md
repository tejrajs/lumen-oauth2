# Lumen Oauth2 Api With Passport
Basically this is a starter kit for you to integrate Lumen with [Oauth2](https://oauth.net/2/) with Lumen Passport

## What's Added
 - [Lumen 5.8](https://github.com/laravel/lumen/tree/v5.8.0).
 - [Lumen Passport](https://github.com/dusterio/lumen-passport/tree/0.2.14)

## Quick Start

``` bash
# Clone The Repo
git clone https://github.com/tejrajs/lumen-oauth2.git
```

``` bash
# Get inside to the Repo
cd lumen-oauth2
```

``` bash
# Install Dependencies
composer install
```

``` bash
# Create ENV file
cp .env.example .env
```

``` bash
# Update Database setting in .env file
APP_KEY=<Your Key>

-----------

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=databasename
DB_USERNAME=user
DB_PASSWORD=password
```

### Migrate and install Laravel Passport

```bash
# Create new tables for Passport
php artisan migrate

# Install encryption keys and other necessary stuff for Passport
php artisan passport:install
```

### Installed routes

This package mounts the following routes after you call routes() method (see instructions below):

Verb | Path | NamedRoute | Controller | Action | Middleware
--- | --- | --- | --- | --- | ---
POST   | /oauth/token                             |            | \Laravel\Passport\Http\Controllers\AccessTokenController           | issueToken | -
GET    | /oauth/tokens                            |            | \Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController | forUser    | auth
DELETE | /oauth/tokens/{token_id}                 |            | \Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController | destroy    | auth
POST   | /oauth/token/refresh                     |            | \Laravel\Passport\Http\Controllers\TransientTokenController        | refresh    | auth
GET    | /oauth/clients                           |            | \Laravel\Passport\Http\Controllers\ClientController                | forUser    | auth
POST   | /oauth/clients                           |            | \Laravel\Passport\Http\Controllers\ClientController                | store      | auth
PUT    | /oauth/clients/{client_id}               |            | \Laravel\Passport\Http\Controllers\ClientController                | update     | auth
DELETE | /oauth/clients/{client_id}               |            | \Laravel\Passport\Http\Controllers\ClientController                | destroy    | auth
GET    | /oauth/scopes                            |            | \Laravel\Passport\Http\Controllers\ScopeController                 | all        | auth
GET    | /oauth/personal-access-tokens            |            | \Laravel\Passport\Http\Controllers\PersonalAccessTokenController   | forUser    | auth
POST   | /oauth/personal-access-tokens            |            | \Laravel\Passport\Http\Controllers\PersonalAccessTokenController   | store      | auth
DELETE | /oauth/personal-access-tokens/{token_id} |            | \Laravel\Passport\Http\Controllers\PersonalAccessTokenController   | destroy    | auth

Please note that some of the Laravel Passport's routes had to 'go away' because they are web-related and rely on sessions (eg. authorise pages). Lumen is an
API framework so only API-related routes are present.

## User model

Make sure your user model uses Passport's ```HasApiTokens``` trait, eg.:

```php
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    /* rest of the model */
}
```

## Extra features

There are a couple of extra features that aren't present in Laravel Passport

### Allowing multiple tokens per client

Sometimes it's handy to allow multiple access tokens per password grant client. Eg. user logs in from several browsers 
simultaneously. Currently Laravel Passport does not allow that.

```php
use Dusterio\LumenPassport\LumenPassport;

// Somewhere in your application service provider or bootstrap process
LumenPassport::allowMultipleTokens();

```

### Different TTLs for different password clients

Laravel Passport allows to set one global TTL for access tokens, but it may be useful sometimes
to set different TTLs for different clients (eg. mobile users get more time than desktop users).

Simply do the following in your service provider:

```php
// Second parameter is the client Id
LumenPassport::tokensExpireIn(Carbon::now()->addYears(50), 2); 
```

If you don't specify client Id, it will simply fall back to Laravel Passport implementation.

### Console command for purging expired tokens

Simply run ```php artisan passport:purge``` to remove expired refresh tokens and their corresponding access tokens from the database.


## Running with Apache httpd

If you are using Apache web server, it may strip Authorization headers and thus break Passport.

Add the following either to your config directly or to ```.htaccess```:

```
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```
