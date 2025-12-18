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
        'sharp' => \App\Service\SharpApiService::class,
        'sharpapi' => \App\Service\SharpApiService::class,
        'sharpid' => \App\Service\SharpApiService::class,
        
        // Add more providers here as needed
        // 'another_provider' => \App\Service\AnotherProviderService::class,
    ],
];
