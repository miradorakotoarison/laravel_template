<?php

namespace App\Managers\Service;

use App\Managers\Http\BaseHttpClient;
use App\Managers\Providers\ProviderInterface;

class SharpApiService extends BaseHttpClient implements ProviderInterface
{
    protected function configure(): void
    {
        $this->baseUrl = $this->serviceConfig['base_url'] ?? '';
        
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . ($this->serviceConfig['api_key'] ?? ''),
        ];

        $this->defaultParams = [
            'version' => $this->serviceConfig['version'] ?? 'v1',
        ];

        $this->timeout = $this->serviceConfig['timeout'] ?? 30;
        $this->retryTimes = $this->serviceConfig['retry_times'] ?? 3;
        $this->retryDelay = $this->serviceConfig['retry_delay'] ?? 100;
    }

    public function fetchSkills(array $params = [])
    {
        $path = $this->serviceConfig['skills_path'] ?? '/skills';
        return $this->get($path, $params);
    }

    public function getProviderName(): string
    {
        $providerName = substr(static::class, strrpos(static::class, '\\') + 1);
        return rtrim($providerName, 'Service');
    }
}
