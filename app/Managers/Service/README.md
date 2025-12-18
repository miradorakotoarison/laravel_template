# HTTP Client Architecture

This directory contains a flexible HTTP client architecture for making API calls to external services.

## Architecture Overview

### BaseHttpClient (Abstract Base Class)
The `BaseHttpClient` is an abstract class that provides all the core functionality for making HTTP requests. It handles:
- HTTP methods (GET, POST, PUT, PATCH, DELETE)
- Headers management
- Query parameters
- Timeout configuration
- Retry logic
- SSL verification
- Base URL configuration
- **Automatic configuration loading** based on class name

### Automatic Configuration Loading
The `BaseHttpClient` automatically loads configuration from `config/services.php` based on the service class name:
- Class name: `SharpApiService` → Config key: `services.sharp_api_service`
- Class name: `AnotherProviderService` → Config key: `services.another_provider_service`
- Class name: `MyCustomService` → Config key: `services.my_custom_service`

The configuration is available in child classes via the `$this->serviceConfig` array.

### Child Classes
Child classes extend `BaseHttpClient` and override the `configure()` method to set up specific API configurations using `$this->serviceConfig`.

## Usage

### 1. Create a New API Client

Create a new class that extends `BaseHttpClient`:

```php
<?php

namespace App\Service\HttpClient;

class MyApiClient extends BaseHttpClient
{
    protected function configure(): void
    {
        // Configuration is automatically loaded into $this->serviceConfig
        // Based on class name: MyApiClient -> services.my_api_client
        
        $this->baseUrl = $this->serviceConfig['base_url'] ?? '';
        
        $this->headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . ($this->serviceConfig['api_key'] ?? ''),
        ];

        $this->defaultParams = [
            'format' => 'json',
        ];

        $this->timeout = $this->serviceConfig['timeout'] ?? 30;
        $this->retryTimes = $this->serviceConfig['retry_times'] ?? 3;
        $this->retryDelay = $this->serviceConfig['retry_delay'] ?? 100;
    }

    // Add your custom methods
    public function getData(int $id)
    {
        return $this->get("/data/{$id}");
    }
}
```

### 2. Add Configuration

Add your API configuration to `config/services.php`:

```php
'my_api' => [
    'base_url' => env('MY_API_BASE_URL'),
    'api_key' => env('MY_API_KEY'),
    'timeout' => env('MY_API_TIMEOUT', 30),
],
```

### 3. Add Environment Variables

Add the required environment variables to `.env`:

```env
MY_API_BASE_URL=https://api.myservice.com
MY_API_KEY=your_api_key_here
MY_API_TIMEOUT=30
```

### 4. Use the Client

```php
use App\Service\HttpClient\MyApiClient;

class SomeController extends Controller
{
    public function index(MyApiClient $client)
    {
        // Make a GET request
        $response = $client->get('/endpoint', ['param' => 'value']);
        
        // Make a POST request
        $response = $client->post('/endpoint', ['data' => 'value']);
        
        // Use custom methods
        $response = $client->getData(123);
        
        // Get response data
        $data = $response->json();
        $status = $response->status();
        $successful = $response->successful();
    }
}
```

## Available Methods

### HTTP Methods
- `get(string $endpoint, array $params = [])`
- `post(string $endpoint, array $data = [], array $params = [])`
- `put(string $endpoint, array $data = [], array $params = [])`
- `patch(string $endpoint, array $data = [], array $params = [])`
- `delete(string $endpoint, array $params = [])`

### Configuration Methods
- `setHeader(string $key, string $value)` - Set a single header
- `setHeaders(array $headers)` - Set multiple headers
- `setDefaultParam(string $key, mixed $value)` - Set a default query parameter
- `setDefaultParams(array $params)` - Set multiple default parameters
- `setTimeout(int $timeout)` - Set request timeout in seconds
- `setRetry(int $times, int $delay = 100)` - Configure retry logic
- `disableSSLVerification()` - Disable SSL verification (not recommended for production)
- `enableSSLVerification()` - Enable SSL verification

### Dynamic Configuration Example

```php
$client = new MyApiClient();
$client->setHeader('X-Custom-Header', 'value')
       ->setTimeout(60)
       ->setRetry(5, 200);

$response = $client->get('/endpoint');
```

## Examples

### Example 1: Simple API Client

```php
class WeatherApiClient extends BaseHttpClient
{
    protected function configure(): void
    {
        $this->baseUrl = config('services.weather_api.base_url');
        $this->headers = [
            'X-API-Key' => config('services.weather_api.api_key'),
        ];
    }

    public function getCurrentWeather(string $city)
    {
        return $this->get('/current', ['city' => $city]);
    }
}
```

### Example 2: API with Authentication

```php
class AuthenticatedApiClient extends BaseHttpClient
{
    protected function configure(): void
    {
        $this->baseUrl = config('services.auth_api.base_url');
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Accept' => 'application/json',
        ];
        $this->timeout = 60;
        $this->retryTimes = 3;
    }

    private function getAccessToken(): string
    {
        // Logic to get or refresh access token
        return cache()->remember('api_token', 3600, function () {
            // Get token from somewhere
            return 'token';
        });
    }
}
```

## Benefits

1. **Reusability**: Write the HTTP logic once, reuse it everywhere
2. **Maintainability**: All API configurations in one place
3. **Flexibility**: Easy to override any behavior in child classes
4. **Environment-based**: Different configurations for dev/staging/production
5. **Type Safety**: Clear method signatures and return types
6. **Testability**: Easy to mock and test

## Testing

```php
use App\Service\HttpClient\MyApiClient;
use Illuminate\Support\Facades\Http;

test('it fetches data successfully', function () {
    Http::fake([
        'api.myservice.com/*' => Http::response(['data' => 'value'], 200)
    ]);

    $client = new MyApiClient();
    $response = $client->getData(123);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toBe(['data' => 'value']);
});
```
