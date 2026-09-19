<?php

namespace App\Providers;

use App\Helpers\Helpers;
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
//        if(isset($_GET['website'])) {
//            header('HTTP/1.1 301 Moved Permanently');
//            header('Location: '.env('APP_URL'));
//            exit();
//        }

//        if(env('APP_ENV') == 'local'){
//            echo '<script>
//                if(window.location.hostname !== "'.env('DOMAIN_LOCAL').'"){
//                    window.location.href = "'.env('APP_URL').'/?website=local";
//                }
//            </script>';
//        }else{
//            echo '<script>
//                if(window.location.hostname !== "www.'.env('DOMAIN_RUN').'"){
//                    alert("1");
//                    window.location.href = "'.env('APP_URL').'/?website=live";
//                }else if(window.location.hostname !== "'.env('DOMAIN_RUN').'"){{
//                    alert("2");
//                    window.location.href = "'.env('APP_URL').'/?website=live";
//                }
//            </script>';
//        }

//        if (isset($_SERVER['HTTP_REFERER'])) {
//            $allowed_domains = ['novelfe', 'dicnovel', 'facebook', 'twitter', 'fb.com', 'youtube', 'linkedin', 'pinterest', 'instagram', 'flickr', 'tumblr', 'google'];
//            $referer = $_SERVER['HTTP_REFERER'];
//
//            if ($referer) {
//                $allowed = false;
//                foreach ($allowed_domains as $domain) {
//                    if (strpos($referer, $domain) !== false) {
//                        $allowed = true;
//                        break;
//                    }
//                }
//
//                if (!$allowed) {
//                    header('HTTP/1.0 403 Forbidden');
//                    exit('Access denied');
//                }
//            }
//        }
//        $method = request()->method();

        if (in_array(env('APP_ENV'), ['production'])) {
            \URL::forceScheme('https');
        }
    }
}
