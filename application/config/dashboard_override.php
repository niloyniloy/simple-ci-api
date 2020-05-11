<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Database table prefix
*/
$config['prefix']='';

/*
 * Site title
 */
$config['site_title']='Cadencemc';
/*
 * After login redirect path
 */
$config['redirect_path_after_login'] = "dbtables/advertisement/listing";

/*
 * Dashboard logo
*/
$config['site_logo'] = 'dashboardmedia/img/cadence.png';
/*
 * Image upload allow type
 */
$config['img_upload_allowed_types'] = array('jpg','jpeg','bmp','png','gif');

define ('CDN_URL', 'http://localhost/apis/');