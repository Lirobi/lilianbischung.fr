
<?php
session_start();

// File to store the view count and clicked links
$file = 'assets/tracking_data.json';

// Initialize data if the file doesn't exist
if (!file_exists($file)) {
    $initialData = [
        'view_count' => 0,
        'clicked_links' => []
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


// Set the admin password (change this to your desired password)
$adminPassword = "your-password";

// Check if the user is an admin
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Check if the form is submitted for password verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"])) {
    $enteredPassword = $_POST["password"];

    if ($enteredPassword == $adminPassword) {
        // Password is correct, set admin session flag
        $_SESSION['is_admin'] = true;
        $isAdmin = true;
    } else {
        // Password is incorrect, display an error message
        $error_message = "Incorrect password. Please try again.";
    }
}

// Handle adding a new review
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_review"])) {
    $name = $_POST["name"];
    $stars = $_POST["stars"];
    $text = $_POST["add_review"];
    
    // Add a double line break before appending the new review
    if (!empty($text)) {
        $newReview = "$name\n$stars\n$text\n\n";
        file_put_contents("reviews.txt", $newReview, FILE_APPEND);
        
        // Redirect to a different page to prevent form resubmission on page reload
        header("Location: ".$_SERVER['PHP_SELF']."?review_added=true");
        exit();
    }
}

// Redirect non-admin users to the main page
if (!$isAdmin) {
    // Display the password form for authentication
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">

        <!-- ... (your existing head content) ... -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../style/admin.css">
    </head>

    <body>
        <section id="admin-login" class="admin-section reveal">
            <div class="container">
                <h2>Admin Login</h2>
                <?php
                if (isset($error_message)) {
                    echo "<p style='color: red;'>$error_message</p>";
                }
                ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="password">Enter Admin Password:</label>
                    <input type="password" name="password" required>
                    <input type="submit" value="Login">
                </form>
            </div>
        </section>
        <script src="../scripts/scroll-animations.js"></script>
    </body>

    </html>
    <?php
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_video"])) {
    // Get videos

    $deleteWhat = $_POST["delete_video"];
    if($deleteWhat == "Delete last video") {
        $videos = file_get_contents("videos.txt");
        $all_videos = explode("\n", $videos);
        $i = 0;
        foreach(array_reverse($all_videos) as $video) {
            $updated_videos = "$updated_videos\n$video";
            $i++;
            if($i == count($all_videos) - 1) {
                break;
            }
        }
    }


  // Save updated videos

        file_put_contents("videos.txt", $updated_videos);
if($deleteWhat == "Delete last short") {
    $shorts = file_get_contents("shorts.txt");
    $short_videos = explode("\n", $videos);

    $i = 0;
    foreach(array_reverse($short_videos) as $video) {
        $updated_shorts = "$updated_shorts\n$video";
        $i++;
        if($i == count($short_videos) - 1) {
            break;
        }

  // Save updated videos

  file_put_contents("shorts.txt", $updated_shorts);
    }
  // Redirect

  header("Location: admin.php?removed=true");

  exit;
}

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_video"])) {
    // Get form values
  $url = $_POST["video_url"];


    // Add video to videos array

    if(str_contains($url, 'shorts')) {

        $videos = file_get_contents("shorts.txt");

        $videos = "$videos\n$url";
    
        // Save to file
        file_put_contents("shorts.txt", $videos);
    }
    if(str_contains($url, 'watch')) {

        $videos = file_get_contents("videos.txt");

        $videos = "$videos\n$url";
    
        // Save to file
        file_put_contents("videos.txt", $videos);
    }

    // Redirect with success message
    header("Location: ".$_SERVER['PHP_SELF']."?review_added=true");
    exit;

  
}
// Handle editing or removing a review
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_review"]) && isset($_POST["review_action"])) {
    $toeditName = $_POST["edit_name"];
    $toeditStars = $_POST["edit_stars"];
    $toeditText = $_POST["edit_review"];
    
    $reviewToEdit = "$toeditName\n$toeditStars\n$toeditText\n\n";


    $reviewAction = $_POST["review_action"];

    // Read existing reviews from reviews.txt
    $reviews = file_get_contents("reviews.txt");
    
    // Find and replace the edited review
    if ($reviewAction == "✔️") {
        $editedName = $_POST["edited_name"];
        $editedStars = $_POST["edited_stars"];
        $editedText = $_POST["edited_text"];

        $editedReview = "$editedName\n$editedStars\n$editedText\n\n";
        $reviews = str_replace($reviewToEdit, $editedReview, $reviews);
    } elseif ($reviewAction == "❌") {
        // Remove the selected review
        $reviews = str_replace($reviewToEdit, "", $reviews);
    }

    // Write back the modified reviews to reviews.txt
    file_put_contents("reviews.txt", $reviews);

    // Redirect to the admin page to refresh the displayed reviews
    header("Location: ".$_SERVER['PHP_SELF']."?review_updated=". $editedText);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ... (your existing head content) ... -->
    <title>Admin panel</title>
    <meta name=" robots" content=" noindex, nofollow">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script type="text/javascript" src="../scripts/toggle-pages.js"></script>
