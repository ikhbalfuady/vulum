---
description: Clone this project into your pc and follow the instruction bellow
---

# Installation

## API

Requirements :

* PHP 7.4^
* Composer 

run this command in root project directory

```text
 $ composer install
```

after completed installing of API package setup your 'env' in root project and the database, and then run this command below to initiate first data

```text
$ php artisan migrate --seed
```

Reference default / general usage :  
[https://lumen.laravel.com/](https://lumen.laravel.com/)

## Front End

Requirements :

* Node.js 12^

run this command in ./ui directory

```text
$ npm install
```

after all package installed, you can run this app with command bellow

```text
$ quasar dev
```

Reference default / general usage :  
[https://v1.quasar.dev/](https://v1.quasar.dev/)

