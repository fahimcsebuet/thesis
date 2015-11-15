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
			//read the osm file
			var id_points = new Array();
			var lat_points = new Array();
			var lon_points = new Array();
			var map_points = new Array();
			var lonlat;
			var index=0;
			var timer_start=0;
			var count=0;
			var s_lat;
			var s_lon;
			var d_lat;
			var d_lon;
			var select_source=1;
			var select_dest=0;
			
			var vector;

		    var counter=setInterval(timer, 1000); //1000 will  run it every 1 second
			function timer()
			{
				if(timer_start==1)
				{
				    count=count+1;

					  //Do code for showing the number of seconds here
					document.getElementById("timer").innerHTML= "Dijkstra running " + count + " secs";
				}
			}
			$(function() {
						$("#dijkstra").click(function(){
						    
							
							timer_start=1;
							
							var source_id = $("#source").val();
							var destination_id = $("#destination").val();
							
							$.ajax({
						      method: 'get',
						      url: 'RunTest.php',
						      data: {
								's_lat': s_lat,
								's_lon': s_lon,
								'd_lat': d_lat,
								'd_lon': d_lon,
						        'ajax': true
						      },
						      success: function(data_json) {
							  	if(data_json=="error"){
									alert("No path exists");
								}
								else{
							  	 var data = new Array();
						       	 //data[0] = $path, $data[1] = $lon, $data[1] = $lat;
								 data = JSON.parse(data_json);
								 var i=0;
								 var length = data[0].length;
								 for(;i<length;i++)
								 {
								  		map_points[i] = new OpenLayers.Geometry.Point(data[1][data[0][i]],data[2][data[0][i]]);
										/*if(i==0||i==length-1)
										{
											var lonLat = new OpenLayers.LonLat( data[1][data[0][i]] ,data[2][data[0][i]] ).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
											markers.addMarker(new OpenLayers.Marker(lonLat));
										}*/
										
								 }
								 
								 var style = {
										strokeColor: '#0000ff',
										strokeOpacity: 0.8,
										strokeWidth: 4
								};
								 
								 vector = new OpenLayers.Layer.Vector();
							 	 var featureVector = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(map_points).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")),null,style);
							 	 vector.addFeatures([featureVector]);
							 	 map.addLayers([vector]);
								 
								 timer_start=0;
						      	}
							  }
						    });
						});
		             /*NEW$.ajax({
		                 type: "GET",
		                 url: "map.osm",
		                 dataType: "xml",
		                 success: function(xml) {
						 	$(xml).find('node').each(function(){
		                         var id_text = $(this).attr('id');
								 var lat_text = $(this).attr('lat');
		                         var lon_text = $(this).attr('lon');
								 
								 id_points[id_text] = index;
								 lat_points[index] = lat_text;
								 lon_points[index] = lon_text;
								 
								 
								 
								 index++;			 
		                     }); //close each(
							 index = 0;
							 $(xml).find('way').each(function(){
		                         var nd = $(this).find('nd');
								 var ref = nd.attr('ref');
								 map_points[index] = new OpenLayers.Geometry.Point(lon_points[id_points[ref]],lat_points[id_points[ref]]);
								 index++;
											 
		                     }); //close each(
							 
							 //here we've got the results do it here
							 var vector = new OpenLayers.Layer.Vector();
							 var featureVector = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(map_points).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")));
							 vector.addFeatures([featureVector]);
							 map.addLayers([vector]);
							 
		                 }
		             }); //close $.ajax(*/
		     }); //close $(	
			//read the osm file end
//marker drawing ends
      
			ourpoint1 = new OpenLayers.LonLat(90.3989,23.7937);
			ourpoint1.transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
			map.setCenter(ourpoint1,12);
			
/// get position from click
			 OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
                defaultHandlerOptions: {
                    'single': true,
                    'double': false,
                    'pixelTolerance': 0,
                    'stopSingle': false,
                    'stopDouble': false
                },

                initialize: function(options) {
                    this.handlerOptions = OpenLayers.Util.extend(
                        {}, this.defaultHandlerOptions
                    );
                    OpenLayers.Control.prototype.initialize.apply(
                        this, arguments
                    ); 
                    this.handler = new OpenLayers.Handler.Click(
                        this, {
                            'click': this.trigger
                        }, this.handlerOptions
                    );
                }, 

                trigger: function(e) {
                    lonlat = map.getLonLatFromPixel(e.xy);
					lonlat.transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326"));
					//var lonlat = map.getLonLatFromViewPortPx(e.xy);
                  /*alert("You clicked near " + lonlat.lat + " N, " +
                                              + lonlat.lon + " E");*/
					
                    if(select_source==1)
   					{
							s_lat = lonlat.lat;
							s_lon = lonlat.lon;
							
						 var ourpoint1 = new OpenLayers.LonLat(s_lon,s_lat)
                         ourpoint1.transform(new OpenLayers.Projection("EPSG:4326" ), map.getProjectionObject());
						 
						 markers.addMarker(new OpenLayers.Marker(ourpoint1));
						 
						 select_dest=1;
						 select_source=0;
						 
						 // alert("You clicked near " + s_lat + " N, " +
                                              // + s_lon + " E");
							
					}
					
					else if(select_dest==1)
					{
							d_lat = lonlat.lat;
							d_lon = lonlat.lon;
							
						var ourpoint2 = new OpenLayers.LonLat(d_lon,d_lat)
                         ourpoint2.transform(new OpenLayers.Projection("EPSG:4326" ), map.getProjectionObject());
						 
						 markers.addMarker(new OpenLayers.Marker(ourpoint2));
						 
						 // alert("You clicked near " + d_lat + " N, " +
                                              // + d_lon+ " E");
						 
						 select_dest=0;
						 //select_source=1;
					}
					else
					{
					    select_dest=0;
						select_source=1;
						markers.clearMarkers();
					
					
					}
                }

            });
			
		    var click = new OpenLayers.Control.Click();
            map.addControl(click);
            click.activate();

            
            			
			 
			 
        </script>
		<button type="button" id="dijkstra" name="dijkstra">Dijkstra</button>
		<label id="timer">Dijkstra not started<label/>
		
		
		
    </body>

</html>

<!-- TASKS
	1. how to get the local values from xml
	2. read the path from way
-->