</head>

<body>
    <section class="nav">
        <div class="container">

            <h2><a href=".."><- Back to site</a></h2>
            <h2 onclick="togglePage('.home-page')">Home</h2>
            <h2 onclick="togglePage('.reviews-page')">Reviews</h2>
            <h2 onclick="togglePage('.tracker-page')">Tracker</h2>
            <h2 onclick="togglePage('.videos-page')">Video management</h2>
            

        </div>
    </section>

    <div class="home-page page">
        <section>
            <div class="agenda-container">
                <iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=2&bgcolor=%23ffffff&ctz=Europe%2FParis&mode=MONTH&showTz=1&title&src=MzE0MGY3YmQ1OGQwY2I0NWU0MGFiMDc4MDY1MjUxMzc1ZWFhODg3MDZhNmRhYjEzNjE0ZTljODM2NDRhMWQ5ZkBncm91cC5jYWxlbmRhci5nb29nbGUuY29t&color=%23B39DDB" style="border:solid 1px #777" width="1200" height="600" frameborder="0" scrolling="no"></iframe>
            </div>
        </section>
    </div>

    <div class="videos-page page hidden">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="submit" name="delete_video" value="Delete last video">
            <input type="submit" name="delete_video" value="Delete last short">
        </form>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <textarea name="video_url" required></textarea>
            <input type="submit" name="add_video" value="✔️">
        </form>
    </div>

    <div class="reviews-page page hidden">
        <section id="add-review" class="admin-section reveal">
            <div class="container">
                <h2>Add Review</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="name">Name:</label>
                    <input type="text" name="name" required>

                    <label for="stars">Stars:</label>
                    <select name="stars" required>
                        <option value="1">⭐️</option>
                        <option value="2">⭐️⭐️</option>
                        <option value="3">⭐️⭐️⭐️</option>
                        <option value="4">⭐️⭐️⭐️⭐️</option>
                        <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                    </select>

                    <label for="add_review">Review Text:</label>
                    <textarea name="add_review" required></textarea>
                    <input type="submit" value="Add Review">
                </form>
            </div>
        </section>

        <section id="admin" class="admin-section reveal">
            <div class="container">
                <h2>Reviews</h2>

                <div class="reviews">
                    <?php
                    // Display reviews sorted by most recent
                    $reviews = file_get_contents("reviews.txt");
                    $reviewsArray = explode("\n\n", $reviews);

                    // Remove any empty elements
                    $reviewsArray = array_filter($reviewsArray);

                    // Reverse the array to display the most recent reviews at the top
                    $reviewsArray = array_reverse($reviewsArray);

                    foreach ($reviewsArray as $i => $review) {
                        if (!empty($review)) {
                            // Extract name, stars, and text from the review
                            list($name, $stars, $text) = extractReviewDetails($review);

                            // Display the review details along with edit and remove options
                            ?>
                            <div class='review-item'>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="edit_name" value="<?php echo $name; ?>">
                                    <input type="hidden" name="edit_stars" value="<?php echo $stars; ?>">
                                    <input type="hidden" name="edit_review" value="<?php echo $text; ?>">
                                    
                                    <h4> <label for="edited_name">🧑‍💻 </label>
                                    <input type="text" name="edited_name" value="<?php echo htmlspecialchars($name); ?>" required>
                                    <label for="edited_stars"><?php echo str_repeat("⭐️", $stars) ?></label>
                                    <select name="edited_stars" required>
                                        <option value="1" <?php echo ($stars == 1) ? 'selected' : ''; ?>>⭐️</option>
                                        <option value="2" <?php echo ($stars == 2) ? 'selected' : ''; ?>>⭐️⭐️</option>
                                        <option value="3" <?php echo ($stars == 3) ? 'selected' : ''; ?>>⭐️⭐️⭐️</option>
                                        <option value="4" <?php echo ($stars == 4) ? 'selected' : ''; ?>>⭐️⭐️⭐️⭐️</option>
                                        <option value="5" <?php echo ($stars == 5) ? 'selected' : ''; ?>>⭐️⭐️⭐️⭐️⭐️</option>
                                    </select>
                                </h4>

                                    <textarea name="edited_text" required><?php echo htmlspecialchars($text); ?></textarea>
                                    <div class="editbuttons">
                                        <input type="submit" name="review_action" value="✔️">
                                        <input type="submit" name="review_action" value="❌">
                                    </div>
                                </form>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>  
    <div class="tracker-page page hidden">
        <section>
            <div>
            <p>Total Views: <?php echo $trackingData['view_count']; ?></p>
            <p>Clicked Links: <?php echo implode(', ', $trackingData['clicked_links']); ?></p>
            </div>
        </section>
    </div>
</body>

</html>

<?php
function extractReviewDetails($review) {
    // Extract name, stars, and text from the review
    $lines = explode("\n", $review);
    $name = $lines[0];  // Remove "Name: " from the beginning
    $stars = $lines[1]; // Remove "Stars: " from the beginning
    $text = $lines[2];  // Remove "Text: " from the beginning

    return array($name, $stars, $text);
}
?>