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

You can register global variables (like the current user or csrf token) in your `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Eusebiu\JavaScript\Facades\ScriptVariables;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        ScriptVariables::add(function () {
            return [
                'csrfToken' => csrf_token(),
                'currentUser' => auth()->user(),
            ];
        });
    }
}
```

> Note that the variables must be passed via a closure.
