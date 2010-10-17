<?php
/*
 * This file is part of CakePHP Gmap Plugin.
 *
 * CakePHP Gmap Plugin
 * Copyright (c) 2010, Miljenko Barbir (http://miljenkobarbir.com)
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
*/ 

class GmapHelper extends AppHelper
{
	/*
	 * Returns a script which contains the google map code.
	 */
	function map($id, $settings = array())
	{
		// center location on the map
		$location = '0, 0';
		if(isset($settings['location']))
		{
			$location = $settings['location']['lat'] . ',' . $settings['location']['lng'];
		}

		// type of the map
		// ---------------
		// HYBRID  		This map type displays a transparent layer of major streets on satellite images.
		// ROADMAP 		This map type displays a normal street map.
		// SATELLITE 	This map type displays satellite images.
		// TERRAIN 		This map type displays maps with physical features such as terrain and vegetation.
		$type = $this->__extractSetting($settings, 'type', $defaultValue = 'ROADMAP');

		// zoom level
		$zoom = $this->__extractSetting($settings, 'zoom', $defaultValue = '10');

		$markers = '';
		if(isset($settings['markers']))
		{
			$index = 0;
			foreach($settings['markers'] as $marker)
			{
				$markers .= "
					var marker_" . $index . "_location = new google.maps.LatLng(" . $marker['lat'] . ',' . $marker['lng'] . ");
					var marker_" . $index . " = new google.maps.Marker
					(
						{
							position: marker_" . $index . "_location, 
							map: " . $id . "_map
						}
					);
				";
				$index++;
			}
		}		

		// map initialization logic
		$script = "
			<div id=\"$id\"></div>
			<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=false\"></script>
			<script type=\"text/javascript\">
				function " . $id . "_Initialize()
				{
					var location = new google.maps.LatLng($location);
					var options =
					{
						zoom: $zoom,
						center: location,
						mapTypeId: google.maps.MapTypeId.$type
					};

					var " . $id . "_map = new google.maps.Map(document.getElementById(\"$id\"), options);

					$markers
				}

				" . $id . "_Initialize();
			</script>
		";

		return $script;
	}

	/*
	 * Extracts a setting under the provided key if possible, otherwise, returns a provided default value.
	 */
	function __extractSetting($settings, $key, $defaultValue = '')
	{
		if(!$settings && empty($settings))
			return $defaultValue;

		if(isset($settings[$key]))
			return $settings[$key];
		else
			return $defaultValue;
	}
}

?>