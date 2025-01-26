<?php

namespace TravelBookingPlugin\Core;

class Plugin
{
    protected $activator;
    protected $deactivator;
    protected $hooks;

    public function __construct()
    {
        $this->activator = new Activator();
        $this->deactivator = new Deactivator();
        $this->hooks = new Hooks();

        //Initialize Admin Settings Page
         new \TravelBookingPlugin\Admin\AdminSettingsPage();
    }


    public function run()
    {
        // Register activation and deactivation hooks.
        register_activation_hook(TRAVEL_BOOKING_PLUGIN_FILE, [$this->activator, 'activate']);
        register_deactivation_hook(TRAVEL_BOOKING_PLUGIN_FILE, [$this->deactivator, 'deactivate']);

        // Initialize the hooks.
        $this->hooks->init();

    }


    // Method to define the main plugin file path
    public static function plugin_file(): string
    {
        return TRAVEL_BOOKING_PLUGIN_FILE;
    }
}