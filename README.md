# modulr-laravel

## Table of Contents

- [Install](#install)
- [Configure](#configure)
- [Contribute](#contribute)
- [Community](#community)
- [Credits](#credits)
- [License](#license)

## Install

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


## Configure

Generate APP_KEY
```
~/modulr-laravel$ php artisan key:generate
```

Create Data Base
```
~/modulr-laravel$ mysql -u{user} -p{password}
~/modulr-laravel$ create database modulr_laravel
```

Create .env file
```
~/modulr-laravel$ cp .env.example .env
```

Configure .env file
```
// Add database params
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=modulr_laravel
DB_USERNAME=user
DB_PASSWORD=password
```

Run migrations
```
~/modulr-laravel$ php artisan migrate
```

Run seeders * *optional* *
> NOTE: Seeds create 10 users and 3 news for user

```
~/modulr-laravel$ php artisan db:seed
```

Run serve
```
~/modulr-laravel$ php artisan serve
```

## Contribute

Run serve * *optional* *
> NOTE: Developer mode

```
~/modulr-laravel$ npm run dev
```


## Community

- Join [the official Slack room](https://modulr.slack.com/).
- Implementation help may be found at Stack Overflow (tagged [`modulr`](http://stackoverflow.com/questions/tagged/modulr)).

## Credits

- [@Alfredobarron](https://github.com/alfredobarron)

## License
The [MIT©](https://github.com/modulr/modulr/blob/master/LICENSE) License 2017 - Modulr.
