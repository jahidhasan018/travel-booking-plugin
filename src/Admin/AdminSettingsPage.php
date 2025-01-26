<?php

namespace TravelBookingPlugin\Admin;

class AdminSettingsPage
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings()
    {
        // Register settings for different APIs
        $this->register_api_settings('flights', 'Flights');
        $this->register_api_settings('cars', 'Cars');
        $this->register_api_settings('activities', 'Activities');
        $this->register_api_settings('stays', 'Stays');

        // Register settings for widgets.
        $this->register_widget_settings();
    }

    private function register_api_settings(string $key, string $label)
    {
        register_setting(
            'travel_booking_settings',
            "{$key}_api_settings",
            [$this, 'sanitize_api_settings']
        );

        add_settings_section(
            "{$key}_api_section",
            "{$label} API Settings",
            [$this, 'api_settings_section_callback'],
            'travel-booking-plugin'
        );

        add_settings_field(
            "{$key}_api_key",
            "{$label} API Key",
            [$this, 'api_key_field_callback'],
            'travel-booking-plugin',
            "{$key}_api_section",
            ['key' => $key]
        );
    }

    public function sanitize_api_settings(array $input)
    {
        return array_map('sanitize_text_field', $input);
    }


    public function api_settings_section_callback()
    {
        // You can display any information about the API settings here
    }

    public function api_key_field_callback(array $args)
    {
        $key = $args['key'];
        $options = get_option("{$key}_api_settings");
        $value = $options["{$key}_api_key"] ?? '';

        printf(
            '<input type="text" id="%1$s_api_key" name="%2$s[%1$s_api_key]" value="%3$s" class="regular-text"/>',
            esc_attr($key),
            esc_attr("{$key}_api_settings"),
            esc_attr($value)
        );
    }


    private function register_widget_settings()
    {
        register_setting(
            'travel_booking_settings',
            'widget_settings',
            [
                'sanitize_callback' => [$this, 'sanitize_widget_settings'],
                'default' => [
                    'enable_widgets' => [], // Default to no widgets enabled
                ],
            ]
        );

        add_settings_section(
            'widget_section',
            'Widget Settings',
            [$this, 'widget_settings_section_callback'],
            'travel-booking-plugin'
        );

        add_settings_field(
            'enable_widgets',
            'Enable Widgets',
            [$this, 'enable_widgets_field_callback'],
            'travel-booking-plugin',
            'widget_section'
        );
    }


    public function sanitize_widget_settings($input): array    
    {

        // Ensure input is an array
        if (!is_array($input)) {
            $input = [];
        }

        if (isset($input['enable_widgets']) && is_array($input['enable_widgets'])) {
            $input['enable_widgets'] = array_map('sanitize_text_field', $input['enable_widgets']);
        }

        return $input;
    }


    public function widget_settings_section_callback()
    {
        // You can display any information about the widget settings here.
    }

    public function enable_widgets_field_callback()
    {
        $options = get_option('widget_settings', []);
        $enabled_widgets = $options['enable_widgets'] ?? [];
    
        $widgets = ['flights', 'cars', 'activities', 'stays'];
        foreach ($widgets as $widget) {
            printf(
                '<label><input type="checkbox" name="widget_settings[enable_widgets][]" value="%1$s" %2$s> %3$s</label><br/>',
                esc_attr($widget),
                checked(in_array($widget, $enabled_widgets), true, false),
                esc_html(ucfirst($widget))
            );
        }
    }
    

    public function render_admin_settings_page()
    {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Travel Booking Plugin Settings', 'travel-booking-plugin'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('travel_booking_settings');
                do_settings_sections('travel-booking-plugin');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }
}