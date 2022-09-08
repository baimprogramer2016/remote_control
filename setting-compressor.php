


<?php
    include('configs/db.php');
    include('function.php');
    $checkedDisabled = "";
    
    $param_area = "AND Area = '".$_GET['area']."'";
    // if($_GET['area'] == 'Other')
    // {
    //   $param_area = "AND Area is null";
    // }
    
    //mendapatkan status ac seluruhnya
    $dataStatusCompressorFromApi      = statusCompressorFromApi();
if(count($dataStatusCompressorFromApi) == 0 ){
  $checkedDisabled = "disabled";
  ?>
    <div class="alert alert-danger" role="alert">
    Warning, Connection to API state is lost and Remote Control not Working
    </div>
  <?php
};
?>

<link rel="stylesheet" href="assets/css/toggle.css" />
<div class="page-header">
  <h3 class="page-title">Compressor Remote Control</h3>
                      
  <a   onclick="return goArea()" class="nav-link btn btn-inverse-warning create-new-button" id="createbuttonDropdown" data-toggle="dropdown" aria-expanded="false" href="#">Back to Area</a>
</div>
       
<div class="row">
             <!-- <div class="col-12 mb-3">
                      <input type="text" onkeyup="searchList()" class="form-control text-white  bg-dark" id="search" name='search' placeholder="Search">
              </div>-->
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                      <!-- <h4 class="card-title mb-1"></h4> -->
                      <!-- <p class="text-muted mb-1">Remote</p> -->
                    </div>
                    <div class="row" id="rowListRoom">
                   
                      <div class="col-12">
                        <div class="preview-list">
                            <?php
                          
     

                            $querycategory  =   mssql_query("SELECT * FROM Compressor_akebono WHERE  Description is not null ".$param_area."  ORDER BY Description ASC");
                            $cekcategory    =   mssql_num_rows($querycategory);
                               
                            if($cekcategory != 0 )
                            {
                              $idx = 1;
                              while($rcategory = mssql_fetch_assoc($querycategory)){
                            
                              $description  = $rcategory['Description'];
                              $address      = $rcategory['Address'];
                              $area         = $rcategory['Area'];
                           
                               //cek status ac dan akan mengembalikan array {status,temp}
                               $statusac  = cekStatusCompressor($dataStatusCompressorFromApi, $address);
                               if($statusac['status'] == '1')
                               {
                                   $checkedAc = 'checked';
                               }
                               else
                               {
                                   $checkedAc = '';
                               }
                              ?>                  
                               <div class="preview-item border-bottom">
                            <div
                              class="preview-item-content d-sm-flex flex-grow text-search"
                            >
                              <div class="flex-grow">
                                <h6 class="preview-subject"><?php echo $description.' - Address '.$address;?></h6>
                                <p class="text-muted mb-0">
                                  Area : <?php echo $area;?>
                                  IP : <?php echo $rcategory['ip_address'];?>
                                </p>
                              </div>
                              <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                <div class="row d-flex justify-content-center">
                                  <label class="skeuo__switch mt-2">
                                    <input
                                    <?php echo $checkedAc; ?>
                                      type="checkbox"
                                      <?php echo $checkedDisabled;?>
                                      class="skeuo__input"
                                      onClick="return onOffCompressor('<?php echo $rcategory['ip_address']; ?>','<?php echo $rcategory['id'];?>','<?php echo $rcategory['Address'];?>')"
                                      id="checked_<?php echo $rcategory['id']; ?>"
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
                              $idx++;
                              }else{
                              ?>
                                      <!-- Jika tidak ditemukan datanya -->
                                      <div class="card corona-gradient-card">
                                        <div class="card-body py-0 px-0 px-sm-3">
                                          <div class="row align-items-center">
                                            <div class="col-4 col-sm-3 col-xl-2">
                                              <img src="assets/images/3973481.png" class="gradient-corona-img img-fluid" alt="">
                                            </div>
                                            <div class="col-5 col-sm-7 col-xl-8 p-0">
                                              <h4 class="mb-1 mb-sm-0">Data Not Available</h4>
                                              <p class="mb-0 font-weight-normal d-none d-sm-block">Please Contact Administrator</p>
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
  function goArea(param) {
    // alert("hai");
    location.href = "main.php?page=group-setting-compressor";
  }
</script>

<script>
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

</script>

<script>
      
        function onOffCompressor(paramIp,paramId,paramAddress){
          statusid = document.getElementById('checked_'+paramId).checked;  

          if(statusid === true)
          {
            statusCompressor    = 1; 
          }
          if(statusid === false)
          {
            statusCompressor    = 0; 
          }

          urlelectriclamp = "process_compressor.php?ip_address="+paramIp+"&id="+paramId+"&statusaction="+statusCompressor+"&address=" + paramAddress;
                fetch(urlelectriclamp)
                    .then(datalamp => {
                        return datalamp.text()
                    })
                    .then(reslamp => {
                        responselamp = JSON.parse(reslamp);
                       
                        // console.log(responselamp);
                    })
          
      }

</script>