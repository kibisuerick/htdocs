<?php
require 'db.php'; // Ensure your database connection is included

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $location = trim($_GET['query']); // Get the search query and remove spaces
    $location = htmlspecialchars($location); // Prevent XSS attacks

    // Fetch properties from the 'properties' table based on location
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE location LIKE :location");
    $stmt->execute(['location' => "%$location%"]);
    $properties = $stmt->fetchAll();

    echo "<h1>Properties in: " . htmlspecialchars($location) . "</h1>";

    if (count($properties) > 0) {
        echo "<ul>";
        foreach ($properties as $property) {
            echo "<li>" . htmlspecialchars($property['title']) . " - " . htmlspecialchars($property['price']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No properties found in this location.</p>";
    }
} else {
    echo "<h1>No search query provided.</h1>";
}
?>
