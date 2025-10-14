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
        <div class="input-group  input-group-sm col-md-2">
            <span class="input-group-text" id="basic-addon1">Site</span>
            <input id="site" type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
        </div>
        <div class="col-md-2"><label class="form-label">Année</label>
            <select id="annee" name="annee" class="form-select">
                <option value="">--</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label">Plot</label>
            <select id="plot" name="plot" class="form-select">
                <option value="">--</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label">Transect</label>
            <select id="transect" name="transect" class="form-select">
                <option value="">--</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label">ID Relevé</label>
            <select id="releve_id" name="releve_id" class="form-select">
                <option value="">--</option>
            </select>
        </div>
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
