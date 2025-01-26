<?php

namespace TravelBookingPlugin\Core;

use TravelBookingPlugin\Frontend\Enqueue;
use TravelBookingPlugin\Frontend\Shortcodes;
use TravelBookingPlugin\Admin\AdminMenu;


class Hooks
{

    protected $enqueue;
    protected $shortcodes;
    protected $adminMenu;
    public function __construct()
    {
        $this->enqueue = new Enqueue();
        $this->shortcodes = new Shortcodes();
        $this->adminMenu = new AdminMenu();
    }

    public function init()
    {
        // Enqueue scripts and styles.
        add_action('wp_enqueue_scripts', [$this->enqueue, 'enqueue_public_scripts']);
        add_action('admin_enqueue_scripts', [$this->enqueue, 'enqueue_admin_scripts']);
         // Register admin menus
        add_action('admin_menu', [$this->adminMenu, 'add_admin_menu']);

         // Add shortcodes
        add_action('init', [$this->shortcodes, 'register_shortcodes']);
    }
}