<?php

namespace TravelBookingPlugin\Api;


class ApiManager {
    private $api_keys;

    public function __construct() {
        $this->load_api_keys();
    }


    private function load_api_keys(): void
    {
         $this->api_keys['flights'] = get_option('flights_api_settings', [])['flights_api_key'] ?? null;
          $this->api_keys['cars'] = get_option('cars_api_settings', [])['cars_api_key'] ?? null;
          $this->api_keys['activities'] = get_option('activities_api_settings', [])['activities_api_key'] ?? null;
         $this->api_keys['stays'] = get_option('stays_api_settings', [])['stays_api_key'] ?? null;
    }

    public function get_api_key(string $module)
    {
       return $this->api_keys[$module] ?? null;
    }

   // Function to check if module is enabled
    public function is_module_enabled(string $module): bool
    {
        $enabled_widgets = get_option('widget_settings', [])['enable_widgets'] ?? [];
        return in_array($module, $enabled_widgets);
    }
}