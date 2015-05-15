<style type="text/css">

.aboutList { margin: 0 auto; padding: 0; list-style: none; width: 100%;}
.aboutList li:first-child { border: none}
.aboutList li { border-top: 1px dashed #ccc; border-bottom: none; border-left: none; padding: 8px;}
#ansatte_message { background: #66FF99; font-size: 15px; font-weight: bold; position: fixed; left: 35%; top: calc(50% - 10px); width: 30%; border-radius: 10px;}
#ansatte_message p { font-size: 15px; margin: 0; padding: 20px;}
#sort_authors { display: none}
</style>
<script> jQuery(function() { 
	jQuery("#authors")
	.sortable({
        update: function(event, ui) {
            var newOrder = jQuery(this).sortable('toArray').toString();
            //var x = jQuery.get('saveSortable.php', {order:newOrder});
			jQuery("#sortOrder").val(newOrder);
        }	
	})
	.disableSelection(); 
}); 
</script>

<div id="ansatte_message"></div>
<?php 

	
	if( $_GET['jmode'] == 'EditDepartment' && isset($_GET['jid'])){
	
		global $wpdb;
		$table = $wpdb->prefix . "ansatte_settings";	
		$query = "SELECT * FROM `".$table."` WHERE `id`='".$_GET['jid']."'";
		$result = $wpdb->get_row($query);	
?>
	<h1>Edit Department</h1>
		<form id="EditDepartment">
			<input type="hidden" name="dept_id" value="<?php echo $result->id; ?>" />
			<input type="text" name="dept_name" value="<?php echo $result->data; ?>" /> <input type="submit" value="Update Department"  />
		</form>	
<?php 	
	} else {
		global $wpdb;
		$table = $wpdb->prefix . "ansatte_settings";	
		$query = "SELECT * FROM `".$table."` WHERE `name`='department'";
		$result = $wpdb->get_results($query);	
?>
		<h1>Add Department</h1>
		<form id="AddDepartment">
			<input type="text" name="dept_name" /> <input type="submit" value="Add Department"  />
		</form>
		<hr />
		<p>&nbsp;</p>
		<h1>Departments</h1>
		<ul class="aboutList" id="authors">
		<?	
			foreach ($result as $row) {
				$data = $row->data;
				$id = $row->id;
				//$data = explode(";;",$data);
		
		?>
			<li id="<?php echo $id; ?>">
				<?php echo $data; ?>
				&nbsp; | &nbsp; 
				<a href="<?php echo bloginfo('url'); ?>/wp-admin/admin.php?page=departments&jmode=EditDepartment&jid=<?php echo $id; ?>">Edit</a>
				&nbsp; | &nbsp; 
				<a href="<?php echo plugins_url(); ?>/risingbear-ansatte/actions.php?jmode=DeleteDepartment&jid=<?php echo $id; ?>" rel="<?php echo $data; ?>" class="deleteDepartment">Delete</a>
		
		
			</li>
		<?php 	}	?>
		</ul>
		<p style="float:none; clear:both">&nbsp;</p>
		<form id="sort_authors" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">	
			<input type="hidden" value="sort_authors" name="mode" />
			<input type="text" id="sortOrder" name="sortOrder" value="<?php echo $author->data; ?>" />
			<input type="submit" value="<?php echo $text05; ?>" />
		</form>
<?php } //end if ?>	
<script type="text/javascript">
jQuery(window).load(function(){	
	 //success message	
		
		jQuery("#AddDepartment").submit(function(e) {
            e.preventDefault();
			var str = jQuery(this).serialize();					 
			   jQuery.ajax({			   
			   type: "POST",
			   url: "<?php echo plugins_url(); ?>/risingbear-ansatte/actions.php?jmode=AddDepartment",
			   data: str,
			   beforeSend: function() { jQuery('#AddDepartment input[type=submit]').attr("disabled","disabled"); jQuery("#ansatte_message").html('<p>Processing, please wait...</p>'); },
			   success: function(msg){	
					jQuery("#ansatte_message").ajaxComplete(function(event, request, settings)
					{ if(msg == "OK"){  } else { result = msg; } jQuery(this).html(result); });	
					//var toId = jQuery('#unbookToHotel input[type=submit]').attr('id');
					 jQuery('#AddDepartment input[type=submit]').removeAttr("disabled");
					window.setTimeout(function(){location.reload()},2000)				 
				}					 
			 });					 
		});		
		
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
						

});	
</script>

	