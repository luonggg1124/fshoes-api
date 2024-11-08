<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider::class,
    Kreait\Laravel\Firebase\ServiceProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
    Srmklive\PayPal\Providers\PayPalServiceProvider::class,
];
