<?php

namespace App\Managers\Providers;

interface ProviderInterface
{
    public function fetchSkills(array $params = []);
    
    public function getProviderName(): string;
}
