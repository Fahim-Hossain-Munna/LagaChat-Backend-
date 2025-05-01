## LagaChat Backend

##### install jwt auth

```
composer require tymon/jwt-auth

```

###### Follow this code and paste into config/app.php

```
    'providers' => ServiceProvider::defaultProviders()->merge([
        Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
    ])->toArray(),

```

##### Publish Config File

```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

```

###### Generate JWT Secret

```
php artisan jwt:secret

```

##### Add this on your authenticable modal like User.php

```
<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

```

###### Guard Setup on Config/auth.php

```
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

```

###### Generate JWT Token

```

$token = JWTAuth::fromUser($user);

$token = JWTAuth::attempt($validatedData)


```

##### Create New Route file ex:api.php,shop.php Register on app/bootstrap/app.php

```

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

```
