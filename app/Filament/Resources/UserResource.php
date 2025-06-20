<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Daftar Pelanggan';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 1; // Urutan di dalam grup

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_admin')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan ID, dan bisa di-sort
                Tables\Columns\TextColumn::make('id')->sortable(),

                // Menampilkan Nama, dan bisa dicari
                Tables\Columns\TextColumn::make('name')->searchable(),

                // Menampilkan Email, dan bisa dicari
                Tables\Columns\TextColumn::make('email')->searchable(),

                // Menampilkan status verifikasi email dengan ikon
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Terverifikasi')
                    ->boolean()
                    ->sortable(),

                // Menampilkan status admin dengan ikon
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin?')
                    ->boolean(),

                // Menampilkan kapan akun dibuat
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i') // Format tanggal agar mudah dibaca
                    ->sortable(),
            ])
            ->filters([
                // Filter akan kita tambahkan nanti
            TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Kita hanya tambahkan aksi untuk melihat detail
                Tables\Actions\DeleteAction::make(), // Ini akan otomatis menjadi soft delete
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // Ini juga otomatis soft delete
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

}
