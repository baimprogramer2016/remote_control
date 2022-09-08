<?php
include('getdata.php');
$datalogs   = getLogs();
$datacount  = $datalogs['datacount'];
$countdata  = $datalogs['countdata'];
$pagecount  = $datalogs['pagecount'];

?>

<link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />

<div class="page-header">
  <input type="hidden" id="counterlogs">
            <h3 class="page-title">History List</h3>
            </div>
            <div class="row">
              <div class="col-md-12 col-xl-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                      <h4 class="card-title">Activities</h4>
                      <p class="text-muted mb-1 small">Create Date</p>
                    </div>
                    <div class="preview-list" id="rowListRoom">
                      <?php
                      foreach($datalogs['datalog'] as $log)
                      {
                        //warna description
                        if($log['type'] == 'MANUAL COMPRESSOR' && $log['status'] == "ON")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['type'] == 'MANUAL COMPRESSOR' && $log['status'] == "OFF")
                        {
                            $col_mdi = 'text-danger';
                        }
                        //warna description
                        elseif($log['type'] == 'SCHEDULE LIGHTING' && $log['status'] == "ON")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['type'] == 'SCHEDULE LIGHTING' && $log['status'] == "OFF")
                        {
                            $col_mdi = 'text-danger';
                        }
                        elseif($log['type'] == 'SCHEDULE AC' && $log['status'] == "ON")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['type'] == 'SCHEDULE AC' && $log['status'] == "OFF")
                        {
                            $col_mdi = 'text-danger';
                        }
                        //warna description
                        elseif($log['type'] == 'MANUAL LIGHTING' && $log['status'] == "ON")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['type'] == 'MANUAL LIGHTING' && $log['status'] == "OFF")
                        {
                            $col_mdi = 'text-danger';
                        }
                        elseif($log['type'] == 'MANUAL AC' && $log['status'] == "ON")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['type'] == 'MANUAL AC' && $log['status'] == "OFF")
                        {
                            $col_mdi = 'text-danger';
                        }
                        elseif($log['type'] == 'SETTING LIGHTING' && $log['status'] == "OFF")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['type'] == 'SETTING LIGHTING' && $log['status'] == "ON")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['type'] == 'SETTING AC' && $log['status'] == "ON")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['type'] == 'SETTING AC' & $log['status'] == "OFF")
                        {
                            $col_mdi = 'text-success';
                        }
                        elseif($log['status'] == 'DELETE')
                        {
                          $col_mdi = 'text-danger';
                        }
                        else{
                          $col_mdi = 'text-muted';
                        }

                        //avatar
                        if($log['npk'] == "")
                        {
                          
                          $imgurl = "<i class='mdi mdi-timer text-warning mr-4'></i>";
                        }
                        else
                        {
                          $imgurl = "<i class='mdi mdi-account text-primary mr-4'></i>";
                          // $imgurl = "<img
                          //         width='35px'
                          //         height='35px'
                          //       
                          //         alt='image'
                          //         class='rounded-circle'
                          //     />";
                        }
                        if($log['name'] == 'Automatic Schedule')
                        {
                          $txtcol = 'text-warning';
                        }else{
                          $txtcol = '';
                        }
                      ?>
                        <div class="preview-item border-bottom">
                              <div class="preview-thumbnail">
                                <?php echo $imgurl;?>
                              </div>
                              <div class="preview-item-content d-flex flex-grow">
                              <div class="flex-grow text-search">
                                  <div
                                  class="d-flex d-md-block d-xl-flex justify-content-between"
                                  >
                                  <h6 class="preview-subject <?php echo $txtcol;?>"><?php echo $log['name'];?></h6>
                                  <p class="text-muted text-small"><?php echo $log['lastupdate'];?></p>
                                  </div>
                                  <p class="<?php echo $col_mdi;?>">
                                  <?php echo $log['description'];?>
                                  </p>
                              </div>
                              </div>
                        </div>
                      <?php
                      }
                      ?>
                    </div>
                    <div onClick="loadMore()" id="loadmore" class="loadmore btn-inverse-primary text-center p-2 rounded" style="cursor:pointer;">
                      <i id="loader" class="fa fa-circle-o-notch fa-spin"></i>
                      Load More
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <script>
  document.getElementById("search").focus();
 

  function searchList() {
      var input, filter, cards, cardContainer, title, i;

      input = document.getElementById("search");
      filter = input.value.toUpperCase();
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

  //load progreess
var loader      = document.getElementById("loader");  
var loadmore      = document.getElementById("loadmore");  
var counterlogs = document.getElementById("counterlogs");  
index = 1;
counterlogs.value = index;
loader.style.display = "none";  
function loadMore()
{
  loader.style.display  = "block";

  var listlogs          = document.getElementById('rowListRoom');
  var pagecount         = <?=$pagecount;?>;
  index                 = parseInt(counterlogs.value) + 1;
  counterlogs.value     = index;
  console.log(pagecount)
  console.log(index)

  if(pagecount === index)
  {
    loadmore.setAttribute("onClick", "");
    loadmore.innerText = 'Finish';
  }else{
    setTimeout(() => {
    $.ajax({
      
                type : "GET",  //type of method
                url  : "process_load_more.php?index="+index,  //your page
                success: function(response){  
             
                loader.style.display = "none"; 
                // console.log(response);
                listlogs.innerHTML += response
                }
    });
  }, "500")
  }
}

</script>