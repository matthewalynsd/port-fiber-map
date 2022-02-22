	/**
	 * Leaflet Fiber Map Shortcode - Matthew Davis, Northwest Media. Live map can be seen here: https://portoflewiston.com/our-business/fiber-optic-network/
	 */
	static function nwm_fibermap($atts) {
	
		$atts = shortcode_atts( array(
			'center' => '46.403972, -117.007808',
			'zoom' => '13',
			'height' => '820px',
		), $atts );

		ob_start();
		?>
		<div id="fibermap"></div>
		
		<style>
            #fibermap {
                height: <?php echo $atts['height']; ?>;
                z-index: 98;
            }
        </style>
        
		<script>
		    $( document ).ready(function() {
                var fibermap = L.map('fibermap').setView([<?php echo $atts['center']; ?>], <?php echo $atts['zoom']; ?>);
                
                L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    				attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    				maxZoom: 18,
    				tileSize: 512,
				    zoomOffset: -1,
    				id: 'mapbox/outdoors-v11',
    				accessToken: 'pk.eyJ1IjoibWF0dGhld2FseW5kIiwiYSI6ImNqdnNvcWQ3cDM4MWY0M3FvdGc1YnF2OXAifQ.1GIr-xDXI-8SPEuZMVB_ug'
    			}).addTo(fibermap);
				
				function mapStyle(feature) {
					return {
						color: '#68BD45',
						dashArray: '',
						fillOpacity: 0.4,
						weight: 3
					};
				}
				//Variable to hold path to Cron JSON
				var fiberJsonPath = "/wp-content/themes/bb-child-theme-deluxe/kml/backbone_data.json";
				
				//Function to pull the JSON into JS for use as a variable
				var fiberJson = (function () {
					var json = null;
					$.ajax({
						'async': false,
						'global': false,
						'url': fiberJsonPath,
						'dataType': "json",
						'success': function (data) {
							json = data;
						}
					});
					return json;
				})(); 
				
				//Properly wrapped GeoJSON with FeatureCollection
				var properArray = {"type": "FeatureCollection", features: fiberJson["result"]};
				
				//Properly styled and added to the map GeoJSON
				var finalData = L.geoJSON(properArray, {
				  onEachFeature: function (feature, layer) {
					if (layer instanceof L.Polyline) {
					  layer.setStyle({
						'color': 'red'
					  });
					}
				  }
				}).addTo(fibermap);
					
		    });
        </script>
        
		<?php

		return ob_get_clean();

	}
