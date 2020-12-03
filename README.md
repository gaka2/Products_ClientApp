# Description

Client application

# Installation

Run:
```
git clone <repository_url>
composer install --no-dev --optimize-autoloader
```

Configure your server:
document root: public
domain: localhost/client_app/

# Testing

Run:
```
php bin/phpunit
```

# Usage

## Web browser
Examples:
```
localhost/client_app/api/products/
localhost/client_app/api/products/avaivable
localhost/client_app/api/products/unavaivable
localhost/client_app/api/products/?amountBiggerThan=5
```