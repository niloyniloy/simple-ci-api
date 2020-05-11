<div class="row">

<div class="col-lg-6">
    <div id="overview_category_section">
    <label><?php echo dashboard_lang("overview_category");?></label>
    <select name="overview_category[]" class="form-control dashboard-dropdown" multiple>
    <?php foreach($overview_category_list as $okey => $orow){?>
    <option value="<?php echo $orow['id'];?>" <?php echo $orow['selected'];?>><?php echo $orow['name'];?></option>
    <?php }?>
    </select>
    </div>


    <div id="category_section">
    <label><?php echo dashboard_lang("category");?></label>
    <select name="category[]" class="form-control dashboard-dropdown" multiple>
    <?php foreach($category_list as $ckey => $crow){?>
    <option value="<?php echo $crow['id'];?>" <?php echo $crow['selected'];?>><?php echo $crow['name'];?></option>
    <?php }?>
    </select>
    </div>
    
    
    
    <div id="project_role_section">
    <label><?php echo dashboard_lang("project_role");?></label>
    <select name="project_role[]" class="form-control dashboard-dropdown" multiple>
    <?php foreach($project_role_list as $pkey => $prow){?>
    <option value="<?php echo $prow['id'];?>" <?php echo $prow['selected'];?>><?php echo $prow['name'];?></option>
    <?php }?>
    </select>
    </div>

    

</div>

</div>

