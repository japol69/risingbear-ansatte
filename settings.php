<?php 
	global $wpdb;
	$table = $wpdb->prefix . "ansatte_settings";	
	$query = "SELECT * FROM `".$table."` WHERE `name`='settings'";
	$result = $wpdb->get_row($query);	
	
	$d = explode(',',$result->data);
?>
<style type="text/css">
#ansatte_message { background: #66FF99; font-size: 15px; font-weight: bold; position: fixed; left: 35%; top: calc(50% - 10px); width: 30%; border-radius: 10px;}
#ansatte_message p { font-size: 15px; margin: 0; padding: 20px;}
</style>

<div id="ansatte_message"></div>
<fieldset id="addform">
	<legend><h3>Settings:</h3></legend>
	<form id="settings" method="post">
	<?php 
		if( in_array('s_dept',$d) ) { $c_dept = ' checked="checked "';} else { $c_dept = ''; }
		if( in_array('s_name',$d) ) { $c_name = ' checked="checked "';} else { $c_name = ''; }
		if( in_array('s_position',$d) ) { $c_position = ' checked="checked "';} else { $c_position = ''; }
		if( in_array('s_phone',$d) ) { $c_phone = ' checked="checked "';} else { $c_phone = ''; }
		if( in_array('s_mobile',$d) ) { $c_mobile = ' checked="checked "';} else { $c_mobile = ''; }
		if( in_array('s_email',$d) ) { $c_email = ' checked="checked "';} else { $c_email = ''; }
		if( in_array('s_photo',$d) ) { $c_photo = ' checked="checked "';} else { $c_photo = ''; }
		if( in_array('s_staffbiography',$d) ) { $c_bio = ' checked="checked "';} else { $c_bio = ''; }
	?>	
	<p><input type="checkbox" name="ansatte_settings[]" value="s_dept" <?php echo $c_dept;?>/> Show Department</p>
	<p><input type="checkbox" name="ansatte_settings[]" value="s_name" <?php echo $c_name;?>/> Show Name</p>
	<p><input type="checkbox" name="ansatte_settings[]" value="s_position" <?php echo $c_position;?>/> Show Position</p>
	<p><input type="checkbox" name="ansatte_settings[]" value="s_phone" <?php echo $c_phone;?>/> Show Phone</p>
	<p><input type="checkbox" name="ansatte_settings[]" value="s_mobile" <?php echo $c_mobile;?>/> Show Mobile</p>
	<p><input type="checkbox" name="ansatte_settings[]" value="s_email" <?php echo $c_email;?>/> Show Email</p>
	<p><input type="checkbox" name="ansatte_settings[]" value="s_photo" <?php echo $c_photo;?>/> Show Photo</p>
    <p><input type="checkbox" name="ansatte_settings[]" value="s_staffbiography" <?php echo $c_bio;?>/> Show Staff Biography</p>
	<p><input type="submit" value="Save Settings" /></p>
</form>
</fieldset>

<script type="text/javascript">
jQuery(window).load(function(){	
	 //success message	
		
		jQuery("#settings").submit(function(e) {
            e.preventDefault();
			var str = jQuery(this).serialize();					 
			   jQuery.ajax({			   
			   type: "POST",
			   url: "<?php echo plugins_url(); ?>/risingbear-ansatte/actions.php?jmode=settings",
			   data: str,
			   beforeSend: function() { jQuery('#settings input[type=submit]').attr("disabled","disabled"); jQuery("#ansatte_message").html('<p>Processing, please wait...</p>'); },
			   success: function(msg){	
					jQuery("#ansatte_message").ajaxComplete(function(event, request, settings)
					{ if(msg == "OK"){  } else { result = msg; } jQuery(this).html(result); });	
					//var toId = jQuery('#unbookToHotel input[type=submit]').attr('id');
					 jQuery('#settings input[type=submit]').removeAttr("disabled");
					window.setTimeout(function(){location.reload()},2000)				 
				}					 
			 });					 
		});		
						

});	
</script>