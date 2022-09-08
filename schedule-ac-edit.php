
<?php
include('function.php');

$dataacedit   = getDataAc($_GET['param_id']);
$id           = $dataacedit[0]['id'];
$address      = $dataacedit[0]['address'];
$port         = $dataacedit[0]['port'];
$description  = $dataacedit[0]['description'];
$dataac       = $dataacedit[0]['dataac'];
$onscheduler  = $dataacedit[0]['onscheduler'];


?>
<div class="page-header">
<h3 class="page-title">Update Schedule</h3>
</div>
<div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Air Conditioner</h4>
                    <p class="card-description">
                      Gunakan Tanda <code>Titik Dua ( : )</code> sebagai pemisah
                      antara jam dan menit
                    </p>
                    <div class="form-inline">
                      <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Time</div>
                        </div>
                        <input
                          type="text"
                          size="5"
                          id="schedule_time"
                          name="schedule_time"
                          class="form-control text-white bg-dark"
                          placeholder="23:59"
                          
                        />
                      </div>
                      <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Address</div>
                        </div>
                        <input
                          required
                          readonly
                          type="text"
                          size="5"
                          name="schedule_address"
                          class="form-control"
                          style="background-color:#2A3038"
                          id="schedule_address"
                          value="<?php echo $address;?>"
                        />
                      </div>
                      <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Port</div>
                        </div>
                        <input
                          readonly
                          size="5"
                          type="text"
                          name="schedule_port"
                          style="background-color:#2A3038"
                          class="form-control text-white"
                          id="schedule_port"
                          value="<?php echo $port;?>"
                        />
                      </div>
                      <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Status</div>
                        </div>
                        <select
                          name="schedule_status"
                          class="form-control text-white bg-dark"
                          id="schedule_status"
                        >
                          <option value="on">ON</option>
                          <option value="off">OFF</option>
                        </select>
                      </div>
                      <input type="hidden" value="<?php echo $description;?>" name="schedule-desc" id="schedule-desc">
                      <button
                        onClick="saveScheduleAc()"
                        type="submit"
                        class="form-control btn btn-inverse-primary mb-2 mr-2"
                      >
                        Save
                      </button>
                      <button
                        onClick="return backScheduleAc()"
                        type="submit"
                        class="form-control btn btn-inverse-warning mb-2"
                      >
                        Cancel
                      </button>
</div>
                  </div>
                </div>
              </div>
</div>

<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Description</th>
                            <th>Status Schedule</th>
                            <th>Remote Address</th>
                            <th>Port</th>
                            <th>Schedule | Delete</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="text-white"><?php echo $description;?></td>
                            <td>
                              <?php echo ($onscheduler == "1")?'<span class="text-success">On Schedule</span>':'<span class="text-warning">No Schedule</span>';?>
                            </td>
                            <td><?php echo $address;?></td>
                            <td>: <?php echo $port;?></td>
                            <td>
                              <div class="row">
                                <div class="col-md-12" id="list-schedule">
                                  <?php
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
                                  ?>         
                                    <div class="col-md-8 badge badge-outline-<?php echo $colorstatus;?> mb-1" id="delete_<?php echo $val['id'];?>">
                                      <?php echo ucfirst($val['statusschedule'] .' '.$val['timeschedule']);?> 
                                    </div>  <i class="mdi  mdi-delete clickTrash" onClick="deleteSchedule(<?php echo  $val['id'];?>)" id="trash_<?php echo $val['id'];?>"></i>
                                    
                                    <br>
                                  <?php
                                  }
                                  ?>
                                </div>
                              </div>
                              </div>
                            </td>
                         
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script> 
<script>
//delete
function deleteSchedule(param_id)
{
 
    var elementdelete = document.getElementById('delete_'+param_id);
    var elementtrash = document.getElementById('trash_'+param_id);

    $.ajax({
                type : "GET",  //type of method
                url  : "process.php?type=scheduler-ac-delete&param_id="+param_id,  //your page
                success: function(response){  
                result  = JSON.parse(response);
                // console.log(result);
                  if(result.statuscode === 200)
                  {
                    elementdelete.remove();
                    elementtrash.remove();
                  }else{
                    alert("Failed to Delete data");
                  }
                }  
      });
}

 //save data 
function saveScheduleAc()
{
  var schedule_address  = document.getElementById('schedule_address').value;
  var schedule_time     = document.getElementById('schedule_time').value;
  var schedule_port     = document.getElementById('schedule_port').value;
  var schedule_status   = document.getElementById('schedule_status').value;
  var schedule_desc   = document.getElementById('schedule-desc').value;
  var list_schedule     = document.getElementById('list-schedule');
  
  if(schedule_time === '')
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
              url  : "process.php?type=scheduler-ac-submit",  //your page
              data : { address : schedule_address, time : schedule_time, port : schedule_port, status:  schedule_status, description: schedule_desc},// passing the values
              success: function(response){  
              result  = JSON.parse(response);
              // console.log(result);
                if(result.statuscode === 200)
                {
                  if(result.status === 'On')
                  {
                    color = 'success';
                  }
                  else{
                    color = 'danger';
                  }
                  list_schedule.innerHTML += '<div class="col-md-8 badge badge-outline-'+color+' mb-1" id=delete_'+result.lastid+'>'+result.status+' '+result.time+'</div>  <i class="mdi  mdi-delete clickTrash" onClick="deleteSchedule('+result.lastid+')" id="trash_'+result.lastid+'"></i><br>';
                }else{
                  alert("Failed to save data");
                }
              }  
    });
  }
  
}

//back 
function backScheduleAc()
{
  location.href = "main.php?page=schedule-ac";
}
</script>
