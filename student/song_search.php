<?php
// Replace 'YOUR_API_KEY' with your actual YouTube Data API key
define('API_KEY', 'AIzaSyCIZw7Sdy9z49oIygIeb4lW1yiY2R9ME0E');

// Function to fetch YouTube search results
function fetchYouTubeResults($query) {
    $url = 'https://www.googleapis.com/youtube/v3/search?' . http_build_query([
        'part' => 'snippet',
        'q' => $query,
        'type' => 'video',
        'key' => API_KEY,
        'maxResults' => 5 // Limit results to 5
    ]);

    $response = @file_get_contents($url); // Suppress warnings
    if ($response === FALSE) {
        return null; // Return null if request fails
    }

    return json_decode($response, true);
}

// Check if a search query is provided
$searchQuery = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';

$results = [];
if ($searchQuery) {
    $results = fetchYouTubeResults($searchQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song Search | SonicScholar</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light">
            <div class="position-sticky">
                <h4 class="text-center my-4">SonicScholar</h4>
                <div class="d-grid gap-2">
                <a class="btn btn-secondary btn-block mb-2" href="dashboard.php">
                            <i class="fa fa-list"></i> Dashboard
                        </a>
                    <a class="btn btn-primary btn-block mb-2" href="tutorials.php">
                        <i class="fa fa-book"></i> Course Materials
                    </a>
                    <a class="btn btn-warning btn-block mb-2" href="virtualkeyboard/index.php">
                        <i class="fa fa-music"></i> Musical Keyboard
                    </a>
                    <a class="btn btn-info btn-block mb-2" href="ebooks.php">
                        <i class="fa fa-book"></i> e-Books
                    </a>
                    <a class="btn btn-success btn-block mb-2" href="feedback.php">
                        <i class="fa fa-comments"></i> Feedback
                    </a>
                    <a class="btn btn-primary btn-block mb-2" href="song_search.php">
                        <i class="fa fa-search"></i> Song Search
                    </a>
                    <a class="btn btn-danger btn-block mb-2" href="logout.php">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </div>
            </div>
        </nav>
    <div class="container mt-4">
        <h1>Song Search</h1>
        <form method="GET" action="song_search.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Search for songs" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <?php if ($searchQuery): ?>
            <h2>Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
            <div class="row">
                <?php if ($results && isset($results['items']) && !empty($results['items'])): ?>
                    <?php foreach ($results['items'] as $item): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <iframe width="100%" height="200" src="https://www.youtube.com/embed/<?php echo $item['id']['videoId']; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($item['snippet']['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($item['snippet']['description']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No results found or API error.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
