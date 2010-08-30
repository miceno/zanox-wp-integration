<?php
/*
Plugin Name: Zanox Widget
Plugin URI: http://wordpress.org/extend/plugins/zanox-widget/
Description: Allows the user to present their personal or their project's Zanox-metrics as a widget.
Version: 0.1
Author: hangy
Author URI: http://hangy.de/
*/


function widget_zanox_init() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;	

	function widget_zanox( $args ) {
		extract( $args );
		$options = get_option('widget_zanox');
	
		// If any of these options is empty, we do not even try to continue
		// executing the widget-method.
		if ( empty( $options['id'] ) || empty( $options['targetType'] ))
			return;
	
		$title = empty( $options['title'] ) ? 'Zanox' : $options['title'];
		$id = empty( $options['id'] ) ? 0 : $options['id'];
		$targetType = empty( $options['targetType'] ) ? 0 : $options['targetType'];
	
		$out = get_widget_zanox_code( $targetType, $id );
		if ( !empty( $out ) ) {
	?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<?php echo $out; ?>
		<?php echo $after_widget; ?>
	<?php
		}
	}
	
	function get_widget_zanox_code( $targetType, $id ) {
		// this is the var which contains our result.
		$result = "";
	        $result = get_widget_zanox_code_file( $targetType );

		return $result;
	}
	
	function get_widget_zanox_code_file( $targetType ) {
		// this is the var which contains our result.
		$plugin_path= dirname(__FILE__);

		// Read file zanox-$targetType.shtml
		$result = "to be done";
		$result = file_get_contents( $plugin_path . '/zanox-' . $targetType . '.shtml' );
		if( empty( $result))
		   $result= "problemas";
	
		return $result;
	}
	
	function widget_zanox_control() {
		// Types of targets
		// TODO: get targetTypes from the database
        $targetTypes= array( 'barcelo-multibuscador', 
                            'barcelo-hoteles', 
                            'barcelo-hoteles-vertical', 
                            'barcelo-vuelos', 
                            'barcelo-vuelos-vertical',
                            'casadellibro-logo' );

		// Read options
		$options = $newoptions = get_option('widget_zanox');
		if ( $_POST['zanox-submit'] ) {
			$newoptions['title'] = strip_tags( stripslashes( $_POST['zanox-title'] ) );
			$newoptions['id'] = strip_tags( stripslashes( $_POST['zanox-id'] ) );
			$newoptions['targetType'] = strip_tags( stripslashes( $_POST['zanox-targetType'] ) );
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_zanox', $options);
		}
		$title = attribute_escape( $options['title'] );
		$id = attribute_escape( $options['id'] );
		$targetType = attribute_escape( $options['targetType'] );
	?>
				<p><label for="zanox-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="zanox-title" name="zanox-title" type="text" value="<?php echo $title; ?>" /></label></p>
				<p><label for="zanox-id">ID: <input stlye="width: 250px;" id="zanox-id" name="zanox-id" type="text" value="<?php echo $id; ?>" /></label></p>
				<p><label for="zanox-targetType">Target type:
					<select name="zanox-targetType" id="zanox-targetType">
	<?php foreach( $targetTypes as $target): ?> 
						<option value="<?php echo $target?>"<?php selected( $options['targetType'], $target ); ?>><?php echo ucfirst( $target) ?></option>
	<?php endforeach; ?>
					</select></label></p>
				<input type="hidden" id="zanox-submit" name="zanox-submit" value="1" />
	<?php
	}
	
	register_sidebar_widget( 'zanox', 'widget_zanox' );
	register_widget_control( 'zanox', 'widget_zanox_control' );
}

add_action('plugins_loaded', 'widget_zanox_init');