<?php

require_once '../vendor/autoload.php';

$clientConfig = new \fkooman\OAuth\Client\ClientConfig(
    array(
        "authorize_endpoint" => "http://localhost/OAuth2-Server/index.php/oauth2/authorize",
        "client_id" => "testclient",
        "client_secret" => "testpass",
        "token_endpoint" => "http://localhost/OAuth2-Server/index.php/oauth2/token",
    )
);

try {
    $tokenStorage = new \fkooman\OAuth\Client\SessionStorage();
    $httpClient = new \Guzzle\Http\Client();
    $cb = new \fkooman\OAuth\Client\Callback("foo", $clientConfig, $tokenStorage, $httpClient);
    $cb->handleCallback($_GET);

    header("HTTP/1.1 302 Found");
    header("Location: http://localhost/OAuth2-Client/example/index.php");
    exit;
} catch (\fkooman\OAuth\Client\AuthorizeException $e) {
    // this exception is thrown by Callback when the OAuth server returns a
    // specific error message for the client, e.g.: the user did not authorize
    // the request
    die(sprintf("ERROR: %s, DESCRIPTION: %s", $e->getMessage(), $e->getDescription()));
} catch (\Exception $e) {
    // other error, these should never occur in the normal flow
    die(sprintf("ERROR: %s", $e->getMessage()));
}
