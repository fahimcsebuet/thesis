<?php

/*
 * Author: milu
 */
set_time_limit(1000);

function runVertexTest() {
	//read xml
	$vertices = array();
        $lons = array();
        $lats = array();
	$vertex_read = new DOMDocument(); 
	$vertex_read->load( 'common.xml' ); 
	$items = $vertex_read->getElementsByTagName("item");

         echo "starting";
        //reading vertices
	$i=0;
	foreach ($items as $item)
	{
		$id = $item->getAttribute("id");
		$lon = $item->getAttribute("lon");
		$lat = $item->getAttribute("lat");
                
		$vertices[$i++] = $id;
		$lons[$id] = $lon;
                $lats[$id] = $lat;
				
	}
        $total_vertices=$i;
        
	echo "ended $i";
       //reading edges

        $edges = array();
	$edge_read = new DOMDocument();
        $edge_read->load('common_edgelist.xml');
	$items = $edge_read->getElementsByTagName("edge");
       
        $i=0;
	foreach ($items as $item)
	{
		$id1 = $item->getAttribute("id1");
		$id2 = $item->getAttribute("id2");
		
                $edges[$i] = array();
                $edges[$i][0] = $id1;
		$edges[$i][1] = $id2;
                $edges[$i][2] = 0;
				
		$i++;
		
             //echo "$i";
           	
	}
        $total_edges=$i;

        
      ///vertex cover algo
     
     $c = array();
     $E = $edges;
     
    $i=-1;
    while(count($E)>0)
    {
       $i++;
       if(!array_key_exists($i,$E)) continue;
       
       $c = array_unique(array_merge($c, array($E[$i][0],$E[$i][1])));
    	$k=0;
        foreach($E as $e)
	{
		if($E[$i][0]==$e[0]&&$E[$i][1]==$e[1]) continue;
		if($E[$i][0]==$e[0]||$E[$i][0]==$e[1]||$E[$i][1]==$e[0]||$E[$i][1]==$e[1])
		{
			unset($e);
		}
	}
	unset($E[$i]);
	

    }
    $doc_write = new DOMDocument();
    $doc_write->formatOutput = true;
    $root = $doc_write->createElement("graph");
    $doc_write->appendChild($root);

    foreach($c as $item_c)
    {
	$item = $doc_write->createElement("item");
	$root->appendChild($item);
				
	// create attribute node
	$current_id_xml = $doc_write->createAttribute("id");
	$item->appendChild($current_id_xml);
	
	// create attribute value node
	$current_id_xml_text = $doc_write->createTextNode($item_c);
	$current_id_xml->appendChild($current_id_xml_text);
		
	// create attribute node
	$current_lon_xml = $doc_write->createAttribute("lon");
	$item->appendChild($current_lon_xml);
	
	// create attribute value node
	$current_lon_xml_text = $doc_write->createTextNode($lons[$item_c]);
	$current_lon_xml->appendChild($current_lon_xml_text);
	
	// create attribute node
	$current_lat_xml = $doc_write->createAttribute("lat");
	$item->appendChild($current_lat_xml);
	
	// create attribute value node
	$current_lat_xml_text = $doc_write->createTextNode($lats[$item_c]);
	$current_lat_xml->appendChild($current_lat_xml_text);
	//new end
     }
  
  echo $doc_write->saveXML();
  $doc_write->save("police_box.xml");
    
	
}


runVertexTest();

?>
