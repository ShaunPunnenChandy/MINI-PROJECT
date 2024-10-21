<?php
// Replace 'YOUR_API_KEY' with your actual YouTube Data API key
define('API_KEY', 'YOUR_API_KEY');

// Function to fetch YouTube search results
function fetchYouTubeResults($query) {
    $url = 'https://www.googleapis.com/youtube/v3/search?' . http_build_query([
        'part' => 'snippet',
        'q' => $query,
        'type' => 'video',
        'key' => API_KEY,
        'maxResults' => 5 // Limit results to 5
    ]);

    $response = file_get_contents($url);
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
                <?php if (isset($results['items']) && !empty($results['items'])): ?>
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
                    <p>No results found.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
