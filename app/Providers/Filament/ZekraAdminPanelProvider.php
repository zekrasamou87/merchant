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
use Illuminate\Support\HtmlString; 
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ZekraAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('zekra_admin')
            ->path('zekra_admin')
            ->login()
            ->brandName('مبادرة التاجر الملتزم')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('8rem') 
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => '#1b5e20',
                'gold' => '#d4af37',
            ])
            ->font('Almarai') 
            ->renderHook(
                'panels::head.end',
                fn () => new HtmlString('
                    <style>
                        .fi-sidebar-header {
                            padding-top: 1.5rem !important;
                            padding-bottom: 1.5rem !important;
                            height: auto !important;
                        }
                        .fi-logo {
                            display: flex !important;
                            flex-direction: column !important;
                            align-items: center !important;
                            gap: 0.75rem !important;
                        }
                        .fi-logo img {
                            height: 6.5rem !important;
                            width: auto !important;
                            object-fit: contain !important;
                            margin: 0 auto !important;
                        }
                        .fi-sidebar-header-active {
                            box-shadow: none !important;
                        }
                        /* تنسيق صندوق الشرح */
                        .initiative-banner {
                            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
                            color: white;
                            padding: 1.5rem;
                            border-radius: 1rem;
                            margin-bottom: 2rem;
                            border-right: 6px solid #d4af37;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                        }
                    </style>
                ')
            )
            /* إضافة الشرح في بداية محتوى الصفحة */
            ->renderHook(
                'panels::content.start',
                fn () => new HtmlString('
                    <div class="initiative-banner">
                        <h2 class="text-xl font-bold mb-2">🌿 رؤية المبادرة:</h2>
                        <p class="text-sm leading-relaxed opacity-90">
                            مبادرة <b>"التاجر الملتزم"</b>  هي خطوة نحو تنظيم السوق المحلي وتعزيز مبدأ الشفافية. 
                            نسعى من خلال هذه المنصة إلى توثيق التجار الملتزمين بالضوابط والمعايير، وبناء جسر من الثقة 
                            يضمن حقوق المستهلك ويدعم النشاط التجاري النزيه في بلدنا.
                        </p>
                    </div>
                ')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
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
            ]);
    }
}