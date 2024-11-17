<?php
//DB connection and configurations
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "galerts");

//google api credentials
define("GOOGLE_CLIENT_ID", "76063403754-egaua2ha5d9b5j5mtp8qk7cas3v2uvlt.apps.googleusercontent.com");
define("GOOGLE_CLIENT_SECRET", "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");
define("GOOGLE_OAUTH_SCOPE", "https://www.googleapis.com/auth/calendar");
define("REDIRECT_URL", "http://localhost/galerts/google_calendar_event_sync.php");

//Google OAUTH Url
$googleOauthURL = "https://accounts.google.com/o/oauth2/auth?scope=" . urlencode(GOOGLE_OAUTH_SCOPE) . "&redirect_uri=" . REDIRECT_URL . "&response_type=code&client_id=" . GOOGLE_CLIENT_ID . "&access_type=online";

DEFINE("GOOGLE_OAUTH_URL", $googleOauthURL);

//start session
if (!session_id()) session_start();
