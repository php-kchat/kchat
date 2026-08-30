
![](https://github.com/php-kchat/kchat/blob/master/public/logo/3.svg)

# KChat
#### PHP Based Chat Application.

## Requirements

* Web Server Apache or Nginx
* MySQL 8.3
* PHP version >= 8.2
    * Required extensions :
        * ctype
        * curl
        * dom
        * fileinfo
        * filter
        * hash
        * json
        * libxml
        * mbstring
        * openssl
        * pcre
        * phar
        * session
        * tokenizer
        * xml
        * xmlwriter

## Manual installation

#### Downlaod Kchat Files

#### Using git

```
git clone https://github.com/php-kchat/kchat.git
```

#### Install Composer

```
composer install
```

> OR

[Download Zip](https://github.com/php-kchat/kchat/archive/refs/heads/master.zip)
and Extract to your Web Directory

#### Install Composer

```
composer install
```

> OR

#### Using with Composer

```
composer create-project php-kchat/kchat
```

### Run following command to complete installation

Create ``.env`` if not exist.
```
cp .env.example .env
```

Configure database details in ``.env``
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Generate ``APP_KEY`` in the ``.env`` file:
```
php artisan key:generate
```

### Chat widget testing

To test the widget on a separate HTML page, include the script from your Laravel app:

```html
<script src="http://localhost/widget/embed.js?token=YOUR_WIDGET_TOKEN"></script>
```

Use the public test file at `public/widget-test.html` as a quick example. Replace the token with a real token generated in the admin Chat Widget Settings page.

For browser-based widget requests, make sure the widget API endpoints are reachable from the host page. In `config/cors.php`, allow the test domain/port and keep the widget route methods open to `POST` requests, for example:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie', 'widget/*'],
'allowed_origins' => ['http://localhost', 'http://127.0.0.1'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

If you are testing from another port or domain, add that origin to `allowed_origins` or host the page on the same Laravel domain.

Create tables:
```
php artisan migrate
```

Give a Writable Permission on

- storage/*
- bootstrap/cache/*
- public/images/*

Sign-in your first user and login

#### Maintainers

- [Ganesh Kandu](https://github.com/GaneshKandu)
	- [Linkedin](https://www.linkedin.com/in/ganeshkandu/)
