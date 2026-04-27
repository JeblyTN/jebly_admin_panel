<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use App\Helpers\FirestoreHelper;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        setcookie("XSRF-TOKEN-AK", bin2hex(config("firebase.apikey", "")), time() + 3600, "/");
        setcookie("XSRF-TOKEN-AD", bin2hex(config("firebase.auth_domain", "")), time() + 3600, "/");
        setcookie("XSRF-TOKEN-DU", bin2hex(config("firebase.database_url", "")), time() + 3600, "/");
        setcookie("XSRF-TOKEN-PI", bin2hex(config("firebase.project_id", "")), time() + 3600, "/");
        setcookie("XSRF-TOKEN-SB", bin2hex(config("firebase.storage_bucket", "")), time() + 3600, "/");
        setcookie("XSRF-TOKEN-MS", bin2hex(config("firebase.messaging_sender_id", "")), time() + 3600, "/");
        setcookie("XSRF-TOKEN-AI", bin2hex(config("firebase.app_id", "")), time() + 3600, "/");
        setcookie("XSRF-TOKEN-MI", bin2hex(config("firebase.measurement_id", "")), time() + 3600, "/");
    }

    public function boot()
    {
        $countries_data = [];
        $get_countries_json = file_get_contents(public_path("countriesdata.json"));
        if ($get_countries_json != "") {
            $countries_data = json_decode($get_countries_json);
        }

        $openai_settings = FirestoreHelper::getDocument("settings/openai_settings");
        if (!empty($openai_settings)) {
            if (!empty($openai_settings["api_key"])) {
                Config::set("openai.api_key", $openai_settings["api_key"]);
            }
            if (!empty($openai_settings["organization"])) {
                Config::set("openai.organization", $openai_settings["organization"]);
            }
        }

        view()->composer("*", function ($view) use ($countries_data, $openai_settings) {
            $view->with("countries_data", $countries_data);
            $view->with("openai_settings", $openai_settings);
        });
    }
}

