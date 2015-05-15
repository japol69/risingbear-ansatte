<style type="text/css">
#editform { width: 760px; margin: 25px auto 25px; background: #eee; padding: 20px; border-radius: 5px;}
#editform legend { background: #eee; border-radius: 5px 5px 0 0; padding: 5px 18px}
#addform, .aboutList, #sort_authors { display: none;}
</style>
<?php 
require_once ABSPATH . WPINC . "/functions.php";
global $wpdb;

		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('my-upload', WP_PLUGIN_URL.'/my-script.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('my-upload');
		
		add_action('admin_print_scripts', 'my_admin_scripts');
		add_action('admin_print_styles', 'my_admin_styles');
		
$table = $wpdb->prefix . "ansatte_settings";

$result = $wpdb->get_results("SELECT * FROM `".$table."` WHERE `id`='".$_GET['jid']."'");

	foreach ($result as $row) {
		$data = $row->data;
		$id = $row->id;
		$data = explode(";;",$data);
		$department = $row->department;
	}
		
?>

<script type="text/javascript">
jQuery(document).ready(function() {

    jQuery(".logoupload2").click(function () { 
		uploadID = jQuery(this).prev('input');
		var formfield = jQuery('#upload_image').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
    });	

	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		uploadID.val(imgurl);
		tb_remove();
	};

});
</script>
<fieldset id="editform">
	<legend><h3>Edit Info:</h3></legend>
	<form method="post" id="editInfo">
	<input type="hidden" name="jmode" value="editsubmit" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<?php  $real_department= str_replace("\\\\", "\\", $department); //echo $real_department; ?>
	<p>Department: <?php ListDepartments($real_department); ?></p>
	<p>
	<input type="text" name="jname" value="<?php echo $data[0]; ?>" />
	<input type="text" name="jposition" value="<?php echo $data[1]; ?>"  />
	</p>
	<p>
	<input type="text" name="jphone" value="<?php echo $data[3]; ?>" />	
	<input type="text" name="jmobile" value="<?php echo $data[4]; ?>"  />
	<input type="text" name="jemail" value="<?php echo $data[2]; ?>"  />	
	</p>
	<p><input type="text" name="jphoto" value="<?php echo $data[5]; ?>" /> <input class="logoupload2" type="button" value="Upload Photo" /> <a href="<?php echo $data[5]; ?>" target="_blank">Preview</a></p>
	<p><textarea name="jbiography"><?php echo $data[6]; ?></textarea></p>
	<p align="center"><input type="submit" value="Edit Info" class="button" /> &nbsp; &nbsp; <a href="?page=ansatte_info" class="button">Cancel</a></p>
</form>
</fieldset>
<script type="text/javascript">
jQuery(window).load(function(){	
	 //success message	
		
		jQuery("#editInfo").submit(function(e) {
            e.preventDefault();
			var str = jQuery(this).serialize();					 
			   jQuery.ajax({			   
			   type: "POST",
			   url: "<?php echo plugins_url(); ?>/risingbear-ansatte/actions.php?jmode=editsubmit",
			   data: str,
			   beforeSend: function() { jQuery('#editInfo input[type=submit]').attr("disabled","disabled"); jQuery("#ansatte_message").html('<p>Processing, please wait...</p>'); },
			   success: function(msg){	
					jQuery("#ansatte_message").ajaxComplete(function(event, request, settings)
					{ if(msg == "OK"){  } else { result = msg; } jQuery(this).html(result); });	
					//var toId = jQuery('#unbookToHotel input[type=submit]').attr('id');
					 jQuery('#editInfo input[type=submit]').removeAttr("disabled");
					window.setTimeout(function(){  window.location.href = "<?php bloginfo('url'); ?>/wp-admin/admin.php?page=ansatte_info"; },2000)
				}					 
			 });					 
		});		
					

});	
</script>