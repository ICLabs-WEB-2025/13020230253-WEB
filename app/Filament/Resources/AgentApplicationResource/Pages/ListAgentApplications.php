<?php

namespace App\Filament\Resources\AgentApplicationResource\Pages;

use App\Filament\Resources\AgentApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgentApplications extends ListRecords
{
    protected static string $resource = AgentApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada CreateAction karena aplikasi agen biasanya dibuat oleh pengguna
        ];
    }
}