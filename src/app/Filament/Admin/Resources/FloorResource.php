<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FloorResource\Pages;
use App\Models\Floor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FloorResource extends Resource
{
    protected static ?string $model = Floor::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Booking Billiard';
    protected static ?string $navigationLabel = 'Lantai';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Lantai')->schema([
                Forms\Components\TextInput::make('name')->label('Nama Lantai')->required()->maxLength(255),
                Forms\Components\TextInput::make('floor_number')->label('Nomor Lantai')->numeric()->required()->unique(ignoreRecord: true),
                Forms\Components\Toggle::make('is_vip')->label('Area VIP'),
                Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Informasi untuk Pembeli')->schema([
                Forms\Components\Textarea::make('description')->label('Deskripsi Awal')->rows(3),
                Forms\Components\Textarea::make('table_specification')->label('Spesifikasi Meja')->rows(3),
                Forms\Components\Textarea::make('cue_specification')->label('Spesifikasi Stick')->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('floor_number')->label('Lantai')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\IconColumn::make('is_vip')->label('VIP')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diubah')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFloors::route('/'),
            'create' => Pages\CreateFloor::route('/create'),
            'edit' => Pages\EditFloor::route('/{record}/edit'),
        ];
    }
}
