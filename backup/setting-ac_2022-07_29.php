

<link rel="stylesheet" href="assets/css/toggle.css" />
<div class="page-header">
  <h3 class="page-title">Office Air Conditioner Remote Control</h3>
</div>
       
<div class="row">
              <!-- <div class="col-12 mb-3">
                      <input type="text" onkeyup="searchList()" class="form-control text-white  bg-dark" id="search" name='search' placeholder="Search">
              </div> -->
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
                            include('configs/db.php');
                            include('function.php');
                            
                            //mendapatkan status ac seluruhnya
                            $dataStatusAcFromApi = statusAcFromApi();

                            $querycategory  =   mssql_query("SELECT * FROM AC_akebono ORDER BY Description ASC");
                            $cekcategory    =   mssql_num_rows($querycategory);
                               
                            if($cekcategory != 0 )
                            {
                              $idx = 1;
                              while($rcategory = mssql_fetch_assoc($querycategory)){
                            
                              $description  = $rcategory['Description'];
                              $address      = $rcategory['Remote_address'];
                              $port         = $rcategory['port'];
                              $floor        = $rcategory['floor'];

                                //cek status ac dan akan mengembalikan array {status,temp}
                                $statusac  = cekStatusAc($dataStatusAcFromApi, $address,$port);
                                if($statusac['status'] == 1)
                                {
                                    $checkedAc = 'checked';
                                }
                                else
                                {
                                    $checkedAc = '';
                                }
                                $temperaturac = $statusac['temp'];

                              ?>                  
                              <div class="preview-item border-bottom">
                                <div
                                  class="preview-item-content d-sm-flex flex-grow text-search">
                                  <div class="flex-grow">
                                    <h6 class="preview-subject"><?php echo $description.' - Lantai '.$floor; ?></h6>
                                    <p class="text-muted mb-0">
                                      Address : <?php echo $address;?>
                                    </p>
                                  </div>
                                  <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                    <div class="row text-center">
                                      <div
                                        class="col-md-3 col-3 clickMinus"
                                        style="font-size: 3vh"
                                        onClick="return tempAc('minus','<?php echo $address;?>','<?php echo $port;?>')"
                                      >
                                        -
                                      </div>
                                      <div
                                        class="col-md-6 col-6"
                                        style="font-size: 3vh;color:#03ECFB"
                                        id="temperatur_<?php echo $address.'_'.$port;?>"
                                      >
                                        <?php echo $temperaturac; ?>
                                      </div>
                                      <div
                                        class="col-md-3 col-3 clickPlus"
                                        style="font-size: 3vh"
                                        onClick="return tempAc('plus','<?php echo $address;?>','<?php echo $port;?>')"
                                      >
                                        +
                                      </div>
                                    </div>
                                    <div class="row d-flex justify-content-center">
                                      <label class="skeuo__switch mt-2">
                                        <input
                                        <?php echo $checkedAc; ?>
                                          type="checkbox"
                                          class="skeuo__input"
                                          id="checked_<?php echo $address.'_'.$port; ?>"
                                          onClick="return switchAc('<?php echo $address;?>','<?php echo $port;?>')"
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
function tempAc(desc, address, port){
  mintemp                     = 16  
  maxtemp                     = 30  
  deftemp                     = 25  
  
  temperaturid = document.getElementById('temperatur_'+address+'_'+port);  
  
  if(desc === "minus")
  {
    setTemperatur = temperaturid.innerText - 1;
  
    if(temperaturid.innerText == mintemp){
      setTemperatur = mintemp;
    }
  }

  if(desc === "plus")
  {
    setTemperatur = parseInt(temperaturid.innerText) + 1;
  
    if(parseInt(temperaturid.innerText) == maxtemp){
      setTemperatur = maxtemp;
    }
   
  }
  temperaturid.innerHTML = setTemperatur;
    console.log(setTemperatur);
    console.log(address);
    console.log(setTemperatur);
    console.log(port);
    prosesAc(address, setTemperatur, 1, port);
  
    statusid = document.getElementById('checked_'+address+'_'+port);  
    statusid.checked = true;
}

function switchAc(param_address,param_port){
  statusid = document.getElementById('checked_'+param_address+'_'+param_port).checked;  

  if(statusid === true)
  {
    temperatur  = 25;
    statusac    = 1; 
  }
  if(statusid === false)
  {
    temperatur  = 25;
    statusac    = 0; 
  }
  temperaturid = document.getElementById('temperatur_'+param_address+'_'+param_port);  
  temperaturid.innerText = 25;
  prosesAc(param_address, temperatur, statusac, param_port);
}


function prosesAc(param_add, param_temp, param_status, param_port ){
          
  urlelectricac = "process_ac.php?statusaction="+param_status+"&address=" + param_add+"&temperatur="+param_temp+"&port="+param_port;
            
  fetch(urlelectricac)
  .then(dataac => {
     return dataac.text()
  })
  .then(resac => {
    responseac = JSON.parse(resac);
     console.log(responseac);
    })
}

</script>