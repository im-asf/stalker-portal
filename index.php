<?php
// Configuration: Replace with your actual Stalker Portal URL
$stalker_url = "http://jiotv.be/stalker_portal/c/";

// Function to fetch M3U Playlist
function getM3UPlaylist($mac_address) {
    global $stalker_url;

    // Define the M3U URL for the Stalker Portal
    $m3u_url = $stalker_url . "server/load.php?type=itv&action=get_m3u&mac=" . urlencode($mac_address);

    // Set headers to mimic a MAG device
    $headers = [
        "User-Agent: Mozilla/5.0 (QtEmbedded; U; Linux) stbapp/1.0",
        "Referer: $stalker_url",
        "X-User-Agent: Model MAG250; Link: WiFi",
        "mac: $mac_address"
    ];

    // Initialize cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $m3u_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Get MAC address from user input (via URL parameter)
$mac = isset($_GET['mac']) ? $_GET['mac'] : "00:1A:79:CA:13:AB"; // Default MAC

// Fetch and display the M3U Playlist
header("Content-Type: audio/x-mpegurl");
echo getM3UPlaylist($mac);
?>
