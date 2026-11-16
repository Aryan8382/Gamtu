<?php
// fetch_instagram_reels.php
// Sets $reels = [ ['embed_url' => ..., 'title' => ...], ... ] (up to 4 items)
// Include this file, then loop over $reels to render the cards.
// Relies on db.php (in the same folder) to provide a ready $conn (mysqli).

require_once __DIR__ . '/db.php';

$reels = [];

if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    // Don't die() on a public page - that would break the whole site if the
    // DB hiccups. Log it and let the page render with an empty $reels array
    // (the "coming soon" message will show instead).
    error_log('Instagram reels: no valid $conn from db.php');
} else {
    $sql = "
        SELECT reel_url, title
        FROM instagram_reels
        WHERE is_active = 1
        ORDER BY sort_order ASC, id ASC
        LIMIT 4
    ";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $url = rtrim(trim($row['reel_url']), '/');

            // Skip anything that isn't a real instagram.com link
            if (!preg_match('#^https://www\.instagram\.com/(reel|p)/[A-Za-z0-9_\-]+#', $url)) {
                continue;
            }

            $reels[] = [
                // Instagram's /embed suffix can be loaded directly in an
                // iframe without needing their JS embed widget.
                'embed_url' => $url . '/embed',
                'title'     => $row['title'] ?? '',
            ];
        }
    }

    // Don't close $conn here - db.php owns that connection and may be
    // reused elsewhere; closing it here could break other code that
    // includes db.php later in the same request.
}