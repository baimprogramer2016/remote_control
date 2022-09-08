<?php
    include('getdata.php');
?>

<div class="page-header">
<h3 class="page-title">Account Settingss</h3>
</div>
<div class="row">
      <div class="col-12 grid-margin stretch-card">
          <div class="card">
              <div class="card-body">
                  
                  <p class="card-description">
                   Pastikan  <code>Nama</code> sudah sesuai dengan yang dipilih
                  </p> 
                  
                  <div class="form-inline">
                      <div class="input-group mb-2 mr-sm-2">
                          <div class="input-group-prepend">
                              <div class="input-group-text">Account</div>
                          </div>
                        
                          <div class="form-group">           
                              <select name="npk" id="npk" style="width:100%;background-color:#000;color:#fff;height:38px;border:1px solid #2e2c33;" style="width:100%">
                              <option value="">-- Pilih Karyawan --</option>
                              <?php
                          
                              $datausr = getAllEmployee();
                              foreach($datausr as $item_user)
                              {
                              ?>
                                <option value="<?php echo $item_user['npk'].'|'.$item_user['name'];?>"><?php echo $item_user['name'].' - '.$item_user['npk'];?></option>
                                
                              <?php
                              }
                              ?>
                              </select>
                              
                          </div>

                            
                          <div class="form-group">           
                          <select  name="role_code" id="role_code"  style="width:100%;background-color:#000;color:#fff;height:38px;border:1px solid #2e2c33;">
                          <option value="">-- Pilih Role --</option>
                              <?php
                              $datarole = getDataRole();
                              foreach($datarole as $item_role)
                              {
                              ?>
                                <option value="<?php echo $item_role['role_code'];?>"><?php echo $item_role['role'];?></option>
                                
                              <?php
                              }
                              ?>
                              </select>
                              
                          </div>
                      
                      </div>
                      <button
                      onClick="saveAccount()"
                      type="submit"
                      class="form-control btn btn-inverse-primary mb-2 mr-2"
                    >
                      Save
                    </button>
                    <button
                                        onClick="clearForm()"
                                        type="submit"
                                        class="form-control btn btn-inverse-warning mb-2 mr-2"
                                        >
                                        Clear
                                        </button>
                  </div>
              </div>    
          </div>    
      </div>

      <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body ">
                  <h4 class="card-title">Account List</h4>
                  
                  <div class="table-responsive rowListRoom">
                    <table class="table table-bordered preview-item" id="myTable">
                      <thead>
                        <tr>
                          <th> Npk </th>
                          <th> Name </th>
                          <th> Role </th>
                          <th> Action </th>
                          
                        </tr>
                      </thead>
                      <tbody id="list-account">
                      <?php
                      
                      $dataemp = getDataUser();
  
                      $no = 1;
                      foreach($dataemp as $item_usr)
                      {

                        $dataupdate = array(
                          $item_usr['id'],
                          $item_usr['npk'],
                          $item_usr['name'],
                          $item_usr['role_code'],
                        );

                        $sendata =  implode(",", $dataupdate);
                      ?>
                         <tr class="hover" id="delete_<?php echo $item_usr['id'];?>" onClick="setValueColom('<?php echo $sendata;?>')">
                              <td class="text-search" > <?php echo $item_usr['npk'];?> </td>
                              <td class="text-search"> <?php echo $item_usr['name'];?> </td>
                              <td class="text-search"> <?php echo $item_usr['role_code'];?> </td>
                              <td> 
                                <button class="btn btn-inverse-danger"  onClick="deleteAccount('<?php echo  $item_usr['id'];?>','<?php echo  $item_usr['npk'];?>')">Delete</button>                         
                 
                            </td>                         
                          </tr>
                      <?php
                      $no++;
                      }
                      ?>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script>      
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>

<script>
  //delete account
