<?php

namespace App\Managers\Providers;

use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class ProviderResolver
{
    protected array $providerMap = [];

    public function __construct()
    {
        $this->providerMap = config('providers.mapping', []);
    }

    public function resolve(string $providerId): ProviderInterface
    {
        $normalizedId = strtolower($providerId);
        
        if (!isset($this->providerMap[$normalizedId])) {
            throw new InvalidArgumentException("Unknown provider: {$providerId}");
        }

        $serviceClass = $this->providerMap[$normalizedId];

        if (!class_exists($serviceClass)) {
            throw new InvalidArgumentException("Provider service class not found: {$serviceClass}");
        }

        $service = App::make($serviceClass);

        if (!$service instanceof ProviderInterface) {
            throw new InvalidArgumentException("Provider service must implement ProviderInterface");
        }

        return $service;
    }

    public function getAvailableProviders(): array
    {
        return array_keys($this->providerMap);
    }

    public function hasProvider(string $providerId): bool
    {
        return isset($this->providerMap[strtolower($providerId)]);
    }
}
