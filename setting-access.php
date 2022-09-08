
<?php
include('getdata.php');
?>
<div class="page-header">
<h3 class="page-title">Access Setting</h3>
</div>
<div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                                <p class="card-description">
                                <code>Area </code> Hanya khusus menu Remote Control Lampu dan Compressor
                                </p> 
                               
                  
                                <div class="form-inline">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Input</div>
                                        </div>
                                        <div class="form-group">           
                                            <select  name="access_code" id="access_code"  style="width:100%;background-color:#000;color:#fff;height:38px;border:1px solid #2e2c33;">
                                            <option value="">--- Menu ---</option>
                                            <option value="dahsboard">Dashboard</option>
                                            <option value="monitoring-energy">Monitoring</option>
                                            <option value="report-power-house">Report - Power House</option>
                                            <option value="report-power-house-distribution">Report - Power House Distribution</option>
                                            <option value="report-water">Report - Water</option>
                                            <option value="report-water-distribution">Report - Water Distribution</option>
                                            <option value="report-compressor">Report - Compressor</option>
                                            <option value="setting-day">Day Settings</option>
                                            <option value="schedule-ac">Schedule - AC </option>
                                            <option value="schedule-lighting">Schedule - Lighting</option>
                                            <option value="schedule-compressor">Schedule - Compressor</option>
                                            <option value="group-setting-ac">Group - AC</option>
                                            <option value="group-setting-lighting">Group - Lighting</option>
                                            <option value="group-setting-compressor">Group - Compressor</option>
                                            <option value="setting-ac">Remote - AC</option>
                                            <option value="setting-lighting">Remote - Lighting</option>
                                            <option value="setting-compressor">Remote - Compressor</option>
                                            <option value="logs">Logs</option>
                                            <!-- <?php
                                            $dataarea = getAreaLightingFilter();
                                            foreach($dataarea as $item_area)
                                            {
                                            ?>
                                                <option value="<?php echo $item_area['kodearea'];?>"><?php echo $item_area['area'];?></option>
                                                
                                            <?php
                                            }
                                            ?> -->
                                            </select>
                                        </div>
                         
                                        <input
                                        type="text"
                                        size="15"
                                        name="access"
                                        class="form-control text-white"
                                        style="background-color:#000"
                                        id="access"
                                        placeholder = "Access"
                                        />
                                   
                                             
                                       
                                             
                                        <input
                                        required
                                        
                                        type="text"
                                        size="25"
                                        name="description"
                                        class="form-control text-white"
                                        style="background-color:#000"
                                        id="description"
                                        placeholder = "Description"
                                        />
                                    </div>
                                    
                                        <button
                                        onClick="saveAccess()"
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

        <div class="col-lg-12 grid-margin stretch-card overflow-auto h-50">
            <div class="card">
                <div class="card-body ">
                  <h4 class="card-title">Access List</h4>
                  
                    <div class="table-responsive rowListRoom">
                    <table class="table table-bordered preview-item" id="myTable">
                            <thead>
                                <tr>
                                <th> Code </th>
                                <th> Access </th>
                                <th> Description </th>
                                <th> Delete </th> 
                                </tr>
                            </thead>
                    
                            <tbody id="list-access">
                            <?php
                            
                            $dataemp = getDataAccess();
        
                            $no = 1;
                            foreach($dataemp as $item_usr)
                            {
                              $dataupdate = array(
                                $item_usr['id'],
                                $item_usr['access_code'],
                                $item_usr['access'],
                                $item_usr['description'],
                              );

                              $sendata =  implode(",", $dataupdate);
                                           
            
                            ?>
                                <tr class="hover" id="delete_<?php echo $item_usr['id'];?>" onClick="setValueColom('<?php echo $sendata;?>')">
                                   
                                    <td class="text-search" id="col_1_<?php echo $item_usr['access_code'];?>" style="font-size:13px;"><?php echo $item_usr['access_code'];?> </td>
                                    <td class="text-search" id="col_2_<?php echo $item_usr['access_code'];?>" style="font-size:13px;"><?php echo $item_usr['access'];?> </td>
                                    <td class="text-search" id="col_3_<?php echo $item_usr['access_code'];?>" style="font-size:13px;"><?php echo $item_usr['description'];?> </td>
                                    <td> <button class="btn btn-inverse-danger"  style="font-size:12px;" onClick="deleteAccess('<?php echo  $item_usr['id'];?>','<?php echo  $item_usr['access_code'];?>')">Delete</button></td>                         
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
document.getElementById("search").focus();

//update
function setValueColom(param)
{

  data = param.split(",");
  id      = data[0];
  col_1   = data[1];
  col_2   = data[2];
  col_3   = data[3];
  
  document.getElementById("access_code").value = col_1;
  document.getElementById("access").value = col_2;
  document.getElementById("description").value = col_3;

}
function clearForm()
{
  document.getElementById("access_code").value = "";
  document.getElementById("access").value = "";
  document.getElementById("description").value = "";
}

function deleteAccess(param_id, param_code)
{
    var elementdelete = document.getElementById('delete_'+param_id);

    $.ajax({
                type : "GET",  //type of method
                url  : "process.php?type=setting-access-delete&param_id="+param_id+"&param_code="+param_code,  //your page
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


function saveAccess()
{
    var access = document.getElementById("access").value;
    var description = document.getElementById("description").value;
    var accessid = document.getElementById("access_code");
    var access_code = accessid.options[accessid.selectedIndex].value;
    var list_access     = document.getElementById('list-access');

    if(access === "" || access_code === "")
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
                url  : "process.php?type=setting-access-submit",  //your page
                data : { access_code_param : access_code, access_param : access, description_param : description},// passing the values
                success: function(response){  
                
                result  = JSON.parse(response);
                  
                    if(result.statuscode === 200)
                    {
                      list_access.innerHTML += "<tr style='font-size:13px;' id='delete_"+result.lastid+"'><td>"+result.access_code+"</td><td>"+result.access+"</td><td>"+result.description+"</td><td> <button onClick=deleteAccess('"+result.lastid+"','"+result.access_code+"') class='btn btn-inverse-danger'>Delete</button></td></tr>";
                       $("#access").val("");
                       $("#access_code").val("");
                       $("#description").val("");
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
                      var elementdelete = document.getElementById('delete_'+result.lastid);
                      elementdelete.remove();
           
              
                      list_access.innerHTML += "<tr style='font-size:13px;' id='delete_"+result.lastid+"'><td>"+result.access_code+"</td><td>"+result.access+"</td><td>"+result.description+"</td><td> <button onClick=deleteAccess('"+result.lastid+"','"+result.access_code+"') class='btn btn-inverse-danger'>Delete</button></td></tr>";
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
