# API Platform Extra

API Platform Extra includes additional features or bug fixes into API Platform that haven't been released or won't be released.

## Installation

Install with composer at `krak/api-platform-extra`.

## Usage

To enable the playground, you can create a playground.php in the project root: `%kernel.project_dir%/playground.php`.

This file needs to return a closure. Here's an example:

```php
<?php

/** this function is autowired, so type hint any service to access it here */
return function(App\Service\MyService $service, Psr\Log\LoggerInterface $log) {
    $log->info("Playing with my symfony app!");
};
```

You should be able to run this with: `./bin/console playground`.
