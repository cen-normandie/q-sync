<?php
function getAccessToken($client_id, $client_secret) {
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
  return $data['access_token'] ?? null;
}



function searchStac($token, $collection, $from, $to) {
  $payload = [
    "collections" => [$collection],
    "datetime" => "$from/$to",
    "limit" => 1
  ];
  $ch = curl_init('https://stac.dataspace.copernicus.eu/v1/search');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
  ]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);
}

function downloadAsset($url, $token, $filename) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token
  ]);
  $data = curl_exec($ch);
  curl_close($ch);
  file_put_contents($filename, $data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $client_id = $_POST['client_id'];
  $client_secret = $_POST['client_secret'];
  $date_from = $_POST['date_from'];
  $date_to = $_POST['date_to'] ?? $_POST['date_from'];
  $collection = 'CGLS_FCOVER300_V1_GLOBAL';

  $token = getAccessToken($client_id, $client_secret);
  if (!$token) {
    echo "Erreur d'authentification.";
    exit;
  }

  $stac = searchStac($token, $collection, $date_from, $date_to);
  if (empty($stac['features'])) {
    echo "Aucun item trouvé.";
    exit;
  }

  $item = $stac['features'][0];
  $asset_url = $item['assets']['LBEFORE']['href'] ?? null;
  if (!$asset_url) {
    echo "Asset LBEFORE introuvable.";
    exit;
  }

  $filename = "FCOVER_LBEFORE_" . str_replace('-', '', $date_from) . ".tif";
  downloadAsset($asset_url, $token, $filename);
  echo 'Téléchargement réussi : <a href="'.$filename.'">'.$filename.'</a>
<!DOCTYPE html>
<html>
<head><title>Téléchargement FCOVER</title></head>
<body>
  <h2>Formulaire de téléchargement FCOVER300 LBEFORE</h2>
  <form method="post">
    <label>Client ID : <input type="text" name="client_id" required></label><br>
    <label>Client Secret : <input type="text" name="client_secret" required></label><br>
    <label>Date (AAAA-MM-JJ) : <input type="date" name="date_from" required></label><br>
    <label>À (optionnel) : <input type="date" name="date_to"></label><br>
    <button type="submit">Télécharger</button>
  </form>
</body>
</html>