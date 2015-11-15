<?php
	set_time_limit(1000);
  //read osm
  $doc_read = new DOMDocument(); 
  $doc_read->load( 'map.osm' ); 
  $doc_write = new DOMDocument();
  $doc_write->formatOutput = true;
  $result = array();
  $result_map = array();
   
$nodes = $doc_read->getElementsByTagName( "node" ); 
$nodes_array = array();
foreach($nodes as $node)
{
	$nodes_array[$node->getAttribute("id")] = $node;
}
$ways = $doc_read->getElementsByTagName("way");

$root = $doc_write->createElement("graph");
$doc_write->appendChild($root);
$edge_count = 0;
$index_result =0;
foreach ($ways as $way)
{
	$nds = $way->getElementsByTagName("nd");
	$tags = $way->getElementsByTagName("tag");
	$flag = 0;
	$index = 0;
	$prev_node = 0;
	$current_node = 0;
	
	foreach($tags as $tag)
	{
		$k = $tag->getAttribute("k");
		$v = $tag->getAttribute("v");
		if($v=="residential" || $v=="park" || $k=="building" || $v=="track" || $k=="landuse" || $v=="water"){
			$flag = 1;
			break;
		}
	}
	
	if($flag == 1){
		continue;
	}
	
	foreach($nds as $nd)
	{
		$current_node_id = $nd->getAttribute("ref");
		if($index==0) $prev_node_id = $current_node_id;
		
		$prev_node = $nodes_array[$prev_node_id];
		$current_node = $nodes_array[$current_node_id]; 
			
		$prev_lon = $prev_node->getAttribute("lon");
		$prev_lat = $prev_node->getAttribute("lat");
	
		$current_lon = $current_node->getAttribute("lon");
		$current_lat = $current_node->getAttribute("lat");
	
		//echo $prev_lon."and".$prev_lat;
		//echo "Connected to ";
		//echo $current_lon."and".$current_lat;
		
		//now we have to determine the euclidean distance between the prev and current nodes
		$square_lon = ($current_lon-$prev_lon) * ($current_lon-$prev_lon);
		$square_lat = ($current_lat - $prev_lat) * ($current_lat - $prev_lat);
		
		$distance = sqrt($square_lon + $square_lat);
		//$distance_rounded = sprintf ("%.2f", $distance);
		
		if(array_key_exists($current_node_id,$result_map)){
			//new
			// create child element
			if($result_map[$current_node_id]==1)//||$result_map[$current_node_id]==2)
				$result_map[$current_node_id]++;
			else //if($result_map[$current_node_id]>2)
			{
				$item = $doc_write->createElement("item");
				$root->appendChild($item);
							
				// create attribute node
				$current_id_xml = $doc_write->createAttribute("id");
				$item->appendChild($current_id_xml);
				
				// create attribute value node
				$current_id_xml_text = $doc_write->createTextNode($current_node_id);
				$current_id_xml->appendChild($current_id_xml_text);
					
				// create attribute node
				$current_lon_xml = $doc_write->createAttribute("lon");
				$item->appendChild($current_lon_xml);
				
				// create attribute value node
				$current_lon_xml_text = $doc_write->createTextNode($current_lon);
				$current_lon_xml->appendChild($current_lon_xml_text);
				
				// create attribute node
				$current_lat_xml = $doc_write->createAttribute("lat");
				$item->appendChild($current_lat_xml);
				
				// create attribute value node
				$current_lat_xml_text = $doc_write->createTextNode($current_lat);
				$current_lat_xml->appendChild($current_lat_xml_text);
				//new end
			}
		}
		else $result_map[$current_node_id] = 1;
		
		
		
		$prev_node_id = $current_node_id;
		$index++;
		//NEW$node = $doc_read->getElementsByAttribute("id",$nd->getAttribute("ref"));
		//var_dump($nodes->getElementById($node_id));
		//var_dump($node);
	}
} 

  echo $doc_write->saveXML();
  $doc_write->save("common.xml");
  
  
?>