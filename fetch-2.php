<?php
// Select weather data for given parameters
$sql = "SELECT *
FROM weather
WHERE city = '{$_GET['city']}'
AND weather_when >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
ORDER BY weather_when DESC limit 1";
$result = $mysqli -> query($sql);

// If 0 record found
if ($result->num_rows == 0) {
    $url = 'https://api.openweathermap.org/data/2.5/weather?q='.$_GET['city'].'&appid=03a98a937b475893d0fe9e6be08c4ec5&units=metric';

    // Get data from openweathermap and store in JSON object
    $data = file_get_contents($url);
    $json = json_decode($data, true);
    date_default_timezone_set('Asia/Kathmandu');

    // Fetch required fields
    $weather_when = date("Y-m-d H:i:s"); // now
    $city = $json['name'];
    $weather_description = $json['weather'][0]['main'];
    $weather_temperature = $json['main']['temp'];
    $weather_humidity = $json['main']['humidity'];
    $weather_pressure = $json['main']['pressure'];
    $wind_speed = $json['wind']['speed'];
    $wind_direction = $json['wind']['deg'];

    // Build INSERT SQL statement
    $sql = "INSERT INTO weather(weather_description, weather_temperature ,weather_when ,weather_humidity ,weather_pressure, wind_speed, wind_direction, city) VALUES ('{$weather_description}','{$weather_temperature}','{$weather_when}','{$weather_humidity}','{$weather_pressure}','{$wind_speed}','{$wind_direction}','{$city}')";
}
    # displays error message
    if (!$mysqli -> query($sql)) {
        echo("<h4>SQL error description: " . $mysqli -> error . "</h4>");       
}
?>