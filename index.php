<?php
session_start();

// Check if language is selected
if (isset($_POST['language'])) {
    $selectedLanguage = $_POST['language'];

    // Set the language in the session
    $_SESSION['language'] = $selectedLanguage;

    // Redirect based on the selected language
    if ($selectedLanguage === 'fr') {
        header('Location: ../fr');
        exit;
    }
}


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
session_abort();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>Lilian Bischung - Video Editor</title>

        <meta name="author" content="Lilian Bischung">
        <meta name="description" content="Unlock the full potential of your videos with my video editing services and make them shine.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name=" robots" content=" index, follow">
        <meta property="image" content="https://lilianbischung.fr/assets/logo.png" />

        <meta property="og:locale" content="en_US" />
        <meta property="og:title" content="Lilian Bischung - Video Editor" />
        <meta property="og:description" content="Unlock the full potential of your videos with my video editing services and make them shine." />
        <meta property="og:image" content="https://lilianbischung.fr/assets/logo.png" />
        <meta property="og:url" content="https://lilianbischung.fr" />
        <meta property="og:site_name" content="Lilian Bischung" />

        <meta property="twitter:title" content="Lilian Bischung - Video Editor" />
        <meta property="twitter:description" content="Unlock the full potential of your videos with my video editing services and make them shine." />
        <meta property="twitter:image" content="https://lilianbischung.frassets/logo.png" />
        <meta property="twitter:url" content="https://lilianbischung.fr" />
        <meta property="twitter:site_name" content="Lilian Bischung" />

        <link rel="icon" type="image/png" href="../assets/logo.png" />

        <link rel="stylesheet" href="../style/styles.css">
        <link rel="stylesheet" href="../style/stars.css">
        <link rel="stylesheet" href="../style/shining.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script type="text/javascript" src="../scripts/language.js"></script>
        <script type="text/javascript" src="../scripts/essentials.js"></script>
        <script type="text/javascript" src="../scripts/toggle-pages.js"></script>

        <script>var selectedLanguage = "<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : ''; ?>";</script>
    </head>
    <body>
        <div class="language-dropdown">
            <form method="post">
                <select name="language" onchange="this.form.submit()">
                    <option value="en" <?php echo ($_SESSION['language'] ?? 'en') === 'en' ? 'selected' : ''; ?>>EN</option>
                    <option value="fr" <?php echo ($_SESSION['language'] ?? 'en') === 'fr' ? 'selected' : ''; ?>>FR</option>
                </select>
            </form>
        </div>
        <header class="header-section">
            <div class="container">
                <div class="intro">
                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                        
                        
                        <h1 class="effect-shine">Lilian Bischung</h1>
                        <h2 class="effect-shine">Unlock the full potential of your videos</h2>
                </div>
            </div>
        </header>
        <section id="portfolio" class="portfolio-section">

            <img style="" src="../assets/logo-big.png" class="logo" alt="Lilian Bischung Logo">   

            <div class="container reveal">
                <h2>Previous work</h2>

                <div class="toggle-container reveal">
                    <h3 onclick="togglePage('.video-container')">Long format</h3>
                    <h3 onclick="togglePage('.shorts-container')">Tiktok Format</h3>
                </div> 

                <div class="video-container page">
                    <?php

                        $videos_file = file_get_contents('videos.txt');
                        $videosArray = explode("\n", $videos_file);
                        $i = 1;
                        foreach(array_reverse($videosArray) as $video) {
                            if(!empty($video)) {
                                $videoembed = str_replace('watch?v=', 'embed/', $video);
                                
                                if($i == 1 || $i == 3) {
                                    echo "<div class='video-item reveal'>";
                                } 
                                if($i == 2 || $i == 4) {
                                    echo "<div class='video-item reveal' id='alternate'>";
                                }
                                
                                echo "<iframe width='560' height='315' src='$videoembed' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' allowfullscreen></iframe>";    }
                                
                                echo "</div>";
                                $i++;
                                if($i == 5) {
                                    break;
                                }

                        }

                    ?>
                </div>
                
                <div class="shorts-container page hidden">
                    <?php

                            $shorts_file = file_get_contents('shorts.txt');
                            $shortsArray = explode("\n", $shorts_file);
                            $i = 1;
                            foreach(array_reverse($shortsArray) as $short) {
                                if(!empty($short)) {
                                    $shortembed = str_replace('shorts', 'embed', $short);
                                    echo "<iframe width='315' height='560' src='$shortembed' class='short-item reveal' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' allowfullscreen></iframe>";    }
                                    if ($i++ == 4) break;

                            }

                        ?>
                </div>
            </div>
        </section>

        <section class="more reveal">
            <div class="more-container">
                <h2>More of my previous work</h2>
                <a href="https://drive.google.com/drive/folders/1dvkOltPBvr-5r6Uec_2uFUggrvTmR379?usp=drive_link" class="track-link" data-link="Google Drive"><img src="../assets/google-drive-icon.png" id="google-drive-icon" alt="Google Drive Icon"></a>
            </div>
        </section>
        <section id="contact" class="contact-section reveal">
            <div class="container">
                <h2>Contact me</h2>
                <h3>If you are interested in working with me, please contact me.</h3>
                <a href="mailto:contact@lilianbischung.fr" class="email track-link reveal" data-link="Email">contact@lilianbischung.fr</a>
            </div>
        </section>

        
        <section id="reviews" class="reviews-section reveal">
            <div class="container">

                <h2>Customer Reviews</h2>

                <?php
                    $averageStars = calculateAverageStars("reviews.txt");
                    echo "<h3>". generateStarEmojis(round($averageStars, 0)) . " (" . round($averageStars, 1) . ")</h3>";
                ?>
                <div id="expandableReviews" class="expandable-reviews">
                    <?php

                        // Display existing reviews
                        $existingReviews = file_get_contents("reviews.txt");
                        $existingReviewsArray = explode("\n\n", $existingReviews);

                        foreach (array_reverse($existingReviewsArray) as $i => $review) {
                            if (!empty($review)) {
                                // Extract name, stars, and text from the review
                                list($name, $stars, $text) = extractReviewDetails($review);

                                // Display the review details
                                echo "<div class='review-item reveal'>";
                                echo "<h4>🧑‍💻 $name &nbsp&nbsp&nbsp&nbsp&nbsp" . generateStarEmojis((int)$stars) . "</h4>";
                                echo "<p>$text</p>";
                                echo "</div>";
                            }
                        }

                        function generateStarEmojis($numStars) {
                            $starEmoji = "⭐️";
                            $stars = str_repeat($starEmoji, $numStars);
                            return $stars;
                        }
                    ?>
                </div>
            </div>
        </section>
        


        <footer class="footer-section">
            <div class="container">
                <br>
                <p>&copy; 2024 Lilian Bischung. All rights reserved.</p>
            </div>
        </footer>
        <script type="text/javascript" src="../scripts/toggle-pages.js"></script>
        <script type="text/javascript" src="../scripts/essentials.js"></script>
        <script type="text/javascript" src="../scripts/scroll-animations.js"></script>
    </body>
