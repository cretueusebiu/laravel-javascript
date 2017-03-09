<h1>laravel-javascript</h1>

<a href="https://packagist.org/packages/cretueusebiu/laravel-javascript"><img src="https://poser.pugx.org/cretueusebiu/laravel-javascript/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://travis-ci.org/cretueusebiu/laravel-javascript"><img src="https://travis-ci.org/cretueusebiu/laravel-javascript.svg?branch=master" alt="Build Status"></a>
<a href="https://packagist.org/packages/cretueusebiu/laravel-javascript"><img src="https://poser.pugx.org/cretueusebiu/laravel-javascript/d/total.svg" alt="Total Downloads"></a>

> Add JavaScript variables from Laravel.

```php
ScriptVariables::add('user', Auth::user());
```

```javascript
const user = window.config.user
```

## Installation

Install the package via Composer:

```bash
composer require cretueusebiu/laravel-javascript
```

Next, you need to register the service provider and facade:

```php
// config/app.php

'providers' => [
    ...
    Eusebiu\JavaScript\JavaScriptServiceProvider::class,
],

'aliases' => [
    ...
    'ScriptVariables' => Eusebiu\JavaScript\Facades\ScriptVariables::class,
],
```

## Usage

In your controller:

```php
<?php

namespace App\Http\Controllers;

use Eusebiu\JavaScript\Facades\ScriptVariables;

class HomeController extends Controller
{
    public function home()
    {
        ScriptVariables::add('key', 'value');
        ScriptVariables::add('data.user', User::first());
    }
}
```

Next, in your blade view add:

```php
{{ JavaScript::render() }}
```

Then in your JavaScript you can use:

```javascript
const key = window.config.key
const user = window.config.data.user
```

To customize the namespace use `JavaScript::render('custom')`.

#### Global Variables

To add global variables like the authenticated user or the csrf token, create a middleware:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Eusebiu\JavaScript\Facades\ScriptVariables;

class AddScriptVariables
{
    public function handle($request, Closure $next)
    {
        ScriptVariables::add([
            'csrfToken' => csrf_token(),
            'currentUser' => auth()->user(),
        ]);

        return $next($request);
    }
}
```

Then register the middleware:

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        ....
        \App\Http\Middleware\AddScriptVariables::class,
    ],
];
```
