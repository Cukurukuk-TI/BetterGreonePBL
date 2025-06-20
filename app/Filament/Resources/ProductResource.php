<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationGroup = 'Manajemen Toko';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kolom untuk memilih Kategori
                Select::make('category_id')
                    ->relationship('category', 'name') // Mengambil data dari relasi 'category'
                    ->required()
                    ->label('Kategori'),

                // Kolom untuk Nama dan Slug
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                // Kolom untuk upload gambar
                FileUpload::make('image_path')
                    ->label('Gambar Produk')
                    ->image() // Memastikan yang diupload adalah gambar
                    ->directory('product-images') // Simpan di folder storage/app/public/product-images
                    ->imageEditor(), // Menambahkan editor gambar sederhana

                // Kolom untuk harga dan stok
                TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                TextInput::make('stock')
                    ->label('Stok')
                    ->required()
                    ->numeric()
                    ->default(0),

                // Kolom untuk deskripsi
                RichEditor::make('description')
                    ->label('Deskripsi Produk')
                    ->columnSpanFull(), // Membuat field ini memanjang penuh
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')->label('Gambar'),
                TextColumn::make('name')->label('Nama Produk')->searchable(),
                TextColumn::make('category.name')->label('Kategori')->sortable(),
                TextColumn::make('price')->label('Harga')->money('IDR')->sortable(),
                TextColumn::make('stock')->label('Stok')->sortable(),
            ])
            ->filters([
                // Menambahkan filter berdasarkan kategori
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Aksi Edit dan Delete akan kita tambahkan di commit selanjutnya
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
