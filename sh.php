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
            <div class="d-flex flex-column align-items-center col-3 p-2 border border-dark">
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
            </div>
            <div class="d-flex flex-column col-1 p-2 border border-dark bg-dark ">
                <div class="vr h-100"></div>
            </div>
            <div class="d-flex flex-column col-8 p-2 border border-dark">
                <div class="d-flex flex-column align-items-center w-100 mb-2">
                    <h4 class="d-flex bebas ">Indicateurs ecologiques</h4> 
                </div>
                <table id="IndicateursEcologiques" class="display w-100">
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
                <div id="appartenance_graph" style="height: 500px; min-width: 600px"></div>
                
            </div>

        </div>
        <div class="d-flex vr h-100 bg-dark"></div>
		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
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
<!-- <script src="js/plugins/datatable/jquery.datatables.min.js"></script> -->
<!--<script src="js/plugins/datatable/Buttons-1.7.0/js/dataTables.buttons.min.js"></script>-->
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.bootstrap5.min.js"></script>
<script src="js/plugins/datatable/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.html5.min.js"></script>


<script type="text/javascript" src="sh/sh.js"></script>  
</body>
</html>
