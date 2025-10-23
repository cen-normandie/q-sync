<?php
// Liste des cd_nom à interroger
$cd_nom_list = [12345, 67890, 112233]; // Remplace par tes codes TaxRef

// URL de base de l'API
$base_url = "https://api.tela-botanica.org/service:ev/1/phytosocio/cd_nom/";

// Clé API (si nécessaire)
$api_key = "VOTRE_CLE_API"; // Inscris-toi sur Tela-Botanica pour obtenir une clé

// Tableau pour stocker les résultats
$results = [];

foreach ($cd_nom_list as $cd_nom) {
    $url = $base_url . $cd_nom;

    // Préparer la requête avec la clé API si nécessaire
    $options = [
        "http" => [
            "header" => "Authorization: Bearer " . $api_key
        ]
    ];
    $context = stream_context_create($options);

    // Appel API
    $response = file_get_contents($url, false, $context);

    if ($response !== false) {
        $data = json_decode($response, true);

        $results[] = [
            "cd_nom" => $cd_nom,
            "associations" => $data["associations"] ?? [],
            "alliances" => $data["alliances"] ?? [],
            "ordres" => $data["ordres"] ?? [],
            "classes" => $data["classes"] ?? []
        ];
    } else {
        echo "Erreur pour cd_nom $cd_nom\n";
    }
}

// Affichage des résultats
header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT);
?>