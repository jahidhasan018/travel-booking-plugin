<?php

namespace TravelBookingPlugin\Core;

class Deactivator
{
    public function deactivate()
    {
        // Perform deactivation tasks here (e.g., deleting options, dropping tables).
        // flush rewrite rules
        flush_rewrite_rules();
    }
}