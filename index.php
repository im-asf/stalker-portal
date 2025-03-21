<?php
// Configuration: Replace with your actual Stalker Portal URL
$stalker_url = "http://jiotv.be/stalker_portal/c/";

// Get MAC address from user input
$mac = isset($_GET['mac']) ? $_GET['mac'] : "00:1A:79:CA:13:AB"; // Default MAC example

// Generate authentication URL
$auth_url = $stalker_url . "server/load.php?type=stb&action=handshake&mac=" . urlencode($mac);

// Set Headers to mimic a MAG device
$headers = [
    "User-Agent: Mozilla/5.0 (QtEmbedded; U; Linux) stbapp/1.0",
    "Referer: $stalker_url",
    "X-User-Agent: Model MAG250; Link: WiFi",
    "mac: $mac"
];

// Start cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $auth_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
curl_close($ch);

// Extract Token for authentication
$data = json_decode($response, true);
$token = isset($data['token']) ? $data['token'] : '';

if (!$token) {
    die("Error: Could not authenticate with the Stalker Portal.");
}

// Fetch M3U Playlist
$m3u_url = $stalker_url . "server/load.php?type=itv&action=get_m3u&mac=" . urlencode($mac) . "&token=" . urlencode($token);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $m3u_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$m3u_playlist = curl_exec($ch);
curl_close($ch);

// Output as an M3U file
header("Content-Type: audio/x-mpegurl");
header("Content-Disposition: attachment; filename=playlist.m3u");
echo $m3u_playlist;
?>
