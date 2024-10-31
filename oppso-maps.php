<?php/*Plugin Name: Oppso MapsPlugin URI: http://www.oppso.com/oppso-maps-plugin-for-wordpress/Description: Add a Google Map to your wordpress site! Oppso Maps creates a map shortcode to use in posts, pages or text widgets.Version: 1.0
Author: Dan BusuiocAuthor URI: http://www.oppso.com/
*/

function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=oppso-maps/oppso-maps.php">Shortcode</a>';
  	array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );

add_action('admin_menu','oppso_maps_add_settings_menu');

add_filter('widget_text', 'do_shortcode');

add_shortcode('oppso-map', 'oppso_maps_shortcode');

add_action('wp_enqueue_scripts', 'oppso_map_scripts');

add_action('admin_init', 'oppso_map_scripts');

function oppso_map_scripts(){

	
	wp_enqueue_script(
	
	'google-map',
	'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false',array('jquery')
	
	);
	wp_register_style( 'oppso-maps-style', plugins_url('css/oppso-map.css', __FILE__) );

	wp_enqueue_style( 'oppso-maps-style' );

	wp_enqueue_script(
	
	'oppso-map',
	
	plugins_url('/js/oppso-map.js', __FILE__),array('jquery')
	
	);

}

function oppso_maps_add_settings_menu(){

	add_options_page('Oppso Maps', 'Oppso Maps', 'administrator', __FILE__, 'oppso_maps_options');

}

function oppso_maps_options(){
	?>

<div style="width:100%">

 <div style="float:left">
	 <h1>Oppso Maps</h1>
	Very easy to use Google Map creator. Just configure you map, click on Generate & Preview button.
	<br />
	If you like what you see, place the shortcode on a page, post or even in a text widget.

		<table  style="background:#ECECEC; padding:10px;width:600px; border-bottom:1px solid #fff">
			<tr>
				<td><b>Address</b></td>
				<td></td>
				<td><b>Lat</b></td>
				<td><b>Lon</b></td>
			</tr>
			<tr>
				<td><input id="oppso_map_address" onblur='oppso_geocode()' type="text" /></td>
				<td align="right"> or coordinates</td>
				<td><input  id="oppso_map_lat"  type="text" /></td>
				<td><input  id="oppso_map_lon"  type="text" /></td>
			</tr>			
		</table> 
		 
		<table style="background:#ECECEC; padding:10px;;width:600px">
				<tr>
				<td><b>Width</b></td>
				<td><input id="oppso_map_width" type="text" /><select id="oppso_map_width_type"><option>px</option><option>%</option></select></td>
				<td><b>Height</b></td>
				<td><input id="oppso_map_height" type="text" /> px</td>
			</tr>
		
			<tr>
				<td><b>Map type</b></td>
				<td>
					<select id="oppso_map_type">
						<option value="HYBRID">hybrid</option>
						<option value="ROADMAP">roadmap</option>
						<option value="SATELLITE">satellite</option>
						<option value="TERRAIN">terrain</option>
					</select>
				</td>
				
				<td><b>Zoom level</b></td>
				<td>
					<input type="text" value="5"  id="oppso_map_zoom" />
					
				</td>
			</tr>
			
			<tr>
				<td><b>Info window</b></td>
				<td colspan="3">
					<textarea style="width:450px" id="oppso_marker_text"></textarea>
				</td>
			</tr>
		
			<tr><td colspan="4"><input  onclick='oppso_geocode()'  type="button" id="oppso-preview-map" class="button button-primary" value="Generate & Preview" /></td></tr>
			
			
		</table>
		<table style="background:#ECECEC; border-top:1px solid #fff;padding:10px;;width:600px">
		<tr>
				<td><b>Shortcode</b></td>
				<td colspan="3">
				<input type="hidden" id="oppso_map_id" value="<?php echo base_convert(date('Ymdhis'),10,16)?>" />
					<textarea style="width:450px" onclick="this.select()" id="shortcode">[oppso-map map_id=]</textarea>
				</td>
			</tr>
		</table>
</div>

		<div style="float:left;margin-left:20px;margin-top:60px" id="previewer">
		
		</div>
		<br clear="all" />
	</div>
<?php }
function oppso_maps_shortcode($atts){


	extract( shortcode_atts( array(
	'map_id'=>"",
	'address' => "",
	'lat' => "",
	'lon' => "",
	'width' => "",
	'height' => "",
	'map_type' => "",
	'zoom' => "5",
	'bubble' => "",
	




	), $atts ) );
	$div_style='border:1px solid #d4d4d4;';
	if($width!='') $div_style.='width:'.$width.';'; 
	if($height!='') $div_style.='height:'.$height.';'; 
	
	$t='
		
		<div id="oppso_map_'.$map_id.'" class="oppso-simple-map" style="'.$div_style.'"></div>
			<input type="hidden" value="'.($address).'" id="address_'.$map_id.'" />
		<input type="hidden" value="'.($zoom).'" id="zoom_'.$map_id.'" />
			<input type="hidden" value="'.($map_type).'" id="map_type_'.$map_id.'" />
			<input type="hidden" value="'.($bubble).'" id="bubble_'.$map_id.'" />
				<input type="hidden" value="'.($lat).'" id="lat_'.$map_id.'" />
				<input type="hidden" value="'.($lon).'" id="lon_'.$map_id.'" />
	<script>
jQuery(document).ready(
		oppso_create_map("'.$map_id.'"));</script>';
	return ($t);
}

function oppso_map_preview(){
$shortcode = stripslashes($_POST['shortcode']);

 echo (do_shortcode($shortcode));
 die();
}
function oppso_map_ids(){
	echo base_convert(date('Ymdhis'),10,16);
	die();
}
add_action('wp_ajax_oppso_map_preview', 'oppso_map_preview');
add_action('wp_ajax_oppso_map_ids', 'oppso_map_ids');
?>