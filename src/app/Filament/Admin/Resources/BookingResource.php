<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BookingResource\Pages;
use App\Models\BilliardTable;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Booking Billiard';
    protected static ?string $navigationLabel = 'Booking';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('billiard_table_id')
                ->label('Meja')
                ->options(BilliardTable::query()->with('floor')->get()->mapWithKeys(fn ($table) => [
                    $table->id => $table->floor->name . ' - ' . ($table->room_name ?: $table->name),
                ]))
                ->searchable()
                ->required(),
            Forms\Components\DatePicker::make('booking_date')->label('Tanggal')->required(),
            Forms\Components\TimePicker::make('start_time')->label('Jam Mulai')->seconds(false)->required(),
            Forms\Components\TimePicker::make('end_time')->label('Jam Selesai')->seconds(false)->required(),
            Forms\Components\TextInput::make('customer_name')->label('Nama Pembeli')->required()->maxLength(100),
            Forms\Components\TextInput::make('phone_number')->label('Nomor Telepon')->required()->maxLength(30),
            Forms\Components\Select::make('payment_method')->label('Pembayaran')->options([
                'cash' => 'Offline / Cash',
                'transfer' => 'Cashless / Transfer',
            ])->required(),
            Forms\Components\Select::make('status')->options([
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'cancelled' => 'Cancelled',
            ])->required()->default('pending'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_date')->label('Tanggal')->date()->sortable(),
                Tables\Columns\TextColumn::make('billiardTable.floor.name')->label('Lantai')->sortable(),
                Tables\Columns\TextColumn::make('billiardTable.name')->label('Meja')->searchable(),
                Tables\Columns\TextColumn::make('customer_name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('phone_number')->label('No. HP')->searchable(),
                Tables\Columns\TextColumn::make('start_time')->label('Mulai'),
                Tables\Columns\TextColumn::make('end_time')->label('Selesai'),
                Tables\Columns\TextColumn::make('payment_method')->label('Bayar')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'confirmed' => 'success',
                    'cancelled' => 'danger',
                    default => 'warning',
                }),
            ])
            ->defaultSort('booking_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'cancelled' => 'Cancelled',
                ]),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
