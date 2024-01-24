<?php
// File to store the tracking data
$file = 'tracking_data.json';

// Initialize data if the file doesn't exist
if (!file_exists($file)) {
    $initialData = [
        'view_count' => 0,
        'clicked_links_counters' => []
    ];
    file_put_contents($file, json_encode($initialData));
}

// Check if the user has already visited during this session
if (!isset($_SESSION['visited'])) {
    // Increment the view count
    $trackingData = json_decode(file_get_contents($file), true);
    $trackingData['view_count']++;

    // Save the updated data to the file
    file_put_contents($file, json_encode($trackingData));

    // Mark the user as visited in this session
    $_SESSION['visited'] = true;
}

// Get the link from the POST data
if (isset($_POST['link'])) {
    $clickedLink = $_POST['link'];

    // Initialize the counter for the clicked link if not already set

    // Increment the counter for the clicked link
    $trackingData['clicked_links_counters'][$clickedLink]++;

    // Save the updated data to the file
    file_put_contents($file, json_encode($trackingData));

    // Return the updated counter for the clicked link
    echo $trackingData['clicked_links_counters'][$clickedLink];
} else {
    // If no link is provided, return an error message
    echo 'Error: No link provided.';
}
?>
