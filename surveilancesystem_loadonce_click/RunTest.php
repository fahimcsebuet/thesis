<?php
//error_reporting(-1);
set_time_limit(1000);
session_start();
require("Dijkstra.php");

//$source_id = "1778185456";//$_GET["source_id"];
//$destination_id = "1778185464";//$_GET["destination_id"];
$graph = unserialize($_SESSION["graph"]);
$lonlat = unserialize($_SESSION["lonlat"]);
 
$s_lat="23.7249233";  //1778185456
$s_lon="90.4119726";
$d_lat="23.7253504"; //1778185464
$d_lon="90.4107634";

//var_dump($lonlat);
//$s_lat=$_GET["s_lat"];
//$s_lon=$_GET["s_lon"];
//$d_lat=$_GET["d_lat"];
//$d_lon=$_GET["d_lon"];

function find_nearest_node($s_lon,$s_lat,$d_lon,$d_lat,$lonlat)
{
   $slon=100;
   $slat=100;
   $dlon=100;
   $dlat=100;
   	
   foreach($lonlat[0] as $id=>$lon)
   {
	//now we have to determine the euclidean distance between the prev and current nodes
	$square_lon = ($lon-$s_lon) * ($lon-$s_lon);
	$square_lat = ($lonlat[1][$id] - $s_lat) * ($lonlat[1][$id] - $s_lat);
	
	$sd = $square_lon + $square_lat;
	//$distance_rounded = sprintf ("%.2f", $distance);
	
	if($sd<$slon)
	{
	        $slon = $sd;
		$source_id = $id ;
	
	}
	
	//now we have to determine the euclidean distance between the prev and current nodes
	$square_lon = ($lon-$d_lon) * ($lon-$d_lon);
	$square_lat = ($lonlat[1][$id] - $d_lat) * ($lonlat[1][$id] - $d_lat);
	
	$dd = $square_lon + $square_lat;
	//$distance_rounded = sprintf ("%.2f", $distance);
	
	if($dd<$dlon)
	{
	        $dlon = $dd;
		$destination_id = $id ;
	
	}


       var_dump($id);
       var_dump($lon);
   }

   die();
    //var_dump($id);
   //$source_id="1778185456";
   //$destination_id="1778185464";
   
   $clicked_node = array(); 
   $clicked_node[0] = $source_id;
   $clicked_node[1] = $destination_id;

   return $clicked_node;
}


function runTest($s_lon,$s_lat,$d_lon,$d_lat,$graph,$lonlat) {
	//read xml
	$result_array = array();
	$result_array[0] = array();//path
	$result_array[1] = $lonlat[0];//lon
	$result_array[2] = $lonlat[1];//lat
	$g = new Graph();
	$g = $graph;
        //var_dump($graph);
	$slon=100;
	$slat=100;
	$dlon=100;
	$dlat=100;
	$clicked_node = array();
	
        ///finding source_id and destination_id
        $clicked_node = find_nearest_node($s_lon,$s_lat,$d_lon,$d_lat,$lonlat);
	        

        $source_id = $clicked_node[0];
        $destination_id = $clicked_node[1];
        //echo "starting";
	list($distances, $prev) = $g->paths_from($source_id);
	
	$path = $g->paths_to($prev, $destination_id);
	
	//echo "ended";
	
	$result_array[0] = $path;
	
	if(count($path)==0){
		 print_r("error");
		 die();
	}
	
        //echo "paths $path";
	//var_dump($path);
	print_r(json_encode($result_array));
	
}


runTest($s_lon,$s_lat,$d_lon,$d_lat,$graph,$lonlat);

?>
