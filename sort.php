<style type="text/css">
.aboutList { margin: 0 auto; padding: 0; list-style: none; width: 100%; max-width: 885px;}
.aboutList li { float: left; max-width: 220px; padding-left: 5%; height: 330px; width: 20%; padding-top: 20px; position: relative}
.aboutList li p { float: left; margin: 20px 0; padding: 0; width: 50%;}
.aboutList li h4, .aboutList li h4 input[type=text] { font-size: 14px; margin: 0 0 2px; padding: 0; color: #0091D4; text-transform: uppercase}
.aboutList li h5, .aboutList li h5 input[type=text]  { font-size: 12px; margin: 0 0 20px; padding: 0; color: #A3A2A2; text-transform: uppercase;; font-weight: normal}
.aboutList li div img { max-width: 150px; width: 100%; height: auto; position: absolute; left: 0; top: 0;}
.aboutList li div { width: 127px; height: 127px; border: 6px solid #C9C9C9; position: relative; overflow: hidden; border-radius: 300px; margin-bottom: 20px;}
/*.aboutList li:after { content: url(../images/about-arrow.png); display: block; width: 26px; height: 29px; border: 1px solid red; position: absolute; top: 200px; right: 50px;}*/
.aboutList li strong, .aboutList li em, .aboutList li a { display: block; font-size: 12px; color: #A3A2A2l; font-weight: normal; text-decoration: none; font-style: normal; display: inline-block; padding-left: 30px;}


.aboutList li { border: 1px dashed #ccc; border-bottom: none; border-left: none}
.aboutList li:nth-child(4n+1) { padding-left: 0;}
.aboutList li:nth-child(4n) { border-right: none}
.aboutList li:nth-child(1), .aboutList li:nth-child(2), .aboutList li:nth-child(3), .aboutList li:nth-child(4) { border-top: none}
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


<h1>Sort users</h1>
<?php 
	global $wpdb;
	$table = $wpdb->prefix . "ansatte_settings";	
	$query = "SELECT * FROM `".$table."` WHERE `name`='contactinfo'";
	$result = $wpdb->get_results($query);
?>

<ul class="aboutList" id="authors">
<?	
	foreach ($result as $row) {
		$data = $row->data;
		$id = $row->id;
		$data = explode(";;",$data);

?>
	<li id="<?php echo $id; ?>">
		
		<h4><?php echo $data[0]; ?></h4>
		<h5><?php echo $data[1]; ?></h5>
		<div><img src="<?php echo $data[5]; ?>" /></div>
		
		<strong><?php echo $data[3]; ?></strong>
		<em><?php echo $data[4]; ?></em>
		<a href="mailto:<?php echo $data[2]; ?>"><?php echo $data[2]; ?></a>

		<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="jmode" value="editinfo" />
			<input type="hidden" name="jid" value="<?php echo $id; ?>" />
			<p><input type="submit" value="Edit Info" class="button" /></p>
		</form>			
		
		<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="jmode" value="deleteinfo" />
			<input type="hidden" name="jid" value="<?php echo $id; ?>" />
			<p><input type="submit" value="Delete" class="button" onclick="return disp_confirm('<?php echo $data[0]; ?>')" /></p>
		</form>					

	</li>
<?php 	}	?>
</ul>



						
	<p style="float:none; clear:both">&nbsp;</p>
	<form id="sort_authors" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">	
		<input type="hidden" value="sort_authors" name="mode" />
		<input type="text" id="sortOrder" name="sortOrder" value="<?php echo $author->data; ?>" />
		<input type="submit" value="<?php echo $text05; ?>" />
	</form>