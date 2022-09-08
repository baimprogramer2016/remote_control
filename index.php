<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <title>AKEBONO</title>
    <!-- plugins:css -->
    <link
      rel="stylesheet"
      href="assets/vendors/mdi/css/materialdesignicons.min.css"
    />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
 


    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/myStyle.css" />
    <link rel="stylesheet" href="assets/css/popup.css" />
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/akebono.png" />

 
  </head>
  <body>
  <dialog id="dialogBox"  class="bg-white  text-center" style="width:300px;height:310px;background-color:#191c24;border:0px;padding:0px;border-radius:5%;" data-aos="zoom-in">
    <div style="height:25%;background-color:#154360;padding:5px;">
      <img src='assets/images/close2.png' id='imgClose' class='imgClose' onClick="return closeBoxDialog();">
      <p class="text-center  text-white" style="font-size:20px;">Wrong <br>Username Or Password</p>
    </div>
      <img  src="assets/images/acc.png" style="width:200px;font-weight:w-500" alt="">
  </dialog>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div
            class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg"
          >
            <div class="card col-lg-4 mx-auto box-login">
              <div class="card-body px-5 py-5 text-center">
                <img width="200vh" src="assets/images/akebono.png" alt="" />
                <h3 class="card-title text-center mb-3 title-login">
                  Energy & Remote Control Dashboard
                </h3>

                <div class="form-group">
                  <label class="text-center"
                    ><i class="icon-node_link_direction"></i>Npk</label
                  >
                  <input
                    type="text"
                    name="username"
                    id="username"
                    
                    class="form-control p_input text-success"
                  />
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input
                    type="password"
                    id="password"
                    name="password"
                   
                    class="form-control p_input text-success"
                  />
                </div>

                <div class="text-center">
               <button onClick="loginProcess()"
                      id="enter-btn"
                      type="submit"
                      class="btn btn-danger btn-block enter-btn">
                      <i id="loader" class="fa fa-circle-o-notch fa-spin"></i>
                      <span id="textlogin">Login</span>
                    </button>
              
                </div>

                <p class="sign-up">Don't have an Account? Contact Admin</p>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- endinject -->

<script src="assets/js/sweetalert2@11.js"></script> 
<script src="assets/js/jquery-3.5.1.min.js"></script> 
<script>


var loader = document.getElementById("loader");
var textlogin = document.getElementById("textlogin");
loader.style.display = "none";  
const dialogBox = document.getElementById('dialogBox');

function alertSwal()
{
  return   Swal.fire({
                  text: 'Time Out, Please Try Again',
                  target: '#custom-target',
                  customClass: {
                    container: 'position-absolute'
                  },
                  toast: true,
                  position: 'bottom-center'
                })

}

function loginProcess()
{
  textlogin.style.display="none"
  loader.style.display = "block";
    var param_username = $("#username").val();
    var param_password = $("#password").val();
    $.ajax({
              type : "POST",  //type of method
              url  : "checklogin.php",  //your page
              error: function(){
                alertSwal('Time Out, Please try again');
                textlogin.style.display="block"
                loader.style.display = "none";
              },
              data : { username : param_username, password : param_password},// passing the values
              success: function(response){  
              // console. log(response);
              if(response.trim() === "success")
              {
                setTimeout(function () {
                  location.href = "main.php?page=dashboard";
                }, 300);
                loader.style.display = "none";
                textlogin.style.display="block"
              }
              else
              {
                // dialogBox.showModal();
                alertSwal('Username Or Password Wrong');
                textlogin.style.display="block"
                loader.style.display = "none";
              }
     
              }  ,  timeout: 10000 
    });
}
function closeBoxDialog()
  {
    $("#ifamePopUp").attr("src", "");
    dialogBox.close();
  }
</script>

  </body>
</html>
