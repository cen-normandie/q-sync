<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Indicateurs écologiques</title>
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
	<link href="../bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
	<!--<link href="../js/plugins/datatable/datatables.min.css" rel="stylesheet">-->
    <link href="../css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/cennormandie.css" rel="stylesheet">
	<link href="../css/autocomplete.dashboard.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="../fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
    <!--Datatable bs5-->
    <!--<link href="css/plugins/twitter_bs5.css" rel="stylesheet">-->
</head>
<body class="container py-4">
    <h1 class="mb-4">Indicateurs écologiques - Highstock</h1>

        <div class="col-md-2">
            <label class="form-label">Type de suivi</label>
            <select id="suivi" name="suivi" class="form-select">
                <option value="">--</option>
                <option value="releve_phyto">Relevé Phyto</option>
                <option value="carre_contact">Carré Contact</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label">Site</label><input      id="site" type="text" name="site" class="form-control" value=""></div>
        <div class="col-md-2"><label class="form-label">Année</label><input id="annee" type="number" name="annee" class="form-control" value=""></div>
        <div class="col-md-2"><label class="form-label">Plot</label><input id="plot" type="text" name="plot" class="form-control" value=""></div>
        <div class="col-md-2"><label class="form-label">Transect</label><input id="transect" type="text" name="transect" class="form-control" value=""></div>
        <div class="col-md-2"><label class="form-label">ID Relevé</label><input id="releve_id" type="number" name="releve_id" class="form-control" value=""></div>
        <div class="col-12"><button type="submit" class="btn btn-primary">Afficher</button></div>

    <div id="container_shannon" class="mb-5" style="height: 400px;"></div>
    <div id="container_richesse" class="mb-5" style="height: 400px;"></div>
    <div id="container_sumcoef" class="mb-5" style="height: 400px;"></div>


<script src="../js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="../bootstrap-5.0.0/js/bootstrap.min.js"></script>
<script src="../js/plugins/bs5-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="../js/plugins/bs5-datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
<!-- FONT AWESOME -->
<script src="../fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- HIGHCHARTS -->
<script type="text/javascript" src="../js/plugins/highcharts/code/highstock.js"></script>
<script type="text/javascript" src="../js/plugins/highcharts/code/modules/exporting.js"></script>  

<script type="text/javascript" src="sh_highstock.js"></script>  
</body>
</html>