function deleteAccount(param_id, param_npk)
{
    var elementdelete = document.getElementById('delete_'+param_id);

    $.ajax({
                type : "GET",  //type of method
                url  : "process.php?type=setting-account-delete&param_id="+param_id+"&param_npk="+param_npk,  //your page
                success: function(response){  
                result  = JSON.parse(response);
                console.log(result);
                  if(result.statuscode === 200)
                  {
                    elementdelete.remove();
                  }else{
                    alert("Failed to Delete data");
                  }
                }  
      });
}
;


//view data befire update
function setValueColom(param)
{

  data = param.split(",");
  id      = data[0];
  col_1   = data[1];
  col_2   = data[2];
  col_3   = data[3];

  
  document.getElementById("npk").value = col_1+'|'+col_2;
  document.getElementById("role_code").value = col_3;

}

//save data
function saveAccount()
{
    var npk             = document.getElementById("npk").value;
    var role_code       = document.getElementById("role_code").value;
    var list_account    = document.getElementById('list-account');

    console.log(npk);

    if(npk === "" || role_code === "")
    {
      Swal.fire({
                  text: "Form Cannot be Empty",
                  target: '#custom-target',
                  customClass: {
                    container: 'position-absolute'
                  },
                  toast: true,
                  position: 'bottom-center'
                });
     
                textlogin.style.display="block"
                loader.style.display = "none";
    }else{

        $.ajax({
            
                type : "POST",  //type of method
                url  : "process.php?type=setting-account-submit",  //your page
                data : { npk_param : npk, role_code_param : role_code},// passing the values
                success: function(response){  
                
                result  = JSON.parse(response);
                  
                    if(result.statuscode === 200)
                    {
                      console.log(result.lastid);
                      list_account.innerHTML += "<tr style='font-size:13px;' id='delete_"+result.lastid+"'><td>"+result.npk+"</td><td>"+result.name+"</td><td>"+result.role_code+"</td><td> <button onClick=deleteAccount('"+result.lastid+"','"+result.npk+"') class='btn btn-inverse-danger'>Delete</button></td></tr>";
                                           
                       $("#npk").val("");
                       $("#role_code").val("");
                       
                       Swal.fire({
                        text: result.message,
                        target: '#custom-target',
                        customClass: {
                          container: 'position-absolute'
                        },
                        toast: true,
                        position: 'bottom-center'
                      });
                    }
                    if(result.statuscode === 205)
                    {
     
                      Swal.fire({
                        text: result.message,
                        target: '#custom-target',
                        customClass: {
                          container: 'position-absolute'
                        },
                        toast: true,
                        position: 'bottom-center'
                      });
                      console.log(result.lastid);
                      var elementdelete = document.getElementById('delete_'+result.lastid);
                      elementdelete.remove();
           
              
                      list_account.innerHTML += "<tr style='font-size:13px;' id='delete_"+result.lastid+"'><td>"+result.npk+"</td><td>"+result.name+"</td><td>"+result.role_code+"</td><td> <button onClick=deleteAccount('"+result.lastid+"','"+result.access_code+"') class='btn btn-inverse-danger'>Delete</button></td></tr>";
                                              
                       $("#access").val("");
                       $("#access_code").val("");
                       $("#description").val("");
            
                    }
                    else{
                      Swal.fire({
                        text: result.message,
                        target: '#custom-target',
                        customClass: {
                          container: 'position-absolute'
                        },
                        toast: true,
                        position: 'bottom-center'
                      });
                    }
                }  
        });
    }
}

$( function() {
  $( "#search_name" ).autocomplete({
    source: "process.php?type=search_name_auto",
    });
  
  });
//clear form
function clearForm()
{
  document.getElementById("npk").value = "";
  document.getElementById("role_code").value = "";
  
}


document.getElementById("search").focus();

function searchList() {
  var input, filter, table, tr, td, i, txtValue;
input = document.getElementById("search");

filter = input.value.toUpperCase();

table = document.getElementById("myTable");
tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
  td = tr[i].getElementsByTagName("td")[2];
  if (td) {
    txtValue = td.textContent || td.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }       
}
}

</script>
