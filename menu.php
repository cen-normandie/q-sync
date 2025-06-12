<div class="d-flex flex-column col-md-3 col-lg-2 h-100 bg-dark sticky-top " style="min-height:100vh;">
<div class="d-flex justify-content-center mt-2 align-iems-center w-100">
<div class="text-light mx-2"><img id='cen_n_img' src="img/CenNormandie.png" style="max-width:80px;max-height: 40px;opacity:0.8;"/></div>
<h1 class="text-light mx-2 moonflower">Qsync</h1>
</div>
<ul class="nav flex-column">
    
  <div class="ml-2">
      <span class="nav-link text-secondary">Qsync :</span>
      

<?php 
 if ($_SESSION['is_equipe_si']) {
    echo '        <a class="nav-link py-1';
    $t = ((($_POST['page']) == 'dashboard.php') ? ' active' : '' ); echo $t;
    echo '"  href="dashboard.php">
          <span data-feather=""></span>
          <i class="fas fa-tachometer-alt"></i> dashboard
        </a>';
  } 
?>

  </div>
</ul>
</div>