    <dialog id="dialogBox" class="w-100 h-100" style="background-color:#191c24;border:0px;padding:0px;" data-aos="zoom-in">
    <div id='divPopUpContent' class="w-100 h-100">
    <img src='assets/images/close2.png' id='imgClose' class='imgClose' onClick="return closeBoxDialog();">
            <iframe src='processloading.php' style='width:100%; height:100%;border:0px; ' id='ifamePopUp'></iframe>
          </div>
        
    </dialog>
 
    <output aria-live="polite"></output>
   <div class="page-header">
              <h3 class="page-title">Power Monitoring</h3>
            </div>
            <div class="row">
              <div class="col-lg-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="row icons-list">
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col" onClick="return openModal('this url')">
                        <i class="mdi mdi-access-point-network"></i> Power House
                        PH 1
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col" onClick="return openModal('this url')">
                        <i class="mdi mdi-home"></i> Power House PH 3
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col" onClick="return openModal('this url')">
                        <i class="mdi mdi-home-modern"></i> Assy
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi mdi-home-variant"></i> Office
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-home-map-marker"></i>
                        Distribution Sel 1
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi mdi-watermark"></i> Plating 6
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-view-stream"></i> Distribution P3
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="page-header">
              <h3 class="page-title">Machines</h3>
            </div>
            <div class="row">
              <div class="col-lg-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="row icons-list">
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-webhook"></i> Treatment
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-window-restore"></i> Plating 6
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-xaml"></i> Grinding Rowland
                      </div>
                      <!-- <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-oil-temperature"></i> HPD
                      </div> -->
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-oil-temperature"></i> HPD
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="page-header">
              <h3 class="page-title">Other</h3>
            </div>
            <div class="row">
              <div class="col-lg-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="row icons-list">
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-water-pump"></i> Water
                      </div>
                      <div class="col-sm-6 col-md-4 col-lg-3 hover-col"  onClick="return openModal('this url')">
                        <i class="mdi mdi-oil-temperature"></i> Compressor
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<script>
function openModal(param_url)
{
  const dialogBox = document.getElementById('dialogBox');
  const outputBox = document.querySelector('output');

      if (typeof dialogBox.showModal === "function") {
          dialogBox.showModal();
          $("#ifamePopUp").attr("src", param_url);
      } else {
          alert("The <dialog> API is not supported by this browser");
      }
  }
  
  function closeBoxDialog()
  {
    $("#ifamePopUp").attr("src", "");
    dialogBox.close();
  }

  // $(document).ready(function() {
  // $(".imgClose").click(function(){
  //     $("#dialogBox").hide();
	// 		$("#divPopUp").hide();
	// 		$("#ifamePopUp").attr("src", '');
	// 		$("#frame").attr("src", 'processloading.php');
	// 		$('#divPopUpContent').html(`<iframe src='processloading.php' style='width:100%; height:100%;border:0px; ' id='ifamePopUp'></iframe>`);
  //   });
  // }
</script>
