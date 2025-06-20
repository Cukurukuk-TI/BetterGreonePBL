<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // 2. Tambahkan method navigationGroups di sini
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Manajemen Toko'),
                NavigationGroup::make()
                    ->label('Manajemen Konten'),
                NavigationGroup::make()
                    ->label('Manajemen Pengguna'),
            ])
            // 3. Tambahkan method navigationItems di sini
            ->navigationItems([
                NavigationItem::make('Produk')
                    ->url('#') // URL # berarti belum ada link
                    ->icon('heroicon-o-shopping-bag')
                    ->group('Manajemen Toko'),
                NavigationItem::make('Pesanan')
                    ->url('#')
                    ->icon('heroicon-o-shopping-cart')
                    ->group('Manajemen Toko'),
                NavigationItem::make('Kategori')
                    ->url('#')
                    ->icon('heroicon-o-tag')
                    ->group('Manajemen Toko'),
                NavigationItem::make('Promo')
                    ->url('#')
                    ->icon('heroicon-o-sparkles')
                    ->group('Manajemen Toko'),

                NavigationItem::make('Artikel')
                    ->url('#')
                    ->icon('heroicon-o-document-text')
                    ->group('Manajemen Konten'),
                NavigationItem::make('Testimoni')
                    ->url('#')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->group('Manajemen Konten'),

                // NavigationItem::make('Daftar Pelanggan')
                //     ->url('#')
                //     ->icon('heroicon-o-users')
                //     ->group('Manajemen Pengguna'),
            ]);
    }
}

