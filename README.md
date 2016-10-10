[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Distilleries/DatatableBuilder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Distilleries/DatatableBuilder/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Distilleries/DatatableBuilder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Distilleries/DatatableBuilder/?branch=master)
[![Build Status](https://travis-ci.org/Distilleries/DatatableBuilder.svg?branch=master)](https://travis-ci.org/Distilleries/DatatableBuilder)
[![Total Downloads](https://poser.pugx.org/distilleries/datatable-builder/downloads)](https://packagist.org/packages/distilleries/datatable-builder)
[![Latest Stable Version](https://poser.pugx.org/distilleries/datatable-builder/version)](https://packagist.org/packages/distilleries/datatable-builder)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)



#Laravel 5 Logentries integration


## Table of contents
1. [Installation](#installation)
2. [Basic usage](#basic-usage)
  


##Installation
  
Add on your composer.json

``` json
    "require": {
        "distilleries/logentries": "1.1.*",
    }
```

run `composer update`.

Add Service provider to `config/app.php`:

``` php
    'providers' => [
        // ...
       'Distilleries\Logentries\LogentriesServiceProvider::class
    ]
```

Export the configuration:

```ssh
php artisan vendor:publish --provider="Distilleries\Logentries\LogentriesServiceProvider"
```

## Basic usage
First of all you need to create an account on https://logentries.com/
After that you just have to put on your .env a token key :

```
LOG_ENTRIES_TOKEN=c0d59aa1-********

```

This package override the Log facade. Basicly if you have already use Log it's exacly the same. If you never use Log from laravel go to the official documentation https://laravel.com/docs/5.3/errors

```php

\Log::info('Here');

```

