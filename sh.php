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
	<!--<link href="js/plugins/datatable/datatables.min.css" rel="stylesheet">-->
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <link href="sh/sh.css" rel="stylesheet">
	<link href="css/autocomplete.dashboard.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
    <!--Datatable bs5-->
    <!--<link href="css/plugins/twitter_bs5.css" rel="stylesheet">-->
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
            <div class="d-flex flex-column align-items-center col-3 ">
                <h4 class="d-flex bebas ">Filtres :</h4>
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
                    <input id="site" type="text" class="form-control" placeholder="76MAR / Chichebo" aria-label="76MAR / Chichebo ">
                    <ul id="suggestions"></ul>
                </div>
                <div class="input-group input-group-sm my-2">
                    <label class="input-group-text" for="annee">Année</label>
                    <select id="annee" name="annee" class="form-select">
                    </select>
                </div>
                <div class="input-group input-group-sm my-2">
                    <label class="input-group-text" for="plot">Plot</label>
                    <select id="plot" name="plot" class="form-select">
                    </select>
                </div>
                <div class="input-group input-group-sm my-2">
                    <label class="input-group-text" for="transect">Transect</label>
                    <select id="transect" name="transect" class="form-select">
                    </select>
                </div>
                <div class="input-group input-group-sm my-2">
                    <label class="input-group-text" for="releve_id">ID Relevé</label>
                    <select id="releve_id" name="releve_id" class="form-select">
                    </select>
                </div>
                <div class="my-2">
                    <button type="submit" class="btn btn-primary">Afficher</button>
                </div>

            </div>
            <div class="d-flex flex-column justify-content-start col-9">
                <h4 class="bebas">Indicateurs ecologiques</h4>
                <div class="d-flex flex-column w-100 m-2">
                    <div id="container_shannon" class="mb-5" style="height: 400px;"></div>
                    <div id="container_richesse" class="mb-5" style="height: 400px;"></div>
                    <div id="container_sumcoef" class="mb-5" style="height: 400px;"></div>
                </div>
            </div>

        </div>
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

<script type="text/javascript" src="sh/sh.js"></script>  
</body>
</html>
