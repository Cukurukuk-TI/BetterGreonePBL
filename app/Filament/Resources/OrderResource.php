<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag'; // Ikon untuk menu
    protected static ?string $navigationLabel = 'Pesanan'; // Label teks untuk menu
    protected static ?string $modelLabel = 'Pesanan'; // Label untuk judul halaman
    protected static ?string $pluralModelLabel = 'Pesanan'; // Label untuk judul halaman (jamak)
    protected static ?string $navigationGroup = 'Manajemen Toko'; // Mengelompokkan menu
    protected static ?int $navigationSort = 2; // Urutan di sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Info Pesanan')
                    ->schema([
                        TextInput::make('id')->label('Order ID')->disabled(),
                        TextInput::make('user.name')->label('Nama Pelanggan')->disabled(),
                        TextInput::make('grand_total')->label('Total Harga')->prefix('Rp')->numeric(0, ',', '.')->disabled(),
                        TextInput::make('shipping_address')->label('Alamat Pengiriman')->disabled(),
                    ])->columns(2),

                Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])->required(),
                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order ID')->sortable(),
                TextColumn::make('user.name')->label('Pelanggan')->searchable()->sortable(),
                TextColumn::make('grand_total')->label('Total Harga')->money('IDR')->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'warning' => 'processing',
                        'success' => 'shipped',
                        'primary' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                BadgeColumn::make('payment_status')
                    ->colors([
                        'secondary' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
                TextColumn::make('created_at')->label('Tanggal Pesan')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
