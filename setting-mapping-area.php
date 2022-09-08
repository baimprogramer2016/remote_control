<?php
include('getdata.php');
$rolecode = $_GET['role_code_param'];
$datamap = getAreaLightingFilter($rolecode);


?>

    <div class="container p-3" >

        <div class="row bg-dark rounded" >
        <input type="hidden" name="mapping_role" id="mapping_role" value="<?php echo $rolecode;?>">
        <input type="hidden" name="tipemodal" id="tipemodal" value="area">
           
        <!-- <input type="text" name="mapping_access_selected" id="mapping_access_selected"> -->
            <?php
            foreach($datamap as $item_map)
            {
            ?>
                <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input bg-success mappingarea" type="checkbox"  <?php echo $item_map['active'];?> name="mapping_area[]" value="<?php echo $item_map['kodearea'];?>" >
                                <label class="form-check-label" for="flexCheckDefault">
                                    <?php echo $item_map['area'];?>
                                </label>
                            </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
