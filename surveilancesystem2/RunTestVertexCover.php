<?php

/*
 * Author: milu
 */
set_time_limit(-1);



require("Dijkstra.php");

function runTestVertexCover() {
	//read xml
	$doc_write = new DOMDocument();
  	$doc_write->formatOutput = true;
  	$root = $doc_write->createElement("graph");
	$doc_write->appendChild($root);
	$doc_read = new DOMDocument(); 
	$doc_read->load( 'mapgraph_vertexcover_new.xml' ); 
	$items = $doc_read->getElementsByTagName("item");
	$g = new Graph();
	$array_vertex_cover = array();
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
	}
	//LOOP
	$doc_read_common = new DOMDocument(); 
	$doc_read_common->load( 'common.xml' ); 
	$items_common = $doc_read_common->getElementsByTagName("item");
	$index=0;
	foreach($items_common as $item)
	{
		$array_vertex_cover[$index] = $item->getAttribute("id");
		$index++;
	}
	//Loop
	$index1=0;
	$result = array();
	for(;$index1<count($array_vertex_cover);$index1++)
	{
		for($index2=$index1+1;$index2<count($array_vertex_cover);$index2++)
		{
			list($distances, $prev) = $g->paths_from($array_vertex_cover[$index1]);
			$path = $g->paths_to($prev, $array_vertex_cover[$index2]);
			$result = array_intersect($path, $array_vertex_cover);	
			if(count($result)==2) {
				// create child element
				$item = $doc_write->createElement("edge");
				$root->appendChild($item);
				
				// create attribute node
				$prev_id_xml = $doc_write->createAttribute("id1");
				$item->appendChild($prev_id_xml);
				
				// create attribute value node
				$prev_id_xml_text = $doc_write->createTextNode($array_vertex_cover[$index1]);
				$prev_id_xml->appendChild($prev_id_xml_text);
				
				// create attribute node
				$current_id_xml = $doc_write->createAttribute("id2");
				$item->appendChild($current_id_xml);
				
				// create attribute value node
				$current_id_xml_text = $doc_write->createTextNode($array_vertex_cover[$index2]);
				$current_id_xml->appendChild($current_id_xml_text);
			}		
		}
	}
	//LoopEnd
	echo $doc_write->saveXML();
  	$doc_write->save("common_edgelist.xml");
}


runTestVertexCover();

?>