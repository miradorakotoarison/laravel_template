<?php

namespace App\Services\Sharp;

use App\Services\Http\BaseHttpClient;
use App\Services\Interfaces\CommonInterface;

class CustomerService extends BaseHttpClient implements CommonInterface
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

    public function doSomething()
    {
        // do something (overload)
    }
}
