<?php
// Configuration: Stalker Portal URL
$stalker_url = "http://jiotv.be/stalker_portal/c/";

// Get MAC address from URL
$mac = isset($_GET['mac']) ? $_GET['mac'] : "00:1A:79:CA:13:AB";

// Step 1: Authenticate and Get Token
$auth_url = $stalker_url . "server/load.php?type=stb&action=handshake&mac=" . urlencode($mac);

$headers = [
    "User-Agent: Mozilla/5.0 (QtEmbedded; U; Linux) stbapp/1.0",
    "Referer: $stalker_url",
    "X-User-Agent: Model MAG250; Link: WiFi",
    "mac: $mac"
];

// Send Request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $auth_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
curl_close($ch);

// Decode Response
$data = json_decode($response, true);
$token = isset($data['token']) ? $data['token'] : '';

if (!$token) {
    die("Error: Stalker Portal did not return a valid token. Try a different MAC.");
}

// Step 2: Get M3U Playlist
$m3u_url = $stalker_url . "server/load.php?type=itv&action=get_m3u&mac=" . urlencode($mac) . "&token=" . urlencode($token);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $m3u_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$m3u_playlist = curl_exec($ch);
curl_close($ch);

// Step 3: Return M3U File
if (!$m3u_playlist) {
    die("Error: No playlist found. MAC might be blocked.");
}

header("Content-Type: audio/x-mpegurl");
header("Content-Disposition: attachment; filename=playlist.m3u");
echo $m3u_playlist;
?>
