<style type="text/css">
.aboutList { margin: 0 auto; padding: 0; list-style: none; width: 100%; max-width: 760px;}
.aboutList li { float: left; max-width: 220px; padding-left: 0; height: 340px; width: 20%; margin-left: 5%; padding-top: 20px; position: relative}
.aboutList li h3 { text-align: center}
.aboutList li h4, .aboutList li h4 input[type=text] { font-size: 14px; margin: 0 0 2px; padding: 0; color: #0091D4; text-transform: uppercase; text-align: center}
.aboutList li h5, .aboutList li h5 input[type=text]  { font-size: 12px; margin: 0 0 20px; padding: 0; color: #A3A2A2; text-transform: uppercase;; font-weight: normal; text-align: center}
.aboutList li img.ansatte_thumb { max-width: 107px; width: 100%; height: auto; left: 0; top: 0; margin: 0 20px 25px 0; border: 5px solid #eeeeee}
.aboutList li strong, .aboutList li em, .aboutList li a { display: block; font-size: 12px; color: #A3A2A2l; text-decoration: none; font-style: normal; display: block;}

.aboutList li p.controls { display: table; width: 107px;}
.aboutList li p.controls img { width: auto; height: auto}
.aboutList li p.controls a.data-edit { float: left}
.aboutList li p.controls a.data-delete { float: right}

.aboutList li #formbuttons { padding: 5px 0 0 0; margin: 6px 5px; border: none; width: 100%; border-radius: 0; border-top: 1px solid #ccc}
.aboutList li #formbuttons input { position: relative; z-index: 99; margin: 0 auto;}
.aboutList li #formbuttons p { position: static; top: 0; margin: 5px 0; padding: 0}

.aboutList li { border: 1px dashed #ccc; border-bottom: none; border-left: none}
.aboutList li:nth-child(4n+1) { margin-left: 0;}
.aboutList li:nth-child(4n) { border-right: none}
.aboutList li:nth-child(1), .aboutList li:nth-child(2), .aboutList li:nth-child(3), .aboutList li:nth-child(4) { border-top: none}
.aboutList li:nth-child(12):after,
.aboutList li:nth-child(24):after,
.aboutList li:nth-child(36):after,
.aboutList li:nth-child(48):after,
.aboutList li:nth-child(60):after,
.aboutList li:nth-child(72):after,
.aboutList li:nth-child(84):after,
.aboutList li:nth-child(96):after,
.aboutList li:nth-child(108):after { background: none repeat scroll 0 0 #ff0000; border: 1px solid red; bottom: 0; color: #fff; content: "End of page"; display: block; font-weight: bold; left: -580px; position: absolute; text-align: center; width: 760px; }
</style>
<script type="text/javascript">
function disp_confirm(name)
{
var r=confirm("Are you sure you want to delete "+name+" ?")
if (r==true)
  {
  //alert("You pressed OK!")
  }
else
  {
  	return false;
  }
}
</script>

<?php
	if($_GET['jmode']=="editinfo" && isset($_GET['jid'])){
?>
	<!--<h1>PAKKKKKKKKKYUUUUUUUUUUUUUUU!</h1>-->
<?php	
	} else {
?>
	<ul class="aboutList" id="aboutList">
	<?php
		$resultsort = $wpdb->get_results("SELECT * FROM `".$table."` WHERE `name`='sort'");
			foreach ($resultsort as $sort) {
				$xort = $sort->data;
				$sid =  $sort->id;
			}
	
		if($xort != "") {
			$jparam = "ORDER BY field(id, ".$xort.")";
		}
		else {
			echo '<h1 align="center">Warning: Make sure to sort and save order.</h1>';
			$jparam = "ORDER BY `id` DESC";
		}
	
		$query = "SELECT * FROM `".$table."` WHERE `name`='contactinfo' ".$jparam."" ;
		$result = $wpdb->get_results($query);
	?>	
	<?php
		$table2 = $wpdb->prefix . "ansatte_settings";	
		$query2 = "SELECT * FROM `".$table2."` WHERE `name`='settings'";
		$result2 = $wpdb->get_row($query2);	
		
		$d = explode(',',$result2->data);
		
	
		foreach ($result as $row) {
			$data = $row->data;
			$id = $row->id;
			$department = get_department_name($row->department);
			$data = explode(";;",$data);
	
	?>
		<li id="<?php echo $id; ?>">
			<p class="controls">
				<a href="<?php echo bloginfo('url'); ?>/wp-admin/admin.php?page=ansatte_info&jmode=editinfo&jid=<?php echo $id; ?>" class="data-edit" title="Edit"><img src="<?php echo plugins_url(); ?>/risingbear-ansatte/icon-edit.png" /></a> 
				<a href="<?php echo plugins_url(); ?>/risingbear-ansatte/actions.php?jmode=deleteinfo&jid=<?php echo $id; ?>" class="data-delete" title="Delete" class="staff_delete" rel="<?php echo $data[0]; ?>"><img src="<?php echo plugins_url(); ?>/risingbear-ansatte/icon-delete.png" /></a>
			</p>
			<?php if( in_array('s_dept',$d) ) {  ?><h3><?php echo $department; ?></h3><?php } ?>
			<?php if( in_array('s_photo',$d) ) {  ?>
				<?php
					if($data[5] == 'Photo' || $data[5] == ''){
						$thumbz = plugins_url().'/risingbear-ansatte/noimage.png';
					} else {
						$thumbz = $data[5];
					}
				?>
				<img src="<?php echo $thumbz; ?>" class="ansatte_thumb" />
			<?php } ?>	
			<?php if( in_array('s_name',$d) ) {  ?><strong><?php echo $data[0]; ?></strong><?php } ?>
			<?php if( in_array('s_position',$d) ) {  ?><em><?php echo $data[1]; ?></em><?php } ?>
			<?php if( in_array('s_phone',$d) ) {  ?><strong><?php echo $data[3]; ?></strong><?php } ?>
			<?php if( in_array('s_mobile',$d) ) {  ?><em><?php echo $data[4]; ?></em><?php } ?>
			<?php if( in_array('s_email',$d) ) {  ?><a href="mailto:<?php echo $data[2]; ?>"><?php echo $data[2]; ?></a><?php } ?>
			<?php if( in_array('s_staffbiography',$d) ) {  ?><?php echo $data[6]; ?></a><?php } ?>
			
		<?php	
			if( in_array('',$d) ) { $c_photo = ' checked="checked "';} else { $c_photo = ''; }
		?>	
		</li>
	<?php }	?>
	</ul>
<?php }//end if ?>

	<script> jQuery(function() {
		jQuery("#aboutList")
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
	<p style="float:none; clear:both">&nbsp;</p>
	<form id="sort_authors" method="post">
		<input type="hidden" value="sort" name="jmode" />
		<input type="hidden" value="<?php echo $sid; ?>" name="sid" />
		<input type="hidden" id="sortOrder" name="sortOrder" value="<?php echo $xort; ?>" />
		<p align="center"><input type="submit" value="Save Order" class="button" /></p>
	</form>

	<script type="text/javascript">
	jQuery(window).load(function(){	
		jQuery(".data-delete").click(function(e) {
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
		
		jQuery("#sort_authors").submit(function(e) {
            e.preventDefault();
			var str = jQuery(this).serialize();					 
			   jQuery.ajax({			   
			   type: "POST",
			   url: "<?php echo plugins_url(); ?>/risingbear-ansatte/actions.php?jmode=sort",
			   data: str,
			   beforeSend: function() { jQuery('#sort_authors input[type=submit]').attr("disabled","disabled"); jQuery("#ansatte_message").html('<p>Processing, please wait...</p>'); },
			   success: function(msg){	
					jQuery("#ansatte_message").ajaxComplete(function(event, request, settings)
					{ if(msg == "OK"){  } else { result = msg; } jQuery(this).html(result); });	
					//var toId = jQuery('#unbookToHotel input[type=submit]').attr('id');
					 jQuery('#sort_authors input[type=submit]').removeAttr("disabled");
					window.setTimeout(function(){location.reload()},2000)				 
				}					 
			 });					 
		});				
	
	});			
	</script>