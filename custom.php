<?php
/*
Plugin Name: RisingBear Ansatte Plugin
Plugin URI: http://www.risingbear.no
Description: Staff list plugin
Version: 1.1
Author: RisingBear Web Developers
Author URI: http://www.risingbear.no
License: 
Date: 
Text Domain: 
Domain Path: 
*/

if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'post-thumbnails' );
	add_image_size( '107x33', 107, 33, true ); // listing thumb
}

register_activation_hook( __FILE__, 'ansatte_db' );
register_activation_hook( __FILE__, 'ansatte_db_data' );

function ansatte_db() {
 global $wpdb;
 global $ansatte_db_version; 
 $table_name = $wpdb->prefix . "ansatte_settings";
 $sql = "CREATE TABLE $table_name (
   id mediumint(9) NOT NULL AUTO_INCREMENT,
   name longtext NOT NULL,
   department longtext NOT NULL,
   data longtext NOT NULL,
   UNIQUE KEY id (id)
   );";
   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
   add_option("ansatte_db_version", $ansatte_db_version);
}


function ansatte_db_data() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'ansatte_settings';
	$data = 's_dept,s_name,s_position,s_phone,s_mobile,s_email,s_photo';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'name' => 'settings', 
			'data' => $data, 
		) 
	);
}


add_action('admin_menu', 'ansatte_nav');
function ansatte_nav() {
	add_menu_page( "Ansatte", "Ansatte", "manage_options", "ansatte_info", "func_ansatteInfo");	
	add_submenu_page( "ansatte_info", "Departments", "Departments", "manage_options", "departments", "func_departments");
	add_submenu_page( "ansatte_info", "Short Codes", "Short Codes", "manage_options", "shortcodes", "func_ShortCodes");
	add_submenu_page( "ansatte_info", "Settings", "Settings", "manage_options", "settings", "func_Settings");	
	//add_submenu_page( "theme_settings", "Test", "Test", "manage_options", "test", "test");	
} 

function func_ansatteInfo(){
	include 'form.php';
	include 'datas.php';
}

function func_departments(){
	require 'departments.php';
}

function func_ShortCodes(){
	require 'shortcodes.php';
}

function func_Settings(){
	require 'settings.php';
}

/////////////////////// Front end stuffs
add_shortcode( 'jInfo', 'jInfo' );
function jInfo($atts){
	extract( shortcode_atts( array( 'id' => '', 'filter' => ''), $atts ) );
	global $wpdb;
	$table = $wpdb->prefix . "ansatte_settings";
	$query = "SELECT * FROM `".$table."` WHERE `id`='".$id."'" ;
	$result = $wpdb->get_results($query);
	foreach ($result as $row) {
		$data = $row->data;
		$id = $row->id;
		$data = explode(";;",$data);	
		
		
	switch ($filter) {
		case "name":
			$output .= $data[0];;
			break;
		case "position":
			$output .= $data[1];
			break;
		
		case "email":
			$output .= '<a href="mailto:'.$data[2].'">'.$data[2].'</a>';
			break;	
					
		case "phone":
			$output .= $data[3];
			break;	
			
		case "mobile":
			$output .= $data[4];
			break;	
			
		case "photo":
			$output .= '<img src="'.$data[5].'"'.$data[5].' />';
			break;	
			
		case "staffbiography":
			$output .= $data[6];
			break;						
			
		default:
			$output .= '<ul class="aboutList" id="aboutList">';
			$output .=	'<li>';
			$output .=		'<h4>'.$data[0].'</h4>';
			$output .=		'<h5>'.$data[1].'</h5>';
			$output .=		'<div><img src="'.$data[5].'" /></div>';
			$output .=		'<strong>'.$data[3].'</strong>';
			$output .=		'<em>'.$data[4].'</em>';
			$output .=		'<a href="mailto:'.$data[2].'">'.$data[2].'</a>';
			$output .=	'</li>';
			$output .= '</ul>';
			$output .= '<hr class="clear">';
			$output .= '<p>'.$data[6].'</p>';
			break;
	}		

	}

	
	return $output;	
}

