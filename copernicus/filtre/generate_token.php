<?php
header('Content-Type: text/plain');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo "Méthode non autorisée";
  exit;
}

$client_id = $_POST['client_id'] ?? '';
$client_secret = $_POST['client_secret'] ?? '';

if (!$client_id || !$client_secret) {
  http_response_code(400);
  echo "Paramètres manquants";
  exit;
}

$ch = curl_init('https://identity.dataspace.copernicus.eu/auth/realms/CDSE/protocol/openid-connect/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
  'grant_type' => 'client_credentials',
  'client_id' => $client_id,
  'client_secret' => $client_secret
]));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if (isset($data['access_token'])) {
  echo $data['access_token'];
} else {
  http_response_code(401);
  echo "Erreur d'authentification";
}