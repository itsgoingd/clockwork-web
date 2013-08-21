Clockwork
=========

[Clockwork](http://github.com/itsgoingd/clockwork-chrome) is a Chrome extension for PHP development, extending Developer Tools with a new panel providing all kinds of information useful for debugging and profilling your PHP applications, including information about request, headers, get and post data, cookies, session data, database queries, routes, visualisation of application runtime and more.

This repository contains embeddable webapp version of Clockwork, supporting many modern browsers along Chrome. For more information see [the original extansion repository](http://github.com/itsgoingd/clockwork-chrome).

## Installation

This extension provides out of the box support for Laravel 4 and Slim 2 frameworks, you can add support for any other or custom framework via an extensible API.

To install the latest version simply add it to your `composer.json`:

```javascript
"itsgoingd/clockwork-web": "dev-master"
```

### Laravel 4

Once Clockwork is installed, you need to register the Laravel service provider, in your `app/config/app.php`:

```php
'providers' => array(
	...    
    'Clockwork\Web\Support\Laravel\ClockworkWebServiceProvider'
)
```

By default, Clockwork will only be available in debug mode, you can change this and other settings in the configuration file. Use the following Artisan command to publish the configuration file into your config directory:

```
$ php artisan config:publish itsgoingd/clockwork-web --path vendor/itsgoingd/clockwork-web/Clockwork/Web/Support/Laravel/config/
```

When the service provider is registered, a variable "clockwork_web" will be available in your views, you should output it in your layout template, ideally just before body closing tag.

### Slim 2

Once Clockwork is installed, you need to add the Slim middleware to your app:

```php
$app = new Slim(...);
$app->add(new Clockwork\Web\Support\Slim\ClockworkWebMiddleware());
```

When the middleware is registered, a variable "clockwork_web" will be available in your views, you should output it in your layout template, ideally just before body closing tag.

## Licence

Copyright (c) 2013 Miroslav Rigler

MIT License

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
