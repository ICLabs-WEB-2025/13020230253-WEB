<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentApplicationResource\Pages;
use App\Models\AgentApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table; // Impor namespace yang benar untuk Table
use Illuminate\Support\Facades\Storage;

class AgentApplicationResource extends Resource
{
    protected static ?string $model = AgentApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nik')->required(),
                Forms\Components\TextInput::make('address')->required(),
                Forms\Components\TextInput::make('phone')->required(),
                Forms\Components\FileUpload::make('document_path')
                    ->required()
                    ->disk('public')
                    ->directory('agent_documents'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('nik'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
                Tables\Columns\TextColumn::make('document_path')
                    ->formatStateUsing(fn ($state) => $state ? '<a href="' . Storage::url($state) . '" target="_blank">View Document</a>' : '')
                    ->html()
                    ->label('Document'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected']),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->action(function (AgentApplication $record) {
                        $record->user->update(['role' => 'agent', 'is_approved' => true]);
                        $record->update(['status' => 'Approved']);
                    }),
                Tables\Actions\Action::make('reject')
                    ->action(function (AgentApplication $record) {
                        $record->update(['status' => 'Rejected']);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgentApplications::route('/'),
        ];
    }
}