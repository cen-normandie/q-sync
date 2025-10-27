<?php
include '../../php/properties.php';
function showDownloadLinks($token, $collection, $dateFrom, $dateTo = null) {
    $dateTo = $dateTo ?: $dateFrom;

    $payload = [
        "collections" => [$collection],
        "datetime" => "$dateFrom/$dateTo",
        "limit" => 10
    ];

    $ch = curl_init('https://stac.dataspace.copernicus.eu/v1/search');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (empty($data['features'])) {
        return "<div class='alert alert-warning'>Aucun item trouvé pour cette période.</div>";
    }

    $html = "<div class='list-group'>";



foreach ($item['assets'] as $key => $asset) {
    if (strpos($asset['href'], '.tif') !== false) {
        $html .= "<li>
                    <a href='" . $asset['href'] . "' download class='list-group-item list-group-item-action' target='_blank'>
                        <strong>" . htmlspecialchars($key) . "</strong> (" . htmlspecialchars($date) . ")
                    </a>
                  </li>";
    }
}





    $html .= "</div>";
    return $html;
}

$token = $_POST['token'] ?? '';
$dateFrom = $_POST['date_from'] ?? '';
$dateTo = $_POST['date_to'] ?? '';
$collections = $_POST['collection_id'] ?? [];

if (!$token || !$dateFrom || empty($collections)) {
    echo "<div class='alert alert-danger'>Paramètres manquants.</div>";
    exit;
}

foreach ($collections as $collection) {
    echo "<h4 class='mt-3'>Collection : $collection</h4>";
    echo showDownloadLinks($token, $collection, $dateFrom, $dateTo);
}