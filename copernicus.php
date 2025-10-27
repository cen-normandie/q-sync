<?php
session_start(); 
include 'php/properties.php';
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
                    <span class="input-group-text" id="">client_id</span>
                    <input id="client_id" type="text" class="form-control" placeholder="aaaaa-eeeee-bbbbb" aria-label="aaaaa-eeeee-bbbbb"></input>
                </div>
                <div class="input-group input-group-sm my-2">
                    <span class="input-group-text" id="">client_secret</span>
                    <input id="client_secret" type="text" class="form-control" placeholder="aaaaa-eeeee-bbbbb" aria-label="aaaaa-eeeee-bbbbb"></input>
                </div>
                <button id="token" class="btn btn-success align-items-start">generate token</button>
                <div  class="mt-2 p-2 border border-dark bg-light text-break" style="height:200px; overflow:auto; width:100%;">
                    <p id="access_token"></p>
                </div>

            </div>
<!--             <div class="d-flex flex-column col-1 p-2 border border-dark bg-dark ">
                <div class="vr h-100"></div>
            </div> -->

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


<script type="text/javascript" src="copernicus/copernicus.js"></script>  
</body>
</html>
