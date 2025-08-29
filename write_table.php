<?php session_start(); 
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
<!doctype html>
<html lang="fr">
  <head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Q-Sync</title>
    <link rel="shortcut icon" href="img/qgis.ico" />
    <script>L_PREFER_CANVAS = true;</script>
    
    

    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
    <link href="js/plugins/bs5-datepicker/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
	<link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
	<link href="js/plugins/datatable/datatables.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
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
        <div class="d-flex flex-column justify-content-end" style="">
            <div class="d-flex flex-column w-100">
                <div class="d-flex align-items-center justify-content-start bg-light text-dark m-2">
                    <h4 class="bebas my-0">Gestion du contenu Nextcloud :</h4>
                    <div id="loader" class=" bg-success loader my-0 mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="min-height: 1rem;"></div>    
                </div>
                <div class="d-flex align-items-center justify-content-start bg-light text-dark m-2">
                    <p>Remplacer les tables, les geopackages ou les projets QGIS depuis le dossier _qfield_skeleton pour l'ensemble des utilisateurs nextcloud</p>
                </div>
                <div class="d-flex w-100">
                    <div class="d-flex flex-column col-sm-6 col-md-4 col-lg-3">
                        <div class="d-flex align-items-center justify-content-start bg-dark text-light">
                            <h4 class="bebas my-0" >Observations.gpkg</h4>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center"><span class="fw-bolder">remplacer observation.gpkg</span>     <button id="gpkg__observations" type="button" class="btn btn-danger"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center"><span class="fw-bolder">remplacer o_tax.qgz</span>     <button id="qgz__o_tax" type="button" class="btn btn-success"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">meta_qsync                            <button id="w_meta_qsync" table="meta_qsync" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">faune (obs_faune)                     <button id="w_obs_faune" table="obs_faune" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">flore (obs_flore)                     <button id="w_obs_flore" table="obs_flore" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">carre contact (obs_cc)                     <button id="w_obs_cc" table="carre_contact" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">liste des observateurs (observateurs) <button id="w_observateurs" table="observateurs" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">liste des taxons faune (tx_faune)     <button id="w_tx_faune" table="tx_faune" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">liste des taxons flore (tx_flore)     <button id="w_tx_flore" table="tx_flore" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">JDD faune (jdd_faune_)                <button id="w_jdd_faune_" table="jdd_faune_" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">JDD flore (jdd_flore_)                <button id="w_jdd_flore_" table="jdd_flore_" gpkg="observations.gpkg" type="button" class="btn btn-dark"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-center justify-content-start bg-dark text-light">
                            <h4 class="bebas my-0" >n2k.gpkg</h4>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center"><span class="fw-bolder">remplacer n2k.gpkg</span>     <button id="gpkg__n2k" type="button" class="btn btn-danger"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center"><span class="fw-bolder">remplacer n2k.qgz</span>     <button id="qgz__n2k" type="button" class="btn btn-success"><i class="fas fa-retweet text-light px-1 fs-5"></i></button></li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-center justify-content-start bg-dark text-light">
                            <h4 class="bebas my-0" >Delete files</h4>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center"><input id="delete_file_name" type="text" class="fw-bolder" value="" >     <button id="delete_file" type="button" class="btn btn-danger"><i class="fas fa-trash"></i></button></li>
                            </ul>
                        </div>
                        <div class="d-flex w-100 p-2 text-light bg-dark justify-content-end">
                            <h4 class="bebas my-0" >...</h4>
                        </div>
                    </div>
                    <div class="d-flex flex-column col-sm-6 col-md-8 col-lg-9 bg-dark text-light">
                        <div class="d-flex align-items-center justify-content-start bg-dark text-light p-2">
                            <p id="output_" class="d-flex flex-column m-0 " style="overflow-y: auto;max-height: 100%;">
                            </p>
                        </div>
                    </div>
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

<!--Datatable bs5-->
<script src="js/plugins/datatable/datatables.min.js"></script>
<script src="js/plugins/datatable/dataTables.bootstrap5.min.js"></script>
<!-- <script src="js/plugins/datatable/jquery.datatables.min.js"></script> -->
<script src="js/plugins/datatable/Buttons-1.7.0/js/dataTables.buttons.min.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.bootstrap5.min.js"></script>
<script src="js/plugins/datatable/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.html5.min.js"></script>

<!-- general.js -->
<script type="text/javascript" src="js/general/general.js" ></script>
<!-- Empty.js -->
<script type="text/javascript" src="js/write_table.js" ></script>
<script type="text/javascript">


$(document).ready(function() {

});




</script>

  </body>
</html>
