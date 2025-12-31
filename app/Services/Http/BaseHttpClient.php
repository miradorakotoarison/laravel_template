<?php

namespace App\Services\Http;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;

abstract class BaseHttpClient
{
    protected string $baseUrl;
    protected array $headers = [];
    protected array $defaultParams = [];
    protected int $timeout = 30;
    protected int $retryTimes = 0;
    protected int $retryDelay = 100;
    protected bool $verifySSL = true;
    protected array $serviceConfig = [];

    public function __construct()
    {
        $this->loadServiceConfig();
        $this->configure();
    }

    protected function loadServiceConfig(): void
    {
        $configKey = $this->getConfigKey();
        $this->serviceConfig = config("services.{$configKey}", []);
    }

    protected function getConfigKey(): string
    {
        $className = class_basename(static::class);
        $configKey = preg_replace('/Service$/', '', $className);
        $configKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $configKey));
        return $configKey;
    }

    abstract protected function configure(): void;

    protected function buildClient(): PendingRequest
    {
        $client = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->withHeaders($this->headers);

        if ($this->retryTimes > 0) {
            $client->retry($this->retryTimes, $this->retryDelay);
        }

        if (!$this->verifySSL) {
            $client->withoutVerifying();
        }

        return $client;
    }

    public function get(string $endpoint, array $params = []): Response
    {
        $mergedParams = array_merge($this->defaultParams, $params);
        return $this->buildClient()->get($endpoint, $mergedParams);
    }

    public function post(string $endpoint, array $data = [], array $params = []): Response
    {
        $mergedParams = array_merge($this->defaultParams, $params);
        $url = $endpoint . ($mergedParams ? '?' . http_build_query($mergedParams) : '');
        return $this->buildClient()->post($url, $data);
    }

    public function put(string $endpoint, array $data = [], array $params = []): Response
    {
        $mergedParams = array_merge($this->defaultParams, $params);
        $url = $endpoint . ($mergedParams ? '?' . http_build_query($mergedParams) : '');
        return $this->buildClient()->put($url, $data);
    }

    public function patch(string $endpoint, array $data = [], array $params = []): Response
    {
        $mergedParams = array_merge($this->defaultParams, $params);
        $url = $endpoint . ($mergedParams ? '?' . http_build_query($mergedParams) : '');
        return $this->buildClient()->patch($url, $data);
    }

    public function delete(string $endpoint, array $params = []): Response
    {
        $mergedParams = array_merge($this->defaultParams, $params);
        return $this->buildClient()->delete($endpoint, $mergedParams);
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function setDefaultParam(string $key, mixed $value): self
    {
        $this->defaultParams[$key] = $value;
        return $this;
    }

    public function setDefaultParams(array $params): self
    {
        $this->defaultParams = array_merge($this->defaultParams, $params);
        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function setRetry(int $times, int $delay = 100): self
    {
        $this->retryTimes = $times;
        $this->retryDelay = $delay;
        return $this;
    }

    public function disableSSLVerification(): self
    {
        $this->verifySSL = false;
        return $this;
    }

    public function enableSSLVerification(): self
    {
        $this->verifySSL = true;
        return $this;
    }
}
