<?php
include '../../php/properties.php';
$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");

if (!$conn) {
    echo json_encode(["error" => "Connexion à la base échouée"]);
    exit;
}

// Récupération des paramètres POST
$annees = $_POST['annees']; // tableau JSON
$plots = $_POST['plots'];   // tableau JSON
$site = $_POST['site'];
$type_suivi = $_POST['type_suivi']; // 'carre_contact' ou 'releve_phyto'


// Conversion en format PostgreSQL ARRAY
$annees_pg = '{' . implode(',', array_map(function($a) { return '"' . $a . '"'; }, $annees)) . '}';
$plots_pg = '{' . implode(',', array_map(function($p) { return '"' . $p . '"'; }, $plots)) . '}';

if ($type_suivi == "carre_contact") {
    $type_suivi = "carre_contact";
    // Préparation de la requête
    $sql = "
        SELECT * FROM sh.ind_eco_carre_contact(
            $1::TEXT[],
            $2::TEXT[],
            $3::TEXT
        );
    ";
} else {
    $type_suivi = "releve_phyto";
    // Préparation de la requête
    $sql = "
        SELECT * FROM sh.ind_eco_releve_phyto(
            $1::TEXT[],
            $2::TEXT[],
            $3::TEXT
        );
    ";
}


pg_prepare($conn, "indicateurs_query", $sql);

// Exécution avec les paramètres
$result = pg_execute($conn, "indicateurs_query", [$annees_pg, $plots_pg, $site]);

if (!$result) {
    echo json_encode(["error" => "Erreur dans la requête"]);
    exit;
}

// Formatage des résultats
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

?>