</html>

<?php

    function calculateAverageStars($filePath) {
        $totalStars = 0;
        $reviewCount = 0;

        $existingReviews = file_get_contents($filePath);
        $existingReviewsArray = explode("\n\n", $existingReviews);

        foreach ($existingReviewsArray as $review) {
            if (!empty($review)) {
                list(, $stars, ) = extractReviewDetails($review);
                $totalStars += $stars;
                $reviewCount++;
            }
        }

        // Avoid division by zero
        return ($reviewCount > 0) ? $totalStars / $reviewCount : 0;
    }
    function extractReviewDetails($review) {
        // Extract name, stars, and text from the review
        $lines = explode("\n", $review);
        $name = $lines[0];  // Remove "Name: " from the beginning
        $stars = $lines[1]; // Remove "Stars: " from the beginning
        $text = $lines[2];  // Remove "Text: " from the beginning

        return array($name, $stars, $text);
    }
?>

<script>
    $(document).ready(function () {
        // Function to log clicked links
        function logClickedLink(link) {
            $.ajax({

                type: 'POST',
                url: '../log_link.php', // Replace with the actual path to your server-side script
                data: { link: link },
                success: function (response) {
                    // Update the displayed clicked links
                    $('#clicked-links').text(response);
                }
                window.location=this.href
            });
        }

        // Attach click event to links
        $('.track-link').click(function (event) {
            event.preventDefault();
            var linkText = $(this).data('link');
            logClickedLink(linkText);
        });
    });
</script>