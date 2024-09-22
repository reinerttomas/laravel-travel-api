# Laravel Consuming API

This project shows how to create APIs in Laravel for a travel application.

## Features

* ✅ Laravel 11
* ✅ Laravel prompts (beautiful CLI)
* ✅ API versioning
* ✅ API documentation with [Scramble](https://scramble.dedoc.co/)
* ✅ Actions
* ✅ Custom query builders
* ✅ Data transfer objects
* ✅ Value objects
* ✅ PHPStan
* ✅ Rector
* ✅ Laravel Pint (PHP Coding Standards Fixer)
* ✅ Pest (testing)

## Installation

Install dependencies using Composer

```
composer install
```

Create your .env file from example

```
cp .env.example .env
```

## Commands

### CreateUserCommand

To create a user we use the CLI command. Thanks to the package `laravel/prompts` we have beautiful and clear commands.

```php
final class CreateUserCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * @var string
     */
    protected $description = 'Creates new user';

    public function __construct(
        private readonly CreateUserAction $createUserAction,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $data = form()
            ->text(
                label: 'What is your name?',
                required: true,
                validate: ['required', 'string', 'max:255'],
                name: 'name',
            )
            ->text(
                label: 'What is your email?',
                required: true,
                validate: ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                name: 'email',
            )
            ->password(
                label: 'What is your password?',
                required: true,
                validate: ['password' => Password::default()],
                hint: 'Minimum 8 characters.',
                name: 'password',
            )
            ->select(
                label: 'What role should the user have?',
                options: ['admin', 'editor'],
                name: 'role',
            )
            ->submit();

        $role = Role::whereName($data['role'])->first();

        if ($role === null) {
            $this->error('Role not found');

            return -1;
        }

        $user = $this->createUserAction->execute(
            new CreateUserData(
                $data['name'],
                $data['email'],
                $data['password'],
                $role,
            )
        );

        info(sprintf('User "%s" created successfully.', $user->email));

        return 1;
    }
}
```

## Actions

Actions in Laravel are separate classes that encapsulate one specific task or part of the business logic of an application. They are part of a concept that seeks to improve code organization and adhere to the Single Responsibility Principle.

Action class should have one public method execute, run, handle. The name is up to you.

### Create access token

Verify login credentials and create an access token. It returns token as value object.

```php
final readonly class CreateAccessTokenAction
{
    public function execute(User $user, CreateAccessTokenData $data): AccessToken
    {
        $attempt = Auth::attempt([
            'email' => $data->email,
            'password' => $data->password,
        ]);

        if (! $attempt) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return new AccessToken(
            $user->createToken($this->device($data->userAgent))->plainTextToken
        );
    }

    private function device(?string $userAgent): string
    {
        return substr($userAgent ?? '', 0, 255);
    }
}
```

### Create user

we use database transactions because we perform 2 operations:

- creating user
- attaching role to user

```php
final readonly class CreateUserAction
{
    public function execute(CreateUserData $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
            ]);
            $user->roles()->attach($data->role->id);

            return $user;
        });
    }
}
```

## Query builders

Personally, I don't really like the scope inside the models. A simple solution is a custom query builder.

## Travel builder

In model:

```php
final class Travel extends Model
{
    // ...
    
    public function newEloquentBuilder($query): TravelBuilder
    {
        return new TravelBuilder($query);
    }
    
    // ...
}
```

Custom query builder:

```php
/**
 * @extends Builder<Travel>
 */
final class TravelBuilder extends Builder
{
    public function wherePublic(bool $isPublic = true): self
    {
        return $this->where('is_public', $isPublic);
    }
}
```

## Testing

For tests, it uses a [pest](https://pestphp.com/). Several tests are created for each endpoint to ensure proper functioning. I'll just give you a few examples.

### Login

```php
it('returns token with valid credentials', function (): void {
    // Arrange
    $user = User::factory()->create();

    $data = [
        'email' => $user->email,
        'password' => 'password',
    ];

    // Act & Assert
    api()->v1()->post('/login', $data)
        ->assertCreated()
        ->assertJsonStructure([
            'access_token',
        ]);
});

it('returns errors with invalid credentials', function (): void {
    // Arrange
    $data = [
        'email' => 'nonexisting@user.com',
        'password' => 'password',
    ];

    // Act & Assert
    api()->v1()->post('/login', $data)
        ->assertUnprocessable();
});
```

### Travel

```php
it('returns tours of travel by slug', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    $tour = Tour::factory()->create([
        'travel_id' => $travel->id,
    ]);

    // Act & Assert
    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->has('data', 1)
            ->has('data.0', fn (AssertableJson $json): AssertableJson => $json
                ->where('id', $tour->id)
                ->etc()
            )
            ->etc()
        );
});

it('shows tour price correctly', function (): void {
    // Arrange
    $travel = Travel::factory()->create();
    $tour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 123.45,
    ]);

    // Act & Assert
    expect(api()->v1()->get('/travels/' . $travel->slug . '/tours'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->has('data', 1)
            ->has('data.0', fn (AssertableJson $json): AssertableJson => $json
                ->where('price', '123.45')
                ->whereType('price', 'string')
                ->etc()
            )
            ->etc()
        );
});
```

### Extensions

Because I didn't want to keep typing in the tests endpoints like: 

`/api/v1/travels` 

So I created a helper classes then can replace typing with:

`api()->v1()->get('/travels/' . $travel->slug . '/tours')`

#### Api

```php
final readonly class Api
{
    public function __construct(
        private string $prefix = '/api',
    ) {}

    public function v1(): Http
    {
        return $this->client('v1');
    }

    public function v2(): Http
    {
        return $this->client('v2');
    }

    private function client(string $version): Http
    {
        return new Http($this->prefix . '/' . $version);
    }
}
```

#### Http

```php
final readonly class Http
{
    public function __construct(
        private string $prefix
    ) {}

    public function endpoint(string $uri): string
    {
        return $this->prefix . $uri;
    }

    public function get(string $uri): TestResponse
    {
        return get($this->prefix . $uri);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function post(string $uri, array $data = []): TestResponse
    {
        return post($this->prefix . $uri, $data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function put(string $uri, array $data = []): TestResponse
    {
        return put($this->prefix . $uri, $data);
    }

    public function delete(string $uri): TestResponse
    {
        return delete($this->prefix . $uri);
    }
}
```
