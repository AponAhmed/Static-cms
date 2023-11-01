<?php

namespace Aponahmed\Cmsstatic\Utilities;

use Exception;

class ApiRequest
{
    private $apiEndpoint;
    private $apiKey;

    public function __construct($apiEndpoint, $apiKey)
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->apiKey = $apiKey;
    }

    public function send($postData = [], $method = 'POST')
    {
        // Initialize cURL session
        $ch = curl_init($this->apiEndpoint);

        // Convert data to JSON if it's a POST request
        if ($method === 'POST') {
            $jsonData = json_encode($postData);
        }

        // Set cURL options
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-API-Key: ' . $this->apiKey,
            ]);
        } else {
            // For GET requests, you can add query parameters if needed
            // For example: $this->apiEndpoint .= '?param1=value1&param2=value2';
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-API-Key: ' . $this->apiKey,
            ]);
        }

        // Return the response as a string instead of outputting it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session and store the result in $response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Handle the API response
        if ($response !== false) {
            // API request was successful, and $response contains the response data
            return $response;
        } else {
            // API request failed
            throw new Exception('API Request Failed');
        }
    }
}
