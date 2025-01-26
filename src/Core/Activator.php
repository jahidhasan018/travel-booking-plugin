<?php

namespace TravelBookingPlugin\Core;

class Activator
{
    public function activate()
    {
       // Perform activation tasks here (e.g., creating custom tables, setting options).
       // You might need to create a database table for storing api keys.

       // flush rewrite rules
        flush_rewrite_rules();
    }
}