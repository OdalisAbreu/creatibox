<?php

namespace App\View\Composers;

use App\Models\PlatformSetting;
use Illuminate\View\View;

class BrandingComposer
{
    public function compose(View $view): void
    {
        static $settings = null;
        static $loaded = false;

        if (!$loaded) {
            $settings = PlatformSetting::first();
            $loaded = true;
        }

        $view->with('platformSettings', $settings);
    }
}
