<?php 
include('../../function.php');
   //mendapatkan status ac seluruhnya
   $dataStatusAcFromApi      = statusLightingFromApi();
   
if(count($dataStatusAcFromApi) == 0){
  ?>
    <div class="alert alert-warning" role="alert">
    Warning, Connection to API state is lost and Remote Control not Working
    </div>
  <?php
}else{
    echo "ada";
};
?>