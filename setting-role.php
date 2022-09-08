<?php
include('getdata.php');

?>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
<div class="modal fade bd-example-modal-xl" id="showProgressJob" tabindex="-1" aria-labelledby="labelModal" aria-hidden="true" >
        <div class="modal-dialog modal-xl" >
            <div class="modal-content" style="background-color:#191c24;">
                    <div class="text-center">
                      <h2 style="font-size:20px;" class="modal-title text-white  mt-3" id="labelModal"></h2>
                    </div>
                <div class="modal-body ">
                <h3 id="loadwaiting" class="text-center text-success"><i class="fa fa-circle-o-notch fa-spin text-success"></i> Loading...</h3>
                
                    <div class="container container_progress">
                            <div class="row w-100  h-100 border-1 divcenter " id="divcenter">
                               
                            </div>
                    </div>
                </div>
                    <div class="row pl-2 pr-2 ">
                      <!-- <div class="col-md-4 bg-info">
                        <button type="button" id="check-all" class="btn btn-info w-100 rounded-0">Check All</button>
                      </div> -->
                      <div class="col-md-6 bg-info " >
                        <button type="button" class="btn btn-info  w-100 rounded-0" id="updatemappingaccess" data-bs-dismiss="modal">Updated</button>
                      </div>
                      <div class="col-md-6 bg-danger">
                        <button type="button" class="btn btn-danger w-100 rounded-0 btn-clear" data-bs-dismiss="modal">Close</button>
                      </div>
                    
                    </div>
                
            </div>
        </div>
</div>
<div class="page-header">
<h3 class="page-title">Roles Setting</h3>
</div>
<div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                                <p class="card-description">
                                
                                </p> 
                               
                  
                                <div class="form-inline">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Form Input Role</div>
                                        </div>
                                             
                                        <input
                                        type="text"
                                        size="15"
                                        name="role_code"
                                        class="form-control text-white"
                                        style="background-color:#000"
                                        id="role_code"
                                        placeholder = "Role Code"
                                        />
                                   
                                    
                                             
                                        <input
                                        required
                                        
                                        type="text"
                                        size="25"
                                        name="role"
                                        class="form-control text-white"
                                        style="background-color:#000"
                                        id="role"
                                        placeholder = "Description"
                                        />
                                   
                                       
                                      
                                    </div>
                                    
                                        <button
                                        onClick="saveRole()"
                                        id="saveRole"
                                        type="submit"
                                        class="form-control btn btn-inverse-primary mb-2 mr-2"
                                        >
                                        Save
                                        </button>
                                </div>
                </div>    
            </div>    
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body ">
                  <h4 class="card-title">Access List</h4>
                  
                    <div class="table-responsive rowListRoom">
                            <table class="table table-bordered preview-item" id="myTable">
                            <thead>
                                <tr>
                               
                                <th> Role Code </th>
                                <th> Description </th>
                                <th> Action </th>
                                
                                </tr>
                            </thead>
                    
                            <tbody id="list-role">
                            <?php
                            
                            $datarole = getDataRole();
        
                            $no = 1;
                            foreach($datarole as $item_role)
                            {
                            ?>
                                <tr id="delete_<?php echo $item_role['id'];?>">
                                   
                                    <td class="text-search"><?php echo $item_role['role_code'];?> </td>
                                    <td class="text-search"><?php echo $item_role['role'];?> </td>
                                    <td> 
                                      <?php
                                      if($item_role['role_code'] != 'ADMIN')
                                      {
                                        ?>
                                          <button class="btn btn-inverse-danger mr-2" onClick="deleteRole('<?php echo  $item_role['id'];?>','<?php echo  $item_role['role_code'];?>')">Delete</button>
                                          <button id="access|<?php echo $item_role['role_code'];?>" data-bs-toggle="modal"  class="btn btn-inverse-info mr-2 openmodal">Access Mapping</button>
                                          <button id="area|<?php echo $item_role['role_code'];?>" data-bs-toggle="modal"  class="btn btn-inverse-success mr-2 openmodal">Area Lighting / Compressor</button>
                                        <?php
                                      }
                                      ?>
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

//SIMPAN ROLE
function saveRole()
{
    var role_code = document.getElementById("role_code").value;
    var role = document.getElementById("role").value;
    var list_role     = document.getElementById('list-role');

    if(role === "" || role_code === "")
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
    }else{

        $.ajax({
            
                type : "POST",  //type of method
                url  : "process.php?type=setting-role-submit",  //your page
                data : { role_code_param : role_code, role_param : role},// passing the values
                success: function(response){  
                
                result  = JSON.parse(response);
       
                    if(result.statuscode == 200)
                    {
                        list_role.innerHTML += "<tr id='delete_"+result.lastid+"'>"+
                        "<td class='text-search'>"+result.role_code+"</td>"+
                        "<td class='text-search'>"+result.role+"</td>"+
                        "<td>"+
                        "<button class='btn btn-inverse-danger mr-2'  onClick=deleteRole('"+result.lastid+"','"+result.role_code+"') >Delete</button>"+
                        "</td>"+
                        "</tr>";
                        location.reload();
                    }
                    else{
                        alert(result.message)
                    }
                    $("#role").val("");
                    $("#role_code").val("");
                }  
        });
    }
}


