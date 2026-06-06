<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BilliardTableResource\Pages;
use App\Models\BilliardTable;
use App\Models\Floor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BilliardTableResource extends Resource
{
    protected static ?string $model = BilliardTable::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Booking Billiard';
    protected static ?string $navigationLabel = 'Meja Billiard';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('floor_id')
                ->label('Lantai')
                ->options(Floor::query()->orderBy('floor_number')->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('name')->label('Nama Meja')->required()->maxLength(255),
            Forms\Components\TextInput::make('room_name')->label('Nama Ruangan / VIP')->maxLength(255),
            Forms\Components\TextInput::make('position_label')->label('Posisi Tampilan')->placeholder('Kiri depan / Tengah / VIP 1')->maxLength(255),
            Forms\Components\Textarea::make('description')->label('Catatan Meja')->rows(3)->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('floor.name')->label('Lantai')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Meja')->searchable(),
                Tables\Columns\TextColumn::make('room_name')->label('Ruangan')->searchable(),
                Tables\Columns\TextColumn::make('position_label')->label('Posisi'),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('floor_id')->label('Lantai')->relationship('floor', 'name'),
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
            'index' => Pages\ListBilliardTables::route('/'),
            'create' => Pages\CreateBilliardTable::route('/create'),
            'edit' => Pages\EditBilliardTable::route('/{record}/edit'),
        ];
    }
}
