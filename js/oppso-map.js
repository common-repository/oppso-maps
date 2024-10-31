function oppso_create_map(oppso_map_id){

	var address = jQuery('#address_'+oppso_map_id).val();
	var zoom = parseInt(jQuery('#zoom_'+oppso_map_id).val());
	var map_type_selected = jQuery('#map_type_'+oppso_map_id).val();
	var bubble_text = jQuery('#bubble_'+oppso_map_id).val();
	var lat = jQuery('#lat_'+oppso_map_id).val();
	var lng = jQuery('#lon_'+oppso_map_id).val();
	
	map_type=google.maps.MapTypeId.HYBRID;
        var latlng = new google.maps.LatLng(lat, lng);
      
        if(map_type_selected == 'HYBRID' ){
        map_type=google.maps.MapTypeId.HYBRID;
        }
        if(map_type_selected == 'SATELLITE' ){
            map_type=google.maps.MapTypeId.SATELLITE;
        }
        if(map_type_selected == 'ROADMAP' ){
            map_type=google.maps.MapTypeId.ROADMAP;
        }
        if(map_type_selected == 'TERRAIN' ){
            map_type=google.maps.MapTypeId.TERRAIN;
        }
       
        var mapOptions = {
        	      zoom: zoom,
        	      center: latlng,
        	      mapTypeId: map_type
        	    }
        	   var map = new google.maps.Map(document.getElementById('oppso_map_'+oppso_map_id), mapOptions);
   	var geocoder = new google.maps.Geocoder();
   	var infowindow = new google.maps.InfoWindow({});
   	infowindow.setContent(bubble_text);

  
    var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        visible:true,
        title: "Test", mapTypeId: map_type
    });
    google.maps.event.addListener(marker, 'click', function() {
    	
    	infowindow.open(map,marker);
    });
    infowindow.open(map,marker);	
}
/** this converts **/
function oppso_geocode(){
 	var geocoder1 = new google.maps.Geocoder();
 	address = jQuery('#oppso_map_address').val();
    geocoder1.geocode( { 'address': address}, function(results, status) {
	      if (status == google.maps.GeocoderStatus.OK) {
	    
	      var lat = (results[0].geometry.location.lat());
	      var lon = (results[0].geometry.location.lng());
	      		jQuery('#oppso_map_lat').val(lat);
	      		jQuery('#oppso_map_lon').val(lon); 
	      }
    });
}
function oppso_map_id(){
	
}
jQuery(document).ready(

		jQuery("#oppso-preview-map").live("click",
				
		function() {
			oppso_geocode();
			var data = {
					action: 'oppso_map_ids'
				};

			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#oppso_map_id').val(response);
						
			});
			oppso_map_address = jQuery('#oppso_map_address').val();
			
			oppso_map_lat = jQuery('#oppso_map_lat').val();
			oppso_map_lon = jQuery('#oppso_map_lon').val();
			oppso_map_width = jQuery('#oppso_map_width').val();
			oppso_map_width_type = jQuery('#oppso_map_width_type').val();
			oppso_map_height = jQuery('#oppso_map_height').val();
			oppso_map_type = jQuery('#oppso_map_type').val();
			oppso_map_zoom = jQuery('#oppso_map_zoom').val();
			oppso_map_id = jQuery('#oppso_map_id').val();
			oppso_marker_text = jQuery('#oppso_marker_text').val();
			if(oppso_map_width_type=='%'){
			
				jQuery('#previewer').css('width','100%');
			}
			shortcode = '[oppso-map map_id="'+oppso_map_id+'" map_type="'+oppso_map_type+'"  bubble="'+oppso_marker_text+'" height="'+oppso_map_height+'px" width="'+oppso_map_width+oppso_map_width_type+'" zoom="'+oppso_map_zoom+'" lat="'+oppso_map_lat+'" lon="'+oppso_map_lon+'"]';
				
		jQuery('#shortcode').val(shortcode);
		shortcode = jQuery('#shortcode').val();
			var data = {

					action: 'oppso_map_preview',
					shortcode: shortcode
				
				};



			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#previewer').html(response);
			//	oppso_create_map("124efa5b97f2");
			});
			

			
		})

);