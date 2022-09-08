<?php
session_start();
if($_SESSION['npk'] == "")
{
  header("location:index.php");
}
include('getdata.php');
$index      = $_GET['index'];

$datalogs   = getLogsLoadMore($index);

                      foreach($datalogs['datalog'] as $log)
                      {
                        //warna description
                        if($log['type'] == 'SCHEDULE LIGHTING' && $log['status'] == "ON")
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