<?php
require_once __DIR__ . '/app/OpenIDConnect/OpenIDConnectClient.php';

$oidc = new \Jumbojett\OpenIDConnectClient(
    'https://example.com',
    'client_id',
    'client_secret'
);

echo "OpenID Connect Client loaded successfully!";
