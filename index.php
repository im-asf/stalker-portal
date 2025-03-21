<?php
// Configuration
$stalker_url = "http://jiotv.be/stalker_portal/c/";

// Function to fetch portal homepage
function fetchPortal() {
    global $stalker_url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $stalker_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// Display Stalker Portal
echo fetchPortal();
?>
