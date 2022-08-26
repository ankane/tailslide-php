# Tailslide PHP

Median and percentile for Eloquent / Laravel

Supports:

- PostgreSQL
- MariaDB
- MySQL (with an extension)
- SQL Server

:fire: Uses native functions when possible for blazing performance

[![Build Status](https://github.com/ankane/tailslide-php/workflows/build/badge.svg?branch=master)](https://github.com/ankane/tailslide-php/actions)

## Installation

Run:

```sh
composer require ankane/tailslide
```

For MySQL, also follow [these instructions](#additional-instructions).

## Getting Started

Median

```php
Item::median('price');
```

Percentile

```php
Request::percentile('response_time', 0.95);
```

## Additional Instructions

### MySQL

MySQL requires the `PERCENTILE_CONT` function from [udf_infusion](https://github.com/infusion/udf_infusion). To install it, do:

```sh
git clone https://github.com/infusion/udf_infusion.git
cd udf_infusion
./configure --enable-functions="percentile_cont"
make
sudo make install
mysql <options> < load.sql
```

## History

View the [changelog](CHANGELOG.md)

## Contributing

Everyone is encouraged to help improve this project. Here are a few ways you can help:

- [Report bugs](https://github.com/ankane/tailslide-php/issues)
- Fix bugs and [submit pull requests](https://github.com/ankane/tailslide-php/pulls)
- Write, clarify, or fix documentation
- Suggest or add new features

To get started with development:

```sh
git clone https://github.com/ankane/tailslide-php.git
cd tailslide-php
composer install
```

To run the tests:

```sh
# Postgres
createdb tailslide_php_test
ADAPTER=pgsql composer test

# MariaDB
mysqladmin create tailslide_php_test
ADAPTER=mariadb composer test

# MySQL (install the extension first)
mysqladmin create tailslide_php_test
ADAPTER=mysql composer test

# SQL Server
docker run -e 'ACCEPT_EULA=Y' -e 'SA_PASSWORD=YourStrong!Passw0rd' -p 1433:1433 -d mcr.microsoft.com/mssql/server:2019-latest
docker exec -it <container-id> /opt/mssql-tools/bin/sqlcmd -S localhost -U SA -P YourStrong\!Passw0rd -Q "CREATE DATABASE tailslide_php_test"
ADAPTER=sqlsrv composer test
```
