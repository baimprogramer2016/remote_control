<div class="page-header">
                <h3 class="page-title">Floor</h3>
              </div>
<div class="row">
<?php
  include('getdata.php');
  $floor = getDataFloorAc();

  foreach($floor as $item)
  {
  
      $floor = $item['floor'];
      $desc = $item['desc'];
   
?>
  <div class="col-md-3 col-xl-3 mb-3 ">
            
              <div class="card ">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $desc;?></h4>
                      <img class="rounded" width="100%" src="assets/images/<?php echo $item['image'];?>" alt="">
                  
                      <p class="text-muted"></p>
                      <button
                                    class="btn btn-inverse-primary"
                                    onclick="return goFloor('<?php echo $floor;?>')"
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
  function goFloor(param) {
    // alert("hai");
    location.href = "main.php?page=setting-ac&floor="+param;
  }
</script>