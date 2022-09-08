<?php 
 include('function.php');
 $datascheduler = getAllDataAc();
?>


<div class="page-header">
<h3 class="page-title">List Schedule</h3>
            </div>
            <div class="row" id="rowListRoom">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                      <h4 class="card-title mb-1">Air Conditioner</h4>
                      <!-- <p class="text-muted mb-1">Remote</p> -->
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="preview-list">
                              <?php
                              
                            
                              foreach($datascheduler as $value)
                              {
                                $id           = $value['id'];
                                $description  = $value['description'];
                                $address      = $value['address'];
                                $port         = $value['port'];
                                $dataac       = $value['dataac'];
                                $onscheduler  = $value['onscheduler'];
                                if($onscheduler == 1)
                                {
                                  $notfischeduler = "<span class='text-success'>On Schedule</span>";
                                }else{
                                  $notfischeduler = "<span class='text-warning'>No Schedule</span>";
                                }

                              ?>
                              <div class="preview-item border-bottom">
                                <div
                                  class="preview-item-content d-sm-flex flex-grow"
                                >
                                <div class="row w-100 text-search">
                                    <div class="col-md-3">
                                      <h6 class="preview-subject "><?php echo $description;?></h6>
                                      <p class="text-muted mb-0">
                                      <?php echo  $notfischeduler; ?>
                                      </p>
                                    </div>
                                    <div class="col-md-3">
                                      <h6 class="preview-subject">Address : Port</h6>
                                      <p class="text-muted mb-0">
                                      <span ><?php echo $address.' : '.$port;?></span>
                                      </p>
                                    </div>
                                    <div class="col-md-3 ">
                                      <h6 class="preview-subject">Schedule</h6>
                                      <div class="row w-100" >
                                      <?php
                                      $datacount = count($dataac);
                                      if($datacount !=0)
                                      {
                                        foreach($dataac as $val)
                                        {
                                          if($val['statusschedule'] == 'on')
                                          {
                                            $colorstatus = 'success';
                                          }
                                          else
                                          {
                                            $colorstatus = 'danger';
                                          }
                                          $statusschedule = $val['statusschedule'];
                                          $timeschedule = $val['timeschedule'];
                                        ?>
                                          <div
                                            class="col-md-4 col-sm-12 mb-1 mr-1 badge badge-outline-<?php echo $colorstatus;?>"
                                          >
                                            <?php echo ucfirst($statusschedule).' '.$timeschedule;?>
                                          </div>
                                          
                                      <?php
                                          }
                                      }
                                      ?>
                                      </div>
                                    </div>
                                    <div class="col-md-3" style="text-align:right;">
                                      <h6 class="preview-subject"></h6>
                                      <button
                                    class="btn btn-inverse-primary"
                                    onclick="return goToUpdateAc('<?php echo $id;?>')"
                                  >
                                    Update
                                  </button>
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
         
<script>
  document.getElementById("search").focus();
  function goToUpdateAc(param) {
    location.href = "main.php?page=schedule-ac-edit&param_id="+param;
  }

  function searchList() {
      var input, filter, cards, cardContainer, title, i;

      input = document.getElementById("search");
      filter = input.value.toUpperCase();
      console.log(filter);
      cardContainer = document.getElementById("rowListRoom");
      cards = cardContainer.getElementsByClassName("preview-item");
      for (i = 0; i < cards.length; i++) {
        title = cards[i].querySelector(".text-search");
        if (title.innerText.toUpperCase().indexOf(filter) > -1) {
          cards[i].style.display = "";
        } else {
          cards[i].style.display = "none";
        }
      }
  }

</script>