<?php

namespace TravelBookingPlugin\Modules\Flights;

use TravelBookingPlugin\Api\ApiManager;

class FlightWidget
{

    protected $apiManager;
    protected $flightService;

    public function __construct()
    {
        $this->apiManager = new ApiManager();
        $this->flightService = new FlightService();

        // Register AJAX actions
        add_action('wp_ajax_search_flights', [$this, 'handle_ajax_request']);
        add_action('wp_ajax_nopriv_search_flights', [$this, 'handle_ajax_request']);
    }

    public function render_widget(array $atts = [], string $content = null): string
    {
        // Check if module is enabled
        if (!$this->apiManager->is_module_enabled('flights')) {
            return '';
        }

        wp_enqueue_script('flight-widget', TRAVEL_BOOKING_PLUGIN_URL . '/assets/public/js/flight-widget.js', ['jquery'], time(), true);

        wp_localize_script('flight-widget', 'flightWidgetAjax', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('flight_widget_nonce'),
        ]);

        ob_start();
        ?>
        <div class="container travel-widget travel-widget-flights">
            <div class="tabs">
                <button class="tab active" data-tab="flights">
                    <span class="material-icons">flight</span> Flights
                </button>
                <button class="tab" data-tab="stays">
                    <span class="material-icons">hotel</span> Stays
                </button>
                <button class="tab" data-tab="todo">
                    <span class="material-icons">tour</span> To Do
                </button>
                <button class="tab" data-tab="rental">
                    <span class="material-icons">directions_car</span> Rental Cars
                </button>
            </div>

            <!-- Flights Tab -->
            <div class="tab-content" id="flights">
                <form class="search-form">
                    <div class="form-row">
                        <div class="input-container">
                            <span class="material-icons">flight_takeoff</span>
                            <input type="text" placeholder="From: Origin" class="form-input" id="departure_city">
                        </div>
                        <span class="material-icons material-symbols-outlined">swap_horiz</span>
                        <div class="input-container">
                            <span class="material-icons">flight_land</span>
                            <input type="text" placeholder="To: Destination" class="form-input" id="destination">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">event</span>
                            <input type="text" placeholder="Departure Date" class="form-input datepicker" id="departure_date">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">event</span>
                            <input type="text" placeholder="Return Date" class="form-input datepicker" id="return_date">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">person</span>
                            <input type="number" placeholder="1" class="form-input" id="passengers" min="1">
                        </div>
                        <button type="submit" class="btn-search" id="search-flights">
                            <span class="material-icons">search</span> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stays Tab -->
            <div class="tab-content" id="stays" style="display: none;">
                <form class="search-form">
                    <div class="form-row">
                        <div class="input-container">
                            <span class="material-icons">event</span>
                            <input type="text" placeholder="Check-in" class="form-input datepicker">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">event</span>
                            <input type="text" placeholder="Check-out" class="form-input datepicker">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">group</span>
                            <input type="text" placeholder="Guests" class="form-input">
                        </div>
                        <button type="submit" class="btn-search">
                            <span class="material-icons">search</span> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- To Do Tab -->
            <div class="tab-content" id="todo" style="display: none;">
                <form class="search-form">
                    <div class="form-row">
                        <div class="input-container">
                            <span class="material-icons">location_on</span>
                            <input type="text" placeholder="Destination" class="form-input">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">event</span>
                            <input type="text" placeholder="Start Date" class="form-input datepicker">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">event</span>
                            <input type="text" placeholder="End Date" class="form-input datepicker">
                        </div>
                        <button type="submit" class="btn-search">
                            <span class="material-icons">search</span> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rental Cars Tab -->
            <div class="tab-content" id="rental" style="display: none;">
                <form class="search-form">
                    <div class="form-row">
                        <div class="input-container">
                            <span class="material-icons">location_on</span>
                            <input type="text" placeholder="Pick-up Location" class="form-input">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">event</span>
                            <input type="text" placeholder="Pick-up Date" class="form-input datepicker">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">access_time</span>
                            <input type="text" placeholder="Pick-up Time" class="form-input">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">event</span>
                            <input type="text" placeholder="Drop-off Date" class="form-input datepicker">
                        </div>
                        <div class="input-container">
                            <span class="material-icons">access_time</span>
                            <input type="text" placeholder="Drop-off Time" class="form-input">
                        </div>
                        <button type="submit" class="btn-search">
                            <span class="material-icons">search</span> Search
                        </button>
                    </div>
                    <div class="form-options">
                        <label><input type="checkbox"> Drop car off at a different location</label>
                        <label><input type="checkbox"> Driver aged 30 - 65?</label>
                    </div>
                </form>
            </div>

            <div id="flight-results"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function handle_ajax_request()
    {
        // Verify nonce
        check_ajax_referer('flight_widget_nonce', 'nonce');

        // Get and sanitize input
        $departure_city = sanitize_text_field($_POST['departure_city']);
        $destination = sanitize_text_field($_POST['destination']);
        $departure_date = sanitize_text_field($_POST['departure_date']);
        $return_date = sanitize_text_field($_POST['return_date']);
        $passengers = intval($_POST['passengers']);

        // Perform flight search
        $results = $this->flightService->search_flights($departure_city, $destination, $departure_date, $return_date, $passengers);

        // Return the results as JSON
        if (!empty($results)) {
            wp_send_json_success($results);
        } else {
            wp_send_json_error(['message' => __('No flights found.', 'travel-booking-plugin')]);
        }

        wp_die();
    }
}
