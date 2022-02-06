
```
I am not able to maintain it due to not getting much time.
Welcome your pull requests
```

![](https://github.com/php-kchat/kchat/blob/master/kchat/assets/images/logo.svg)

# KChat
#### PHP Based Chat Application.

## Requirements

* PHP version >= 5.5
    * Required extensions :
        * PDO_Mysql
        * json
* Web Server Apache or Nginx
* MySQL 5

## Installation

#### Downlaod Kchat Files

#### Using with Composer

```
composer create-project php-kchat/kchat
```

#### Or

#### Using git

```
git clone https://github.com/php-kchat/kchat.git
```

#### Or

[Download Zip](https://github.com/php-kchat/kchat/archive/refs/tags/1.0.10.tar.gz)
and Extract to your Web Directory

#### And

Give a Writable Permission on
* config
* logs
* cache
* logs/kchat.log.php
* box/config
* box/logs/error.log


Visit the subfolder https://mydomain.com/index.php in your web-browser.
The installation script will start automatically and guide you through the installation process.

#### Login with
* Admin - admin
* Password - pass

#### note.

- if you getting internal server error when you installed KChat in sub-directory please update .htaccess

Uncomment and update RewriteBase

ex.
```
RewriteBase /{{your-relative-url}}/
```

- if message box not working try adding jquery in your code

ex.

```html
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
```

#### Maintainers

- [Ganesh Kandu](https://github.com/GaneshKandu)
	- [Linkedin](https://www.linkedin.com/in/ganesh-kandu-42b14373/)
