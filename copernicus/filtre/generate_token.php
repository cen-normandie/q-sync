
<?php
// Paramètres écrits en dur
//$client_id = 'sh-05b192bf-0bee-4941-b8a0-a49bbce72ab4';
//$client_secret = '13EAbEUifvlj3QKWQOdit13qg0s7P8dH';
$_POST['client_id'] = $client_id ?? 'sh-05b192bf-0bee-4941-b8a0-a49bbce72ab4';
$_POST['client_secret'] = $client_secret ?? '13EAbEUifvlj3QKWQOdit13qg0s7P8dH';

// URL d'authentification Copernicus Data Space
$url = 'https://identity.dataspace.copernicus.eu/auth/realms/CDSE/protocol/openid-connect/token';

// Préparer la requête POST
$postFields = http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $client_id,
    'client_secret' => $client_secret
]);

// Initialiser cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

// Exécuter la requête
$response = curl_exec($ch);
if (curl_errno($ch)) {
    die('Erreur cURL : ' . curl_error($ch));
}
curl_close($ch);

// Décoder la réponse JSON
$data = json_decode($response, true);

// Afficher le token ou une erreur
if (isset($data['access_token'])) {
    echo "" . $data['access_token'];
} else {
    echo "" . ($data['error_description'] ?? 'Impossible d’obtenir le token');
}
