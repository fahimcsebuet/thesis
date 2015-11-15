<html>
    <head>
        <title>Map</title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="jquery.svg.package/jquery.svg.js"></script>
		<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
		<script src="json2.js"></script>
    </head>
    <body>
        
        <div id="ourMap"></div>
        <script>
			map = new OpenLayers.Map("ourMap");
			 map.addLayer(new OpenLayers.Layer.OSM());
			 var markers = new OpenLayers.Layer.Markers( "Markers" );
	         map.addLayer(markers);	 
			 
			 var con=0;
			//read the osm file
			$(function() {
		             $.ajax({
		                 type: "GET",
		                 url: "police_box.xml",
		                 dataType: "xml",
		                 success: function(xml) {
						 	$(xml).find('item').each(function(){
		                         var id_text = $(this).attr('id');
								 var lat_text = $(this).attr('lat');
		                         var lon_text = $(this).attr('lon'); 
								 var lonLat = new OpenLayers.LonLat( lon_text ,lat_text ).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
								 markers.addMarker(new OpenLayers.Marker(lonLat));
								 
								 con++;
								 //if(con>2) return false;
									 
		                     }); 
							 
							 
		                 }
		             }); //close $.ajax(
		     }); //close $(	
			//read the osm file end
//marker drawing ends
      
			var ourpoint1 = new OpenLayers.LonLat(90.3989,23.7937);
			ourpoint1.transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
			map.setCenter(ourpoint1,12);
			


            
            			
			 
			 
        </script>
		
		
		
    </body>

</html>

<!-- TASKS
	1. how to get the local values from xml
	2. read the path from way
-->
