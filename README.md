
![](https://github.com/php-kchat/kchat/blob/master/public/logo/KChat_Logo.svg)

# KChat
#### PHP Based Chat Application.

## Requirements

* Web Server Apache or Nginx
* MySQL 5.7
* PHP version >= 8.0
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
