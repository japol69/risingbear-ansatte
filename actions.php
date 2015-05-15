<?php
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
global $wpdb;
$table = $wpdb->prefix . "ansatte_settings";
$id = $_GET['jid'];
$mode = $_GET['jmode'];



switch ($mode) {
    case "addinfo":
		//print_r($_POST); exit;
		$name = $_POST['jname'];
		$position = $_POST['jposition'];
		$email = $_POST['jemail'];
		$phone = $_POST['jphone'];
		$mobile = $_POST['jmobile'];
		$photo = $_POST['jphoto'];
		// $page_id = $_POST['page_id'];
		$department = $_POST['department'];
		$staffbiography = $_POST['jbiography'];

		$contactinfo = array();
		array_push($contactinfo,$name);
		array_push($contactinfo,$position);
		array_push($contactinfo,$email);
		array_push($contactinfo,$phone);
		array_push($contactinfo,$mobile);
		array_push($contactinfo,$photo);
		array_push($contactinfo,$staffbiography);
		// array_push($contactinfo,$page_id);

		$contactinfo = implode(";;", $contactinfo);

		$table = $wpdb->prefix . "ansatte_settings";
		$result = $wpdb->query("SELECT * FROM `".$table."` WHERE `name`='contactinfo'");
			$wpdb->insert( $table, array( 'name' => "contactinfo", 'department' => $department, 'data' => $contactinfo  ) );
		echo "<p>Contact Info Added</p>";	
        break;


    case "editinfo":
		$jid = $_POST['jid'];
		require 'data-form.php';
        break;

	case "editsubmit":

		$id = $_POST['id'];
		$name = $_POST['jname'];
		$position = $_POST['jposition'];
		$email = $_POST['jemail'];
		$phone = $_POST['jphone'];
		$mobile = $_POST['jmobile'];
		$photo = $_POST['jphoto'];
		$page_id = $_POST['page_id'];
		$department = $_POST['department'];
		$staffbiography = $_POST['jbiography'];

		$contactinfo = array();
		array_push($contactinfo,$name);
		array_push($contactinfo,$position);
		array_push($contactinfo,$email);
		array_push($contactinfo,$phone);
		array_push($contactinfo,$mobile);
		array_push($contactinfo,$photo);
		//array_push($contactinfo,$page_id);
		array_push($contactinfo,$staffbiography);
		$contactinfo = implode(";;", $contactinfo);

		$table = $wpdb->prefix . "ansatte_settings";
		$result = $wpdb->query("SELECT * FROM `".$table."` WHERE `id`='".$id."'");

			$wpdb->update(
				$table,
				array(  'data' => $contactinfo ,  'department' => $_POST['department'] ),
				array( 'id' => $id )
			);
		echo "<p>Contact Info Updated</p>";			
		break;

		case "deleteinfo":
			$id = $_GET['jid'];
			$table = $wpdb->prefix . "ansatte_settings";
			$wpdb->delete( $table, array( 'id' => $id ) );
			echo '<p>Staff Deleted.</p>';
		break;

		case "sort":
			echo "<p>Sort Success</p>";
			$sort = $_POST['sortOrder'];
			$ssid = $_POST['sid'];
			$table = $wpdb->prefix . "ansatte_settings";
			$result = $wpdb->query("SELECT * FROM `".$table."` WHERE `name`='sort'");

			if($result < 1) {
				$wpdb->insert( $table, array( 'name' => "sort", 'data' => $sort  ) );
			} else {
				$wpdb->update(
					$table,
					array(  'data' => $sort ),
					array( 'id' => $ssid )
				);
			}

			break;
			
		case "AddDepartment":
			$table = $wpdb->prefix . "ansatte_settings";
			$result = $wpdb->query("SELECT * FROM `".$table."` WHERE `name`='department' AND `data`='".$_POST['dept_name']."'");

			if($result < 1) {
				$wpdb->insert( $table, array( 'name' => "department", 'data' => $_POST['dept_name']  ) );
				echo '<p>Department Added.</p>';
			} else {
				echo '<p>Error: Department already exists.</p>';
			}			
		break;
		
		case "EditDepartment":
			$table = $wpdb->prefix . "ansatte_settings";
			$wpdb->update(
				$table,
				array(  'data' => $_POST['dept_name'] ),
				array( 'id' => $_POST['dept_id'] )
			);		
			echo '<p>Department Updated.</p>';			
		break;	
		
		case "DeleteDepartment":
			$table = $wpdb->prefix . "ansatte_settings";
			$wpdb->delete( $table, array( 'id' => $_GET['jid'] ) );		
			echo '<p>Department Deleted.</p>';	
		break;
		
		case "settings":
			$data = implode(',',$_POST['ansatte_settings']);
			$table = $wpdb->prefix . "ansatte_settings";
			$result = $wpdb->get_row("SELECT * FROM `".$table."` WHERE `name`='settings'");
			$wpdb->update(
				$table,
				array(  'data' => $data ),
				array( 'id' => $result->id )
			);	
			echo '<p>Settings Saved.</p>';
		break;		
}
?>