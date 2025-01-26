<?php

namespace TravelBookingPlugin\Modules\Flights;
use TravelBookingPlugin\Api\ApiManager;

class FlightService
{
    private $apiManager;

    public function __construct()
    {
        $this->apiManager = new ApiManager();
    }
    public function search_flights(string $departureCity, string $destination, string $departureDate, string|null $returnDate = null, int $passengers = 1): array
    {
        // Get API key
        $apiKey = $this->apiManager->get_api_key('flights');

        if (!$apiKey) {
            return []; // Or log an error.
        }

        // sample KIWI API endpoint:
        $apiEndpoint = 'https://api.tequila.kiwi.com/v2/search/';


        // https://api.tequila.kiwi.com/v2/search?fly_from=FRA&fly_to=PRG&date_from=26%2F01%2F2025&date_to=28%2F01%2F2025&return_from=29%2F01%2F2025&return_to=31%2F01%2F2025&nights_in_dst_from=2&nights_in_dst_to=3&max_fly_duration=20&ret_from_diff_city=true&ret_to_diff_city=true&one_for_city=0&one_per_date=0&adults=2&children=2&selected_cabins=C&mix_with_cabins=M&adult_hold_bag=1%2C0&adult_hand_bag=1%2C1&child_hold_bag=2%2C1&child_hand_bag=1%2C1&only_working_days=false&only_weekends=false&partner_market=us&max_stopovers=2&max_sector_stopovers=2&limit=10

        // Build query parameters
        $queryParams = [
            'fly_from' => strtoupper($departureCity),
            'fly_to' => strtoupper($destination),
            'date_from' => $departureDate,
            'date_to' => $returnDate,
            'adults' => $passengers,
            'limit' => 5,
        ];

        $apiUrl = $apiEndpoint . '?' . build_query($queryParams);

        // Make the API request

        $response = wp_remote_get($apiUrl, [
            'headers' => [
                'apikey' => $apiKey,
                'Content-Type' => 'application/json'
            ]
        ]);

        // Check for errors in the API response
        if (is_wp_error($response)) {
            // Log the error
            error_log('KIWI API Error: ' . $response->get_error_message());
            return []; // Return an empty array or a specific error object
        }

        // Process the response if it's successful
        $body = wp_remote_retrieve_body($response);

        if (!$body) {
            error_log('KIWI API Error: Empty Response Body');
            return []; // Handle empty body or response
        }

        $data = json_decode($body, true);


        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['data'])) {
            error_log('KIWI API Error: Invalid JSON Response');
            return [];  // Return empty array or handle the error.
        }

        // Sample response from KIWI
        //    {
        //         "data": [
        //             {
        //                 "id": "5793642",
        //                 "flyFrom": "PRG",
        //                 "flyTo": "JFK",
        //                 "cityFrom": "Prague",
        //                 "cityTo": "New York",
        //                 "countryFrom": {
        //                     "code": "CZ",
        //                     "name": "Czechia"
        //                 },
        //                 "countryTo": {
        //                     "code": "US",
        //                     "name": "United States"
        //                 },
        //                 "dTime": 1679914500,
        //                 "dTimeUTC": 1679910900,
        //                 "aTime": 1679945400,
        //                 "aTimeUTC": 1679934600,
        //                 "nightsInDest": 0,
        //                 "price": 1201,
        //                 "availability": {
        //                     "seats": 6
        //                 },
        //                 "bags_price": {
        //                     "1": 50,
        //                     "2": 100
        //                 },
        //                 "baglimit": {
        //                     "hand_height": 1,
        //                     "hand_length": 1,
        //                     "hand_weight": 1,
        //                     "hand_width": 1,
        //                     "hold_dimensions_sum": 1,
        //                     "hold_height": 1,
        //                     "hold_length": 1,
        //                     "hold_weight": 1,
        //                     "hold_width": 1
        //                 },
        //                 "conversion": {
        //                     "EUR": 1100
        //                 },
        //                 "quality": 1
        //             },
        //              // more flight data here
        //          ]
        //   }

        // $flights = [];
        // foreach ($data['data'] as $flightData) {
        //     $flights[] = [
        //         'image' => '', // You might not have a direct image link from the API
        //         'title' => $flightData['cityFrom'] . ' to ' . $flightData['cityTo'],
        //         'price' => '$' . number_format($flightData['price'], 2),
        //         'description' => 'Departure: ' . date('Y-m-d H:i', $flightData['dTime']) . ' Arrival: ' . date('Y-m-d H:i', $flightData['aTime']),
        //         'book_url' => 'https://www.kiwi.com/en/search/results/' . $departureCity . '-' . $destination . '/' . $departureDate  //  create KIWI url here
        //     ];
        // }

        return $data['data'];
    }
}