add_shortcode( 'jAllInfo', 'jAllInfo' );
function jAllInfo($atts){
	extract( shortcode_atts( array( 'department_id' => ''), $atts ) );
	global $wpdb;
	$table = $wpdb->prefix . "ansatte_settings";
	$resultsort = $wpdb->get_results("SELECT * FROM `".$table."` WHERE `name`='sort'");
		foreach ($resultsort as $sort) {
			$xort = $sort->data;
		}	

	if($xort != "") { 
		$jparam = "ORDER BY field(id, ".$xort.")"; 
	}	
	else {
		$jparam = "ORDER BY `id` DESC";
	}
	
	if($department_id != ""){
		$kparam = " AND `department`='$department_id' ";
	}
	
	$query = "SELECT * FROM `".$table."` WHERE `name`='contactinfo' ".$kparam.$jparam."" ;
	$result = $wpdb->get_results($query);
	$len = count($result);
	
	
	$queryz = "SELECT * FROM `".$table."` WHERE `name`='settings'";
	$resultz = $wpdb->get_row($queryz);	
	$d = explode(',',$resultz->data);	
	
	
	$output .='<div id="employees">';
	$ctr = 1;
	foreach ($result as $row) {
		$data = $row->data;
		$id = $row->id;
		$department = get_department_name($row->department);
		$data = explode(";;",$data);
			$output .=	'<div class="span6">'."\r\n";
			$real_department= str_replace("\\\\", "\\", $department);
			
			if( in_array('s_photo',$d) ) { 
				if($data[5] == 'Photo' || $data[5] == ''){
					//$thumbz = plugins_url().'/risingbear-ansatte/noimage.png';
					$output .=''; 
				} else {
					//$thumbz = $data[5];
					$output .='<img src="'.$data[5].'" class="ansatte_thumb" /><br>'."\r\n"; 
				}
				
			}
			if( in_array('s_name',$d) ) {  $output .='<b>'.$data[0].'</b><br>'."\r\n"; }
			$output .= '<em>';
			if( in_array('s_dept',$d) ) {  $output .= $real_department." - ";  }
			if( in_array('s_position',$d) ) {  $output .= $data[1]; }
			$output .= '</em><br>'."\r\n";		
			if( in_array('s_phone',$d) ) {  $output .='<strong>'.$data[3].'</strong><br>'."\r\n"; }
			if( in_array('s_mobile',$d) ) {  $output .=''.$data[4].'<br>'."\r\n"; }
			if( in_array('s_email',$d) ) {  $output .='<a href="mailto:'.$data[2].'">'.$data[2].'</a><br>'."\r\n"; }
			if( in_array('s_staffbiography',$d) ) {  $output .='<p>'.$data[6].'</p><br>'."\r\n"; }
			$output .=	'</div>'."\r\n";
		$ctr++;				
	} // end foreach
	$output .='</div>';
	
	return $output;	
}


function ListPages($id){
	$args = array(
		'depth'            => 0,
		'child_of'         => 0,
		'selected'         => $id,
		'echo'             => 1,
		'name'             => 'page_id'
	); 
	$xpages = wp_dropdown_pages($args); 
}

function ListDepartments($department_names_d){
	$current_select = $department_names_d;
	
	global $wpdb;
	$table = $wpdb->prefix . "ansatte_settings";
	$query = "SELECT * FROM `".$table."` WHERE `name`='department' ORDER BY `data` ASC" ;
	$result = $wpdb->get_results($query);

	if(count($result) > 0){
		$output .= '<select name="department">';
				
			foreach($result as $dept){
				if($current_select == $dept){
					$output .= '<option value="'.$dept->id.'" selected>'.$dept->data.'</option>';
				}
				else{
					$output .= '<option value="'.$dept->id.'">'.$dept->data.'</option>';
				}
			}
		$output .= '</select>';
	} else {
		$output .= 'No department added. Click <a href="'.get_bloginfo("url").'/wp-admin/admin.php?page=departments">here</a> to add department.';
	}
	echo $output;
}
include 'drew-widget.php';


function get_department_name($depId){
	global $wpdb;
	$table = $wpdb->prefix . "ansatte_settings";
	$query = "SELECT * FROM `".$table."` WHERE `id`='".$depId."' AND `name`='department'" ;
	$result = $wpdb->get_row($query);	
	//print_r($result);
	//echo  $depId. '<<< paaaaaaaaaaaaaaaakkkkkkkkkkkkkkyuuuuuuuuuuuuuuuu >>>'.$result->department;
	return $result->data;
}
?>