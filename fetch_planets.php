<?php

$servername = "localhost";
$username = "store_admin";
$password = "password1#";
$dbname = "store_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch planets data
function getPlanets() {
    $url = 'https://swapi.dev/api/planets/';
    $response = file_get_contents($url);
    if ($response !== FALSE) {
        $data = json_decode($response, true);
        return $data['results'];
    } else {
        return null;
    }
}

// Fetch and store planets data
$planets = getPlanets();

if ($planets) {
    $stmt = $conn->prepare("INSERT INTO planets (name, rotation_period, orbital_period, diameter, climate, gravity, terrain, surface_water, population, url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($planets as $planet) {
        $stmt->bind_param(
            "ssssssssss",
            $planet['name'],
            $planet['rotation_period'],
            $planet['orbital_period'],
            $planet['diameter'],
            $planet['climate'],
            $planet['gravity'],
            $planet['terrain'],
            $planet['surface_water'],
            $planet['population'],
            $planet['url']
        );
        $stmt->execute();
    }
    $stmt->close();
    echo "Planets data stored successfully.";
} else {
    echo "No planets data found.";
}

$conn->close();
?>
