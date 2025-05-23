<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Http\Middleware\Authenticate;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->middleware([
                Authenticate::class,
                'admin', // Gunakan nama middleware yang terdaftar
            ])
            ->resources([
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\AgentApplicationResource::class,
                \App\Filament\Resources\HouseResource::class,
            ]);
    }
}