<?php
include('getdata.php');
$rolecode = $_GET['role_code_param'];
$datamap = getMappingAccess($rolecode);
?>

    <div class="container p-3" >

        <div class="row bg-dark rounded" >
        <input type="hidden" name="mapping_role" id="mapping_role" value="<?php echo $rolecode;?>">
        <input type="hidden" name="tipemodal" id="tipemodal" value="access">
           
        <!-- <input type="text" name="mapping_access_selected" id="mapping_access_selected"> -->
            <?php
            foreach($datamap as $item_map)
            {
            ?>
           
                <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input bg-success mappingaccess" type="checkbox"  <?php echo $item_map['active'];?> name="mapping_access[]" value="<?php echo $item_map['access_code'];?>" >
                                <label class="form-check-label" for="flexCheckDefault">
                                    <?php echo $item_map['description'];?>
                                </label>
                            </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
