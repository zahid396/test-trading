<?php

namespace App\Providers;

use App\Models\PaymentSetting;
use App\Models\SocialLink;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $storeSettings = Cache::remember('shop.store_settings', 3600, function () {
                return StoreSetting::pluck('value', 'key')->all();
            });

            $paymentMethodData = Cache::remember('shop.payment_methods', 3600, function () {
                return PaymentSetting::query()
                    ->get(['id', 'method', 'number', 'account_type', 'instructions', 'qr_image', 'logo', 'is_active'])
                    ->map->getAttributes()
                    ->keyBy('method')
                    ->all();
            });

            $socialLinkData = Cache::remember('shop.social_links', 3600, function () {
                return SocialLink::active()
                    ->orderBy('sort_order')
                    ->get(['id', 'platform', 'label', 'url', 'icon', 'is_active', 'sort_order'])
                    ->map->getAttributes()
                    ->all();
            });

            $paymentMethods = collect($paymentMethodData)->map(fn (array $attrs) => (new PaymentSetting())->forceFill($attrs))->keyBy('method');
            $socialLinks = collect($socialLinkData)->map(fn (array $attrs) => (new SocialLink())->forceFill($attrs));

            $view->with('storeSettings', $storeSettings);
            $view->with('paymentMethods', $paymentMethods);
            $view->with('socialLinks', $socialLinks);
        });
    }
}
