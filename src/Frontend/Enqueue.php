<?php

namespace TravelBookingPlugin\Frontend;

class Enqueue
{
    public function enqueue_public_scripts()
    {
        wp_enqueue_style('travel-booking-plugin-public-style', plugins_url('/assets/public/css/style.css', dirname(dirname(__FILE__))), [], '1.0.0');
        wp_enqueue_style('travel-booking-plugin-mobiscroll-style', plugins_url('/assets/public/css/mobiscroll.jquery.min.css', dirname(dirname(__FILE__))), [], '1.0.0');
        wp_enqueue_style('travel-booking-Material-icon', '//fonts.googleapis.com/icon?family=Material+Icons', [], '1.0.0');
        wp_enqueue_script('travel-booking-plugin-public-script', plugins_url('/assets/public/js/public.js', dirname(dirname(__FILE__))), ['jquery'], '1.0.0', true);
        wp_enqueue_script('travel-booking-plugin-mobiscroll-script', plugins_url('/assets/public/js/mobiscroll.jquery.min.js', dirname(dirname(__FILE__))), ['jquery'], '1.0.0', true);
        // enquue date picker from wordpress core
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-css', '://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    }

    public function enqueue_admin_scripts()
    {
        if (isset($_GET['page']) && $_GET['page'] === 'travel-booking-plugin') {
            wp_enqueue_style('travel-booking-plugin-admin-style', plugins_url('/assets/admin/css/admin.css', dirname(dirname(__FILE__))), [], '1.0.0');
            wp_enqueue_script('travel-booking-plugin-admin-script', plugins_url('/assets/admin/js/admin-app.js', dirname(dirname(__FILE__))), ['jquery'], '1.0.0', true);
        }
    }
}