<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start(); 
include 'php/properties.php';
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
};
if (!isset($_SESSION['password'])) {
    header('Location: index.php');
    exit();
};
if (!isset($_SESSION['session'])) {
    header('Location: index.php');
    exit();
};
$_SESSION['is_admin'] = false;
$admins = array("b.perceval@cen-normandie.fr");
if (in_array($_SESSION['email'], $admins)) {
    $_SESSION['is_admin'] = true;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Indicateurs écologiques</title>
    <link rel="shortcut icon" href="img/qgis.ico" />
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
	<link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
    <!--Datatable-->
	<link href="js/plugins/datatable/datatables.min.css" rel="stylesheet">
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <link href="sh/sh.css" rel="stylesheet">
	<link href="css/autocomplete.dashboard.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
    <!--Datatable bs5-->
    <!--Datatable bs5-->
    <link href="css/plugins/twitter_bs5.css" rel="stylesheet">
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
  <?php $_POST["page"] = basename(__FILE__);include("menu.php"); ?>
	<div class="d-flex flex-column col-md-9 col-lg-10 bg-light " >
		<div class="d-flex justify-content-end  bg-dark sticky-top">
			<div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?php echo $_SESSION['email']; ?></span></div>
			<div class="m-2"><a class="logout text-light" href="php/logout.php" ><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
		</div>
        <div class="d-flex" style="">
            <div class="d-flex flex-column align-items-center col-3 p-2">
                <h4 class="d-flex bebas align-items-start ">Filtres :</h4>
                <div class="input-group input-group-sm my-2">
                    <label class="input-group-text" for="suivi">Suivi</label>
                    <select id="suivi" name="suivi" class="form-select">
                        <option value="">--</option>
                        <option value="releve_phyto">Relevé Phyto</option>
                        <option value="carre_contact">Carré Contact</option>
                    </select>
                </div>
                <div class="input-group input-group-sm my-2">
                    <span class="input-group-text" id="">Site</span>
                    <input id="site" type="text" class="form-control" placeholder="76MAR / Chichebo" aria-label="76MAR / Chichebo "></input>
                    <button id="clear_site" class="btn btn-outline-secondary" type="button"><i class="fas fa-times"></i></button>
                    <ul id="suggestions"></ul>
                </div>
                <div class="input-group input-group-sm my-2">
                    <label class="input-group-text" for="annee">Année</label>
                    <select id="annee" name="annee" class="form-select" multiple>
                    </select>
                </div>
                <div class="input-group input-group-sm my-2">
                    <label class="input-group-text" for="releve_id">ID Relevé</label>
                    <select id="releve_id" name="releve_id" class="form-select" multiple>
                    </select>
                </div>
                <div class="my-2">
                    <button id="graphs" class="btn btn-primary align-items-start">Charger les graphiques</button>
                </div>
                <!-- Button trigger modal -->
                <div class="my-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#doc_indic_ecol">
                    Documentation des indicateurs écologiques
                    </button>
                </div>
            </div>
            <div class="d-flex flex-column justify-content-start col-9">
                <h4 class="bebas">Indicateurs ecologiques</h4> 
                <table id="IndicateursEcologiques" class="display">
                        <thead>
                            <tr>
                                <th>Année</th>
                                <th>ID Relevé</th>
                                <th>Site</th>
                                <th>Richesse spécifique</th>
                                <th>Indice de Shannon</th>
                                <th>Équitabilité</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                </table>
            </div>

        </div>
		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
		</div>
	</div>

<!-- Modal -->
<div class="modal fade" id="doc_indic_ecol" tabindex="-1" aria-labelledby="doc_indic_ecolLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="doc_indic_ecolLabel">Indicateurs Ecologiques</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

    <h4>Documentation des fonctions ind_eco_carre_contact et ind_eco_releve_phyto</h4>

    <h5>Objectif</h5>
    <p>Ces fonctions calculent trois indices écologiques à partir des données de relevés floristiques :</p>
    <ul>
        <li><strong>Richesse spécifique</strong> : nombre d'espèces distinctes (<code>taxon</code>)</li>
        <li><strong>Indice de Shannon</strong> : mesure de la diversité prenant en compte la répartition des abondances</li>
        <li><strong>Équitabilité</strong> : mesure de la régularité de la distribution des espèces</li>
    </ul>

    <h5>Paramètres</h5>
    <pre><code>
annee_param TEXT[]        -- Liste des années à filtrer
id_releve_param TEXT[]    -- Liste des identifiants de relevé
site_param TEXT           -- Nom du site à filtrer
    </code></pre>

    <h5>Source des données</h5>
    <ul>
        <li><code>sh.carre_contact</code> : chaque ligne représente une observation d’une espèce dans un carré.</li>
        <li><code>sh.releve_phyto</code> : chaque ligne représente une observation d’une espèce dans un relevé.</li>
    </ul>

    <h5>Méthode de calcul</h5>
    <ul>
        <li><strong>Richesse spécifique</strong> : calculée par <code>COUNT(DISTINCT taxon)</code></li>
        <li><strong>Indice de Shannon</strong> : <code>H' = -∑ pᵢ log(pᵢ)</code>, où <code>pᵢ</code> est la proportion relative d’abondance</li>
        <li><strong>Équitabilité</strong> : <code>E = H' / log(S)</code>, où <code>S</code> est la richesse spécifique</li>
    </ul>

    <h6>Dans carre_contact</h6>
    <p>Les proportions sont calculées à partir du <strong>nombre d’occurrences</strong> de chaque espèce.</p>

    <h6>Dans releve_phyto</h6>
    <p>Les proportions sont calculées à partir de la colonne <code>coefficient_int</code>, qui représente une <strong>valeur semi-quantitative</strong>.</p>

    <h4>À propos de coefficient_int</h4>
    <p>La colonne <code>coefficient_int</code> est une <strong>valeur entière obtenue par traduction des indices de Braun-Blanquet</strong> vers une échelle numérique. Ces indices sont utilisés en phytosociologie pour estimer :</p>
    <ul>
        <li>la fréquence d’apparition,</li>
        <li>le recouvrement,</li>
        <li>et la dominance des espèces dans un relevé.</li>
    </ul>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Symbole</th>
                <th>Description</th>
                <th>Recouvrement estimé</th>
                <th>Classe</th>
                <th>Valeur numérique</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>1</td><td>r</td><td>Espèce très rare, 1 ou 2 individus</td><td>&lt; 1%</td><td>1</td><td>0.15</td></tr>
            <tr><td>2</td><td>+</td><td>Quelques individus, très faible recouvrement</td><td>≈ 1%</td><td>2</td><td>0.2</td></tr>
            <tr><td>3</td><td>1</td><td>Nombreux individus, faible recouvrement</td><td>&lt; 5%</td><td>2</td><td>1</td></tr>
            <tr><td>4</td><td>2a</td><td>Recouvrement modéré, distinctions selon dominance</td><td>5–25%</td><td>3</td><td>2</td></tr>
            <tr><td>5</td><td>2m</td><td>Recouvrement modéré, distinctions selon dominance</td><td>5–25%</td><td>3</td><td>2</td></tr>
            <tr><td>6</td><td>2b</td><td>Recouvrement modéré, distinctions selon dominance</td><td>5–25%</td><td>3</td><td>2</td></tr>
            <tr><td>7</td><td>3</td><td>Espèce dominante</td><td>25–50%</td><td>4</td><td>3</td></tr>
            <tr><td>8</td><td>4</td><td>Très dominante</td><td>50–75%</td><td>5</td><td>4</td></tr>
            <tr><td>9</td><td>5</td><td>Recouvrement quasi total</td><td>75–100%</td><td>6</td><td>5</td></tr>
            <tr><td>10</td><td>i</td><td>Espèce observée hors du relevé</td><td>&lt; 1%</td><td>1</td><td>0.1</td></tr>
        </tbody>
    </table>

    <h3>Conclusion</h3>
    <ul>
        <li><code>coefficient_int</code> est plus adapté que le simple comptage pour les indices de Shannon et d’équitabilité.</li>
        <li>La richesse spécifique reste calculée par le nombre d’espèces distinctes.</li>
    </ul>

      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>







</div>

<script src="js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
<script src="js/plugins/bs5-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="js/plugins/bs5-datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
<!-- FONT AWESOME -->
<script src="fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- HIGHCHARTS -->
<script type="text/javascript" src="js/plugins/highcharts/code/highstock.js"></script>
<script type="text/javascript" src="js/plugins/highcharts/code/modules/exporting.js"></script>  
<!--Datatable bs5-->
<script src="js/plugins/datatable/datatables.min.js"></script>
<script src="js/plugins/datatable/dataTables.bootstrap5.min.js"></script>


<script type="text/javascript" src="sh/sh.js"></script>  
</body>
</html>
