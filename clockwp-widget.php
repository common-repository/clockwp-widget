<?php
/*
	Plugin Name: Clockwp Widget
	Description: With this simple sidebar widget you can add a flash clock to your wordpress blog, there are many designs to choose from, so you can find a design that suits your blog. 
	Version: 1.5
	Author: Podz

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

function ClockWp_Widget_install () {
	$widgetoptions = get_option('ClockWp_Widget');
	$newoptions['width'] = '160';
	$newoptions['height'] = '160';
	$newoptions['FlashClock'] = '1';
	add_option('ClockWp_Widget', $newoptions);
}

function ClockWp_Widget_init($content){
	if( strpos($content, '[Clocky-Widget]') === false ){
		return $content;
	} else {
		$code = ClockWp_Widget_createflashcode(false);
		$content = str_replace( '[Clocky-Widget]', $code, $content );
		return $content;
	}
}

function ClockWp_Widget_insert(){
	echo ClockWp_Widget_createflashcode(false);
}

function ClockWp_Widget_createflashcode($widget){
	if( $widget != true ){
	} else {
		$options = get_option('ClockWp_Widget');
		$soname = "widget_so";
		$divname = "wpFlash_Clockwidgetcontent";
	}
	if( function_exists('plugins_url') ){ 
		$clocknum = $options['FlashClock'].".swf";
		$movie = plugins_url('clockwp-widget/flash/wp-clock-').$clocknum;
		$path = plugins_url('clockwp-widget/');
	} else {
		$clocknum = $options['FlashClock'].".swf";
		$movie = get_bloginfo('wpurl') . "/wp-content/plugins/clockwp-widget/flash/wp-clock-".$clocknum;
		$path = get_bloginfo('wpurl')."/wp-content/plugins/clockwp-widget/";
	}

	$flashtag = '<script type="text/javascript" src="'.$path.'swfobject.js"></script>';	
	$flashtag .= '<center><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$options['width'].'" height="'.$options['height'].'" id="FlashTime" align="middle">';
	$flashtag .= '<param name="movie" value="'.$movie.'" /><param name="menu" value="false" /><param name="wmode" value="transparent" /><param name="allowscriptaccess" value="always" />';
	$flashtag .= '<!--[if !IE]>--><object type="application/x-shockwave-flash" data="'.$movie.'" width="'.$options['width'].'" height="'.$options['height'].'" align="middle"><param name="menu" value="false" /><param name="wmode" value="transparent" /><param name="allowscriptaccess" value="always" /><!--<![endif]-->';
$flashtag .= '<a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /> </a><!--[if !IE]>--></object><!--<![endif]--></object></center>';
	return $flashtag;
}


function ClockWp_Widget_uninstall () {
	delete_option('ClockWp_Widget');
}


function widget_init_ClockWp_Widget_widget() {
	if (!function_exists('register_sidebar_widget'))
		return;

	function ClockWp_Widget_widget($args){
	    extract($args);
		$options = get_option('ClockWp_Widget');
		$title = empty($options['title']) ? __('ClockWP Widget') : $options['title'];
		?>
	        <?php echo $before_widget; ?>	
				<?php echo $before_title . $title . $after_title;?>
				<?php 
					if( !stristr( $_SERVER['PHP_SELF'], 'widgets.php' ) ){
						echo ClockWp_Widget_createflashcode(true);
					}
				?>
								
	        <?php echo $after_widget; ?>
		<?php
	}
	
	function ClockWp_Widget_widget_control() {
		$movie = get_bloginfo('wpurl') . "/wp-content/plugins/clockwp-widget/flash/wp-clock-";
		$path = get_bloginfo('wpurl')."/wp-content/plugins/clockwp-widget/";
		$options = $newoptions = get_option('ClockWp_Widget');
		if ( $_POST["ClockWp_Widget_submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["ClockWp_Widget_title"]));
			$newoptions['width'] = strip_tags(stripslashes($_POST["ClockWp_Widget_width"]));
			$newoptions['height'] = strip_tags(stripslashes($_POST["ClockWp_Widget_height"]));
			$newoptions['FlashClock'] = strip_tags(stripslashes($_POST["ClockWp_Widget_FlashClock"]));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('ClockWp_Widget', $options);
		}
		$title = attribute_escape($options['title']);
		$width = attribute_escape($options['width']);
		$height = attribute_escape($options['height']);
		$FlashClock = attribute_escape($options['FlashClock']);
		?>
			<p><label for="ClockWp_Widget_title"><?php _e('Title:'); ?> <input class="widefat" id="ClockWp_Widget_title" name="ClockWp_Widget_title" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="ClockWp_Widget_width"><?php _e('Width:'); ?> <input class="widefat" id="ClockWp_Widget_width" name="ClockWp_Widget_width" type="text" value="<?php echo $width; ?>" /></label></p>
			<p><label for="ClockWp_Widget_height"><?php _e('Height:'); ?> <input class="widefat" id="ClockWp_Widget_height" name="ClockWp_Widget_height" type="text" value="<?php echo $height; ?>" /></label></p>
						<p><label for="ClockWp_Widget_FlashClock"><?php _e('Clock:'); ?></label></p>
			<? for ( $i = 1; $i <= 21; $i += 1) { ?>			
				<center>
				<input type="radio" name="ClockWp_Widget_FlashClock" value="<? echo $i ?>" <?php if ($FlashClock == $i) echo 'checked' ?>> 
				<object width="160" height="160" align="middle" id="FlashTime" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="
				<? echo $movie . $i ?>.swf" name="movie"/><param value="false" name="menu"/><param value="transparent" name="wmode"/><param value="always" name="allowscriptaccess"/><!--[if !IE]>--><object width="160" height="160" align="middle" data="<? echo $movie . $i ?>.swf" type="application/x-shockwave-flash"><param value="false" name="menu"/><param value="transparent" name="wmode"/><param value="always" name="allowscriptaccess"/><!--<![endif]--><!--[if !IE]>--></object><!--<![endif]--></object><br/></center>
			<? } ?> 
			
			<input type="hidden" id="ClockWp_Widget_submit" name="ClockWp_Widget_submit" value="1" />
		<?php
	}
	
	register_sidebar_widget( "Clockwp Widget", ClockWp_Widget_widget );
	register_widget_control( "Clockwp Widget", "ClockWp_Widget_widget_control" );
}

add_action('widgets_init', 'widget_init_ClockWp_Widget_widget');
add_filter('the_content','ClockWp_Widget_init');
register_activation_hook( __FILE__, 'ClockWp_Widget_install' );
register_deactivation_hook( __FILE__, 'ClockWp_Widget_uninstall' );
?>
