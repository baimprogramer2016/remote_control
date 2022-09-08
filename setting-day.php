<link rel="stylesheet" href="assets/css/toggle.css" />
<div class="page-header">
    <h3 class="page-title">Day Settings</h3>
</div>
<div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                      <!-- <h4 class="card-title mb-1"></h4> -->
                      <!-- <p class="text-muted mb-1">Remote</p> -->
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="preview-list">
                        <?php
                         include('function.php');
                         $dayactive = statusDayActive();
                         
                         foreach($dayactive as $item_day)
                         {        
                         if($item_day['status'] == 1)
                         {
                            $checkday = 'checked';
                         }
                         else{
                            $checkday = '';
                         }                    
                        ?>
                          <div class="preview-item border-bottom">
                            <div
                              class="preview-item-content d-sm-flex flex-grow"
                            >
                              <div class="flex-grow">
                                <h6 class="preview-subject"><?php echo $item_day['hari'];?></h6>
                              </div>
                              <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                <div class="row d-flex justify-content-center">
                                  <label class="skeuo__switch mt-2">
                                    <input
                                    <?php echo $checkday; ?>
                                      type="checkbox"
                                      id="checked_<?php echo $item_day['hari']; ?>"
                                      class="skeuo__input"
                                      onClick="return runDayActive('<?php echo $item_day['hari'];?>')"
                                    />

                                    <div class="skeuo__rail">
                                      <span class="skeuo__circle"></span>
                                    </div>

                                    <span class="skeuo__indicator"></span>
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php
                         }
                         ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script> 
<script>
function runDayActive(param_hari){
  param_status    = document.getElementById('checked_'+param_hari).checked;  
  param_hari      = param_hari;

  $.ajax({
              type : "POST",  //type of method
              url  : "process.php?type=scheduler-day-submit",  //your page
              data : { hari : param_hari, status : param_status},// passing the values
              success: function(response){  
              result  = JSON.parse(response);
              // console.log(response);
             
              }  
    });
}

</script>