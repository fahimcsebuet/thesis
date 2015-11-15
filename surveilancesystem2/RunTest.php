<?php

/*
 * Author: milu
 */
set_time_limit(1000);

//$source_id = $_GET["source"];
//$destination_id = $_GET["destination"];


// $s_lat=23.7165369;  //1778185456
// $s_lon=90.3944054;
// $d_lat=23.7184739; //1778185464
// $d_lon=90.3939061;


$s_lat=$_GET["s_lat"];
$s_lon=$_GET["s_lon"];
$d_lat=$_GET["d_lat"];
$d_lon=$_GET["d_lon"];

require("Dijkstra.php");

function runTest($s_lat,$s_lon,$d_lat,$d_lon) {
	//read xml
	$result_array = array();
	$result_array[0] = array();//path
	$result_array[1] = array();//lon
	$result_array[2] = array();//lat
	$doc_read = new DOMDocument(); 
	$doc_read->load( 'mapgraph_new.xml' ); 
	$items = $doc_read->getElementsByTagName("item");
	$g = new Graph();
	
	$slon=100;
	$slat=100;
	$dlon=100;
	$dlat=100;
	//LOOP
	foreach ($items as $item)
	{
		$prev_id = $item->getAttribute("prev_id");
		$prev_lon = $item->getAttribute("prev_lon");
		$result_array[1][$prev_id] = $prev_lon;
		$prev_lat = $item->getAttribute("prev_lat");
		$result_array[2][$prev_id] = $prev_lat;
		
		$current_id = $item->getAttribute("current_id");
		$current_lon = $item->getAttribute("current_lon");
		$result_array[1][$current_id] = $current_lon;
		$current_lat = $item->getAttribute("current_lat");
		$result_array[2][$current_id] = $current_lat;
		
		$distance = $item->getAttribute("distance");
		
		$g->addedge($prev_id, $current_id, $distance);
		$g->addedge($current_id, $prev_id, $distance);
		
		
		//now we have to determine the euclidean distance between the prev and current nodes
		$square_lon = ($current_lon-$s_lon) * ($current_lon-$s_lon);
		$square_lat = ($current_lat - $s_lat) * ($current_lat - $s_lat);
		
		$sd = sqrt($square_lon + $square_lat);
		//$distance_rounded = sprintf ("%.2f", $distance);
		
		if($sd<$slon)
		{
		        $slon = $sd;
				$source_id = $current_id ;
		
		}
		
		//now we have to determine the euclidean distance between the prev and current nodes
		$square_lon = ($current_lon-$d_lon) * ($current_lon-$d_lon);
		$square_lat = ($current_lat - $d_lat) * ($current_lat - $d_lat);
		
		$dd = sqrt($square_lon + $square_lat);
		//$distance_rounded = sprintf ("%.2f", $distance);
		
		if($dd<$dlon)
		{
		        $dlon = $dd;
				$destination_id = $current_id ;
		
		}
		
		
		
		/*
		if(abs($prev_lon-$s_lon)<$slon)
		{
		    if(abs($prev_lat-$s_lat)<$slat)
			{
			    $slon = abs($prev_lon-$s_lon);
				$slat = abs($prev_lat-$s_lat);
				$source_id = $prev_id ;
			
			}
		}
		
		if(abs($current_lon-$s_lon)<$slon)
		{
		    if(abs($current_lat-$s_lat)<$slat)
			{
			    $slon = abs($current_lon-$s_lon);
				$slat = abs($current_lat-$s_lat);
				$source_id = $current_id ;
			
			}
		}
		
		if(abs($prev_lon-$d_lon)<$dlon)
		{
		    if(abs($prev_lat-$d_lat)<$dlat)
			{
			    $dlon = abs($prev_lon-$d_lon);
				$dlat = abs($prev_lat-$d_lat);
				$destination_id = $prev_id;
			
			}
		}
		
		if(abs($current_lon-$d_lon)<$dlon)
		{
		    if(abs($current_lat-$d_lat)<$dlat)
			{
			    $dlon = abs($current_lon-$d_lon);
				$dlat = abs($current_lat-$d_lat);
				$destination_id = $current_id;
			
			}
		}*/
	}
	//LOOP

	list($distances, $prev) = $g->paths_from($source_id);
	
	$path = $g->paths_to($prev, $destination_id);
	
	$result_array[0] = $path;
	
	if(count($path)==0){
		 print_r("error");
		 die();
	}
		
	print_r(json_encode($result_array));
	
}


runTest($s_lat,$s_lon,$d_lat,$d_lon);

?>