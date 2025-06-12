<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cen Normandie</title>
    <link rel="shortcut icon" href="img/CenNormandie.ico" />
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
    <link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
	<link href="css/cennormandie.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
	<div class="d-flex flex-column col-md-3 col-lg-2 h-100 bg-dark sticky-top " style="min-height:100vh;">
		<div class="d-flex flex-column justify-content-between align-items-between w-100" style="min-height:100vh;">
			<div class="d-flex ">
				<div class="d-flex justify-content-center mt-2 align-items-center w-100">
				<img src="img/CenNormandie.png" style="max-width:80px;max-height: 40px;opacity:0.8;"/>
					<div class="text-light mx-2">
						<h1 class="text-light mx-2 moonflower">Qsync</h1>
					</div>
				</div>
			</div>
			<div class="d-flex flex-grow-1 ">
				<div id="" class="d-flex flex-column justify-content-end w-100">
					<div class="d-flex justify-content-around fs-6 my-2 text-secondary">
							<div><i class="fas fa-otter"></i></div>
							<div><i class="fas fa-spider"></i></div>
							<div><i class="fas fa-kiwi-bird"></i></div>
							<div><i class="fas fa-fish"></i></div>
							<div><i class="fas fa-crow"></i></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="d-flex flex-column col-md-9 col-lg-10 bg-light " >
		<div class="d-flex justify-content-end  bg-dark sticky-top">
			<p>.</p>
		</div>
        <div class="d-flex flex-column justify-content-end" style="">
			<div class="d-flex align-items-start flex-column" style="">
				<div class="p-4 w-100"><h2>Qsync</h2></div>
				<div class="p-4 col-6">
					<form role="form">
						<div class="input-group w-50">
							<span class="input-group-text justify-content-center col-2" id="user"><i class="fas fa-user"></i></span>
                            <input id="courriel" type="text" class="form-control col-10 " placeholder="Identifiant de session " aria-label="courriel@mail.com" aria-describedby="user" tabindex="1">
                        </div>
						<div class="input-group w-50 my-2">
							<span class="input-group-text justify-content-center col-2" id="passwordLabel"><i class="fas fa-key"></i></span>
                            <input id="pwd" type="password" class="form-control col-10 " placeholder="MoTDePassE" aria-label="MoTDePassE" aria-describedby="passwordLabel" tabindex="2" >
                        </div>
						<div class="my-2">
							<button type="button" id="signin" class="btn btn-primary" tabindex="3">Valider</button>
						</div>
					</form>
				</div>
				<div class="p-4 w-50">
					<div class="d-flex justify-content-arround">
					</div>
				</div>
				<div class="mt-auto p-4">
				</div>
			</div>
		</div>
		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
		</div>
	</div>
</div>
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
	<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
    <script src="js/plugins/jquery-ui-1.12.1.custom/jquery-ui.js" ></script>
    <!--Custom-->
    <script src="js/index.js"></script>
    
    <script>
$(document).ready(function(){
});
    </script>
</body>
</html>
