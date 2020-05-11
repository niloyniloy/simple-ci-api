
<div class="language_section"><?php echo dashboard_lang("languages");?></div>

<ul>
<?php foreach($languages as $key => $language) {?>

<li class="nav">
<label>
<input name="languages[]" type="checkbox" value="<?php echo $language['id'];?>" <?php echo $language['checked']; ?> />
<?php echo $language['name']; ?></label>
</li>


<?php }?>


</ul>