<?php
// Configuration: Replace with your actual Stalker Portal URL
$stalker_url = "http://jiotv.be/stalker_portal/c/";

// Function to fetch Stalker Portal with MAC authentication
function fetchPortal($mac_address) {
    global $stalker_url;

    // Set headers to mimic a MAG device
    $headers = [
        "User-Agent: Mozilla/5.0 (QtEmbedded; U; Linux) stbapp/1.0",
        "Referer: $stalker_url",
        "X-User-Agent: Model MAG250; Link: WiFi",
        "Authorization: Bearer",
        "mac: $mac_address"
    ];

    // Initialize cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $stalker_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Get MAC address from user input (via URL parameter)
$mac = isset($_GET['mac']) ? $_GET['mac'] : "00:1A:79:FD:64:46"; // Default MAC example

// Fetch and display the portal with authentication
echo fetchPortal($mac);
?>
