# modulr-laravel

## Table of Contents

- [Install](#install)


### Install

1. Clone repository
```
$ git clone https://github.com/modulr/modulr-laravel.git
```

2. Enter to folder
```
$ cd modulr-laravel
```

3. Install composer dependencies
```
// Composer
~/modulr-laravel$ composer install

// npm
~/modulr-laravel$ npm install
```

4. Generate APP_KEY
```
~/modulr-laravel$ php artisan key:generate
```

5. Configure .env file
```
// Add database params
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

6. Run migrations
```
~/modulr-laravel$ php artisan migrate
```

7. Run seeders * *optional* *
> NOTE: Seeds create 10 users fake with gravatar

```
~/modulr-laravel$ php artisan db:seed
```

8. Run serve * *optional* *
```
> NOTE: Developer mode
~/modulr-laravel$ npm run dev
```
