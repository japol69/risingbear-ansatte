<?php
// require_once ABSPATH . WPINC . "/functions.php";


//create class and extend the WP widget
class Drew_Ansatte_Widget extends WP_Widget{
	function __construct(){

		//classname and description of the widget
		$ansatte_classname_desc = array('classname' => 'widget_ansatte', 'description' => __('Ansatte Widget') );

		//Widget Controls ofwidth and height
		$w_control = array('width' => 250, 'height' => 350, 'id_base' => 'drewansatte');

		//Create the Widget
		$this->WP_Widget('drewansatte', __('Ansatte Widget'), $ansatte_classname_desc, $w_control);
	}

	function widget($args, $instance){
		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Ansatte') : $instance['title'], $instance, $this->id_base );

		//get the db informations
		global $wpdb;
		$table = $wpdb->prefix . 'ansatte_settings';
		$query = "SELECT * FROM `". $table ."` WHERE `name` = 'sort'";
		$result_sort = $wpdb->get_results($query);

		// print_r($result_sort);

		foreach($result_sort as $sort){
			$xort = $sort->data;
		}

		if($xort != ""){
			$dparam = "ORDER BY field(id, ".$xort.")";
		}
		else{
			$dparam = "ORDER BY `id` DESC";
		}

		$second_query = "SELECT * FROM `".$table."` WHERE `name`='contactinfo' ".$dparam."";
		$result = $wpdb->get_results($second_query);

		//display
		echo '<div class="widget widget_ansatte_drew">';
		if($title){
			echo $before_title.$title.$after_title;
		}
		echo '<div class="flexslider">';
		echo '<ul class="slides">';

		foreach ($result as $row) {
			$data = $row->data;
			$id = $row->id;
			$department = $row->department;
			$data = explode(";;", $data);
				$d_department = str_replace("\\\\", "\\", $department);

				$output .= '<li>';
				$output .= '<h3>'. $d_department .'</h3>';
				$output .= '<img src="'.$data[5].'" /><br>';
				$output .= '<b>'.$data[0].'</b><br>';
				$output .= '<em>'.$data[1].'</em><br>';
				$output .= $data[3]. '<br>';
				$output .= $data[4]. '<br>';
				$output .= '<a href="mailto:'.$data[2].'">'.$data[2].'</a>';
				$output .= '</li>';

				$cache[$args['widget_id']] = ob_get_flush();

		}
				echo $output;
		echo '</div></div>';
		echo '<link rel="stylesheet" href="'.plugins_url().'/risingbear-ansatte/flexslider/flexslider.css">';
		echo '<script src="'.plugins_url().'/risingbear-ansatte/flexslider/jquery.flexslider.js"></script>';
		echo '<script src="'.plugins_url().'/risingbear-ansatte/flexslider/widgetslide.js"></script>';
	}

	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ){
		$title = isset($instance['title']) ? esc_attr($instance['title'] ) : '';

?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input type="text" class="widget_title" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
	</p>
<?php
	}

}

/* Register now this widget */
if(!function_exists('ansatte_widget_register')){
	function ansatte_widget_register(){
		register_widget( 'Drew_Ansatte_Widget' );
	}
}
add_action('widgets_init', 'ansatte_widget_register' );

?>