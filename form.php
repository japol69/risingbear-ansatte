<?php 
require 'actions.php';

wp_enqueue_style('thickbox');
wp_enqueue_script('media-upload');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('thickbox');
wp_register_script('my-upload', WP_PLUGIN_URL.'/my-script.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('my-upload');

add_action('admin_print_scripts', 'my_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');

$table = $wpdb->prefix . "ansatte_settings";


?>
<style type="text/css">
#addform { width: 760px; margin: 25px auto 25px; background: #eee; padding: 20px; border-radius: 5px;}
#addform legend { background: #eee; border-radius: 5px 5px 0 0; padding: 5px 18px}
#ansatte_message { background: #66FF99; font-size: 15px; font-weight: bold; position: fixed; left: 35%; top: calc(50% - 10px); width: 30%; border-radius: 10px; z-index: 99999}
#ansatte_message p { font-size: 15px; margin: 0; padding: 20px;}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery(".logoupload").click(function () { 
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
<div id="ansatte_message"></div>
<fieldset id="addform">
	<legend><h3>Add Info:</h3></legend>
	<form id="addInfo" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="jmode" value="addinfo" />
	<p>Department: <?php ListDepartments(''); ?></p>
	<p>
	<input type="text" name="jname" value="Name" onfocus="if(this.value=='Name')this.value=''" onblur="if(this.value=='')this.value='Name'" />
	<input type="text" name="jposition" value="Position" onfocus="if(this.value=='Position')this.value=''" onblur="if(this.value=='')this.value='Position'"  />
	</p>
	<p>
	<input type="text" name="jphone" value="Phone"  onfocus="if(this.value=='Phone')this.value=''" onblur="if(this.value=='')this.value='Phone'"  />
	<input type="text" name="jmobile" value="Mobile"  onfocus="if(this.value=='Mobile')this.value=''" onblur="if(this.value=='')this.value='Mobile'"  />
	<input type="text" name="jemail" value="Email"  onfocus="if(this.value=='Email')this.value=''" onblur="if(this.value=='')this.value='Email'"  />	
	</p>
	<p><input type="text" name="jphoto" value="Photo" /> <input class="logoupload" type="button" value="Upload Photo" /></p>
    <p><textarea name="jbiography" placeholder="Staff Biography" rows="10" cols="70"></textarea></p>
	<p><input type="submit" value="Add Info" class="button" /></p>
</form>
</fieldset>

<script type="text/javascript">
jQuery(window).load(function(){	
	 //success message	
		
		jQuery("#addInfo").submit(function(e) {
            e.preventDefault();
			var str = jQuery(this).serialize();					 
			   jQuery.ajax({			   
			   type: "POST",
			   url: "<?php echo plugins_url(); ?>/risingbear-ansatte/actions.php?jmode=addinfo",
			   data: str,
			   beforeSend: function() { jQuery('#AddDepartment input[type=submit]').attr("disabled","disabled"); jQuery("#ansatte_message").html('<p>Processing, please wait...</p>'); },
			   success: function(msg){	
					jQuery("#ansatte_message").ajaxComplete(function(event, request, settings)
					{ if(msg == "OK"){  } else { result = msg; } jQuery(this).html(result); });	
					//var toId = jQuery('#unbookToHotel input[type=submit]').attr('id');
					jQuery('#AddDepartment input[type=submit]').removeAttr("disabled");
					jQuery('#addInfo')[0].reset(); 
					window.setTimeout(function(){location.reload()},2000)				 
				}					 
			 });					 
		});		
/*		
		jQuery("#EditDepartment").submit(function(e) {
            e.preventDefault();
			var str = jQuery(this).serialize();					 
			   jQuery.ajax({			   
			   type: "POST",
			   url: "<?php echo plugins_url(); ?>/risingbear-ansatte/actions.php?jmode=EditDepartment",
			   data: str,
			   beforeSend: function() { jQuery('#EditDepartment input[type=submit]').attr("disabled","disabled"); jQuery("#ansatte_message").html('<p>Processing, please wait...</p>'); },
			   success: function(msg){	
					jQuery("#ansatte_message").ajaxComplete(function(event, request, settings)
					{ if(msg == "OK"){  } else { result = msg; } jQuery(this).html(result); });	
					//var toId = jQuery('#unbookToHotel input[type=submit]').attr('id');
					 jQuery('#EditDepartment input[type=submit]').removeAttr("disabled");
					window.setTimeout(function(){  window.location.href = "<?php bloginfo('url'); ?>/wp-admin/admin.php?page=departments"; },2000)				 			 
				}					 
			 });					 
		});		
		
		jQuery(".deleteDepartment").click(function(e) {
			var u = jQuery(this).attr('href');
			var t = jQuery(this).attr('rel');
			var r = confirm("Are you sure you want to delete "+t+"");
			if (r == true) {
				e.preventDefault();
				jQuery("#ansatte_message").html('<p>Processing, please wait...</p>').load(u);
				window.setTimeout(function(){location.reload()},2000)	
			} else {
				e.preventDefault();
			}			
				 
		})			
*/						

});	
</script>