//OPEN MODAL
$(document).ready(function () {
  


  //open modal
  $(".openmodal").click(function (e) {
            
            valueIdSplit  =  this.id.split("|");
            tipeModal     = valueIdSplit[0];
            valueId       = valueIdSplit[1];
            bodydialog    = $("#divcenter");
            bodydialog.html("");
            

            //pisahkan modal untuk access dan area
            if(tipeModal == 'access')
            {
              $("#labelModal").text("Access Mapping");
              
              console.log('access');
              urlParam = "setting-mapping-access.php?role_code_param="+valueId;
            }
            else if(tipeModal == 'area')
            {
              $("#labelModal").text("Area Mapping");
              console.log('area');
              urlParam = "setting-mapping-area.php?role_code_param="+valueId;
            }

            $.ajax({
              url: urlParam,
              type: 'GET',

              success: function(res) {
                $("#loadwaiting").hide();
              // console.log(res);
                bodydialog.append(res);
              }
          });
            $("#showProgressJob").modal("show");
    });


  $(".btn-clear").click(function(){
    bodydialog = $("#divcenter");
    bodydialog.html("");
    $("#showProgressJob").modal("hide");
  });
});


//updapte mapping access
$(document).ready(function () {


//update mapping access
  $("#updatemappingaccess").click(function(e){
      rolecode = $("#mapping_role").val();
      tipemodal = $("#tipemodal").val();
      console.log(tipemodal);
      //mendapatkan data checked

      datamapping = [];
      if(tipemodal == "access")
      {
        const dataaccess = [];
        $('.mappingaccess:checked').each(function(i){
            dataaccess.push($(this).val());
        });

        datamapping = dataaccess;
        urlPage     = "process.php?type=setting-mapping-access";
      }
      else if(tipemodal == "area")
      {
        const dataarea = [];
        $('.mappingarea:checked').each(function(i){
          dataarea.push($(this).val());
        });
        datamapping = dataarea;
        urlPage     = "process.php?type=setting-mapping-area";
      }

    
      $.ajax({
                type : "POST",  //type of method
                url  : urlPage,  //your page
                data : { role_code_param : rolecode, mapping_access : datamapping},// passing the values
                success: function(response){  
                  Swal.fire({
                  text: 'Data has been Updated',
                  target: '#custom-target',
                  customClass: {
                    container: 'position-absolute'
                  },
                  toast: true,
                  position: 'bottom-center'
                })
                }  
      });


  })
});




//PENCARIAN
document.getElementById("search").focus();

//DELETE ROLE
function deleteRole(param_id, param_code)
{
    var elementdelete = document.getElementById('delete_'+param_id);

    $.ajax({
                type : "GET",  //type of method
                url  : "process.php?type=setting-role-delete&param_id="+param_id+"&param_code="+param_code,  //your page
                success: function(response){  
                result  = JSON.parse(response);
                console.log(result);
                  if(result.statuscode === 200)
                  {
                    elementdelete.remove();
                    
                  }
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
      });
}




//SEARCH
function searchList() {
  var input, filter, table, tr, td, i, txtValue;
input = document.getElementById("search");

filter = input.value.toUpperCase();

table = document.getElementById("myTable");
tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
  td = tr[i].getElementsByTagName("td")[1];
  tdx = tr[i].getElementsByTagName("td")[0];
  if (td) {
    txtValue = td.textContent || td.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }       
  if (tdx) {
    txtValue = tdx.textContent || tdx.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }       
}
}
</script>
