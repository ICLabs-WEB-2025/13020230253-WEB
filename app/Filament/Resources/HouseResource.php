<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResource\Pages;
use App\Models\House;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table; // Impor namespace yang benar untuk Table
use Illuminate\Support\Facades\Storage;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('price')->required()->numeric(),
                Forms\Components\Select::make('status')->options(['Tersedia' => 'Tersedia', 'Dalam Proses' => 'Dalam Proses', 'Terjual' => 'Terjual'])->required(),
                Forms\Components\FileUpload::make('photo_path')
                    ->required()
                    ->image()
                    ->disk('public')
                    ->directory('house_photos'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent.name'),
                Tables\Columns\ImageColumn::make('photo_path'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['Tersedia' => 'Tersedia', 'Dalam Proses' => 'Dalam Proses', 'Terjual' => 'Terjual']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'edit' => Pages\EditHouse::route('/{record}/edit'),
        ];
    }
}