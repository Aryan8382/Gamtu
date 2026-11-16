<?php
// get_instagram_reels.php
// Public JSON endpoint. about.html's JavaScript calls this via fetch().
// Returns: [ { "embed_url": "...", "title": "..." }, ... ]  (up to 4 items)

header('Content-Type: application/json; charset=utf-8');

// Basic CORS safety: only needed if this file is ever called from a
// different domain than the one it's hosted on. Same-origin calls (the
// normal case - about.html and this file on the same site) don't need it.
// header('Access-Control-Allow-Origin: https://gamtuindia.com');

require_once __DIR__ . '/fetch_instagram_reels.php'; // sets $reels

echo json_encode($reels ?? [], JSON_UNESCAPED_SLASHES);