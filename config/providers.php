<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Provider Mapping
    |--------------------------------------------------------------------------
    |
    | This configuration maps provider IDs to their corresponding service classes.
    | When importing skills, the system will automatically resolve the correct
    | provider service based on the provider ID.
    |
    */

    'mapping' => [
        'sharp' => \App\Managers\Service\SharpApiService::class,
        'sharpapi' => \App\Managers\Service\SharpApiService::class,
        'sharpid' => \App\Managers\Service\SharpApiService::class,  
        
        // Add more providers here as needed
        // 'another_provider' => \App\Service\AnotherProviderService::class,
    ],
];
