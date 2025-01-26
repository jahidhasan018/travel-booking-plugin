<?php

namespace TravelBookingPlugin\Frontend;

use TravelBookingPlugin\Modules\Flights\FlightWidget;
use TravelBookingPlugin\Modules\Cars\CarRentalWidget;
use TravelBookingPlugin\Modules\Activities\ActivitiesWidget;
use TravelBookingPlugin\Modules\Stays\StaysWidget;

class Shortcodes
{
    protected $flightWidget;
    protected $carRentalWidget;
    protected $activitiesWidget;
    protected $staysWidget;


    public function __construct()
    {
        $this->flightWidget = new FlightWidget();
        // $this->carRentalWidget = new CarRentalWidget();
        // $this->activitiesWidget = new ActivitiesWidget();
        // $this->staysWidget = new StaysWidget();
    }
    public function register_shortcodes()
    {
        add_shortcode('travel_booking_flights', [$this->flightWidget, 'render_widget']);
        // add_shortcode('travel_booking_cars', [$this->carRentalWidget, 'render_widget']);
        // add_shortcode('travel_booking_activities', [$this->activitiesWidget, 'render_widget']);
        // add_shortcode('travel_booking_stays', [$this->staysWidget, 'render_widget']);
    }
}