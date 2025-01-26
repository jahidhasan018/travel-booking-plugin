<?php

namespace TravelBookingPlugin\Admin;

class AdminMenu
{
    public function add_admin_menu()
    {
        add_menu_page(
            'Travel Booking Settings',
            'Travel Booking',
            'manage_options',
            'travel-booking-plugin',
            [$this, 'render_settings_page'],
             'dashicons-location-alt', // Add a dashicon (e.g., 'dashicons-location-alt')
             60
        );
    }
    
    public function render_settings_page()
    {
       // Render the settings page
       $settingsPage = new AdminSettingsPage();
       $settingsPage->render_admin_settings_page();

    }
}