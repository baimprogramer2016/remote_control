<div class="page-header">
                <h3 class="page-title">Lighting Area</h3>
              </div>
<div class="row">
<?php
  include('getdata.php');
  $area = getDataAreaLighting();

  foreach($area as $item)
  {
    if($item['area'] == null){
      $area = "Other";
    }else{
      $area = $item['area'];
    }
?>
  <div class="col-md-3 col-xl-3 mb-3 ">
            
              <div class="card ">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $area;?></h4>
                      <img class="rounded" width="100%" src="assets/images/<?php echo $item['image'];?>" alt="">
                  
                      <p class="text-muted"></p>
                      <button
                                    class="btn btn-inverse-primary"
                                    onclick="return goArea('<?php echo $area;?>')"
                                  >
                                    Go to List
                                  </button>
                    </div>
              </div>
  </div>
    <?php
    }
    ?>
</div>

<script>
  function goArea(param) {
    // alert("hai");
    location.href = "main.php?page=setting-lighting&area="+param;
  }
</script>