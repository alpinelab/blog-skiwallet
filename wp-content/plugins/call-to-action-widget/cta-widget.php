<?php
/*
Plugin Name: CTA Widget
Plugin URI: http://www.boomvisibility.com/
Description: A text widget with an image or icon and a call to action button.
Version: 1.1
Author: Charlie Strickler
Author URI: http://boomvisibility.com
License: GPL
*/
/* This calls widget code when wordpress initializes.*/
/* Note that the hello_world doesnt have brackets. */
class RT_Widget_Text extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_cta', 'description' => __('Add a Call to action box'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('ctatext', __('Call To Action'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$title1 = apply_filters( 'widget_title1', empty( $instance['title1'] ) ? '' : $instance['title1'], $instance, $this->id_base );
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$buttontext = apply_filters( 'widget_buttontext', empty( $instance['buttontext'] ) ? '' : $instance['buttontext'], $instance );
		$buttonurl = apply_filters( 'widget_buttonurl', empty( $instance['buttonurl'] ) ? '' : $instance['buttonurl'], $instance );		
		$imageurl = apply_filters( 'widget_imageurl', empty( $instance['imageurl'] ) ? '' : $instance['imageurl'], $instance );
		$imageplace = apply_filters( 'widget_imageplace', empty( $instance['imageplace'] ) ? '' : $instance['imageplace'], $instance );		
		echo $before_widget; ?>						
		<?php if ( $imageplace != "below" && !empty( $imageurl ) ) : ?>
		<img src="<?php echo $imageurl; ?>" alt="<?php echo $title; ?>"/>		<?php endif; ?>
		<?php if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
		<?php if ( !empty( $title1 ) ) { echo "<h4>" . $title1 . "</h4>"; } ?>		
		<?php if ( $imageplace == "below" && !empty( $imageurl ))  : ?>		
			<img src="<?php echo $imageurl; ?>" alt="<?php echo $title; ?>"/>		
		<?php  endif; ?>		
			<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
			<div class="ctabutton"><a href="<?php echo $buttonurl; ?>"><?php echo $buttontext; ?></a></div>			
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
			$instance['title1'] = $new_instance['title1'];
			$instance['imageurl'] =  $new_instance['imageurl'];
			$instance['buttonurl'] =  $new_instance['buttonurl'];
			$instance['buttontext'] =  $new_instance['buttontext'];									$instance['imageplace'] =  $new_instance['imageplace'];			
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed	
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'imageurl' => '', 'title' => '', 'text' => '', 'buttontext' => '', 'buttonurl' => '', 'imageplace' => ''  ) );
		$title = strip_tags($instance['title']);
		$title1 = strip_tags($instance['title1']);
		$text = esc_textarea($instance['text']);
		$buttontext = esc_textarea($instance['buttontext']);
		$imageurl = esc_textarea($instance['imageurl']);		
		$buttonurl = esc_textarea($instance['buttonurl']);						$imageplace = esc_textarea($instance['imageplace']);				
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Sub-Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" type="text" value="<?php echo esc_attr($title1); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('imageurl'); ?>"><?php _e('Image URL:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('imageurl'); ?>" name="<?php echo $this->get_field_name('imageurl'); ?>" type="text" value="<?php echo esc_attr($imageurl); ?>" /></p>				<p>Image Placement: &nbsp;&nbsp;		<input type="radio" value="above" id="imgabove" name="<?php echo $this->get_field_name('imageplace'); ?>" <?php if ( $imageplace != "below" ) echo 'checked'; ?>/>		<label for="imgabove">Above title</label>		&nbsp;&nbsp;		<input type="radio" value="below" id="imgbelow" name="<?php echo $this->get_field_name('imageplace'); ?>" <?php if ( $imageplace == "below" ) echo 'checked'; ?>/>		<label for="imgbelow">Below Title</label>				</p>
		
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		
		<p><label for="<?php echo $this->get_field_id('buttontext'); ?>"><?php _e('Button Text:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('buttontext'); ?>" name="<?php echo $this->get_field_name('buttontext'); ?>" type="text" value="<?php echo esc_attr($buttontext); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('buttonurl'); ?>"><?php _e('Button URL:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('buttonurl'); ?>" name="<?php echo $this->get_field_name('buttonurl'); ?>" type="text" value="<?php echo esc_attr($buttonurl); ?>" /></p>

		<?php
		} 
		}


/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */

add_action( 'widgets_init', create_function( '', 'register_widget( "RT_Widget_Text" );' ) );


?>