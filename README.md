# modulr-laravel

## Table of Contents

- [Install](#install)
- [Config](#config)
- [Contribute](#contribute)


### Install

Clone repository
```
$ git clone https://github.com/modulr/modulr-laravel.git
```

Enter to folder
```
$ cd modulr-laravel
```

Install dependencies
```
// Composer
~/modulr-laravel$ composer install

// npm
~/modulr-laravel$ npm install
```


### Config

Generate APP_KEY
```
~/modulr-laravel$ php artisan key:generate
```

Configure .env file
```
// Add database params
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Run migrations
```
~/modulr-laravel$ php artisan migrate
```

Run seeders * *optional* *
> NOTE: Seeds create 10 users fake with gravatar

```
~/modulr-laravel$ php artisan db:seed
```


### Contribute

Run serve * *optional* *
```
> NOTE: Developer mode
~/modulr-laravel$ npm run dev
```
