<?php $this->load->helper('translations');?>
<table width='300'>

<tr>
    <td> <?php echo get_lang_value("_TRAINING_NAME");?> </td>
    <td> <?php echo $details['training_id'];?></td>
 </tr>
 <tr>
    <td><?php echo get_lang_value("_ORDER_NUMBER");?></td>
    <td> <?php echo $details['order_number'];?> </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_FIRST_NAME");?> </td>
    <td> <?php echo $details['user_first_name'];?> </td>
 </tr>
 
 <tr>
    <td> <?php echo get_lang_value("_LAST_NAME");?> </td>
    <td> <?php echo $details['user_last_name'];?> </td>
 </tr>
 <tr>
    <td><?php echo get_lang_value("_GENDER");?> </td>
    <td> <?php echo $details['user_gender'];?> </td>
 </tr>
 
 <tr>
    <td><?php echo get_lang_value("_EMAIL");?> </td>
    <td> <?php echo $details['user_email'];?> </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_PHONE");?> </td>
    <td>  <?php echo $details['user_phone'];?> </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_ROLE");?> </td>
    <td>  <?php echo $details['user_role'];?> </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_DEPARTMENT");?> </td>
    <td>  <?php echo $details['user_department'];?> </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_COMPANY_NAME");?> </td>
    <td>  <?php echo $details['company_name'];?> </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_COMPANY_ADDRESS");?> </td>
    <td>  <?php echo $details['company_address'];?> </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_COMPANY_ZIPCODE");?> </td>
    <td> <?php echo $details['company_zipcode'];?>  </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_COMPANY_CITY");?> </td>
    <td> <?php echo $details['company_city'];?>  </td>
 </tr>
 <tr>
    <td> <?php echo get_lang_value("_COMPANY_COUNTRY");?> </td>
    <td>  <?php echo $details['company_country'];?> </td>
 </tr>
 
 <tr>
    <td> <?php echo get_lang_value("_VAT");?> </td>
    <td>  <?php echo $details['vat'];?> </td>
 </tr>
 
 <tr>
    <td> <?php echo get_lang_value("_AMOUNT");?> </td>
    <td> &euro;  <?php echo $details['amount'];?> </td>
 </tr>

 <tr>
    <td> <?php echo get_lang_value("_PURCHASE_ORDER_NUMBER");?> </td>
    <td>  <?php echo $details['purchase_order_number'];?> </td>
 </tr>
 
  <tr>
    <td> <?php echo get_lang_value("_ORDER_NUMBER");?> </td>
    <td> <?php echo $details['other_participants'];?>  </td>
 </tr>

</table>
