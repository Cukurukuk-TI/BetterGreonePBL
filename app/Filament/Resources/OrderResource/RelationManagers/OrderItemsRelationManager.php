<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $pluralModelLabel = 'Item Pesanan';
    protected static ?string $modelLabel = 'Item Pesanan';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

        ->recordTitleAttribute('id')
            ->columns([
                ImageColumn::make('product.image_path')->label('Gambar'),
                TextColumn::make('product.name')->label('Nama Produk')->searchable(),
                TextColumn::make('quantity')->label('Kuantitas'),
                TextColumn::make('price')->label('Harga Satuan')->money('IDR'),
                // Kolom Subtotal yang dihitung otomatis
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->state(function ($record) {
                        return $record->quantity * $record->price;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([

                ])
            ->actions([

                ])
            ->bulkActions([

            ]);
    }
}
