<?php
require_once("PriorityQueue.php");

class Edge {
	
	public $start;
	public $end;
	public $weight;
	
	public function __construct($start, $end, $weight) {
		$this->start = $start;
		$this->end = $end;
		$this->weight = $weight;
	}
}

class Graph {
	
	public $nodes = array();
	public $lons = array();
	public $lats = array();
	public $dest;
	
	public function addedge($start, $end, $weight = 0) {
		if (!isset($this->nodes[$start])) {
			$this->nodes[$start] = array();
		}
		array_push($this->nodes[$start], new Edge($start, $end, $weight));
	}
	//Improvement Start
	public function initialize_lon_lat_dest($lons, $lats, $dest)
	{
		$this->lons = $lons;
		$this->lats = $lats;
		$this->dest = $dest;
	}
	//Improvement End    
    	public function removenode($index) {
		array_splice($this->nodes, $index, 1);
	}
	
	
	public function paths_from($from) {
		$distance_source_dest = $this->calculate_distance($from, $this->dest);
		$dist = array();
		$dist[$from] = 0;
		
		$visited = array();
		
		$previous = array();
		
		$queue = array();
		$Q = new PriorityQueue("compareWeights");
		$Q->add(array($dist[$from], $from));
		
		$nodes = $this->nodes;
		
		while($Q->size() > 0) {
			list($distance, $u) = $Q->remove();
			
			if (isset($visited[$u])) {
				continue;
			}
			$visited[$u] = True;
			
			if (!isset($nodes[$u])) {
				print "WARNING: '$u' is not found in the node list\n";
			}

			foreach($nodes[$u] as $edge) {
				//Improvement Start
				$distance_end_dest = $this->calculate_distance($edge->end, $this->dest);
				$distance_end_source = $this->calculate_distance($from, $edge->end);

				if($distance_source_dest<$distance_end_dest || $distance_source_dest<$distance_end_source)
				{
					$visited[$edge->end] = True;
					continue;
				}
				//Improvement End
				$alt = $dist[$u] + $edge->weight;
				$end = $edge->end;
				if (!isset($dist[$end]) || $alt < $dist[$end]) {
					$previous[$end] = $u;
					$dist[$end] = $alt;
					$Q->add(array($dist[$end], $end));
				}
			}
		}
		return array($dist, $previous);
	}
	
	public function paths_to($node_dsts, $tonode) {
		// unwind the previous nodes for the specific destination node
		
		$current = $tonode;
		$path = array();
		
		if (isset($node_dsts[$current])) { // only add if there is a path to node
			array_push($path, $tonode);
		}
		while(isset($node_dsts[$current])) {
			$nextnode = $node_dsts[$current];
			
			array_push($path, $nextnode);
			
			$current = $nextnode;
		}
		
		return array_reverse($path);
		
	}
	
	public function getpath($from, $to) {
		list($distances, $prev) = $this->paths_from($from);
		return $this->paths_to($prev, $to);
	}
	//Improvement Start
	public function calculate_distance($start_id, $end_id)
	{
		$prev_lon = $this->lons[$start_id];
		$prev_lat = $this->lats[$start_id];
		$current_lon = $this->lons[$end_id];
		$current_lat = $this->lats[$end_id];

		$square_lon = ($current_lon-$prev_lon) * ($current_lon-$prev_lon);
		$square_lat = ($current_lat - $prev_lat) * ($current_lat - $prev_lat);		
		$distance = sqrt($square_lon + $square_lat);
		return $distance;
	}
	//Improvement End
	
}

function compareWeights($a, $b) {
	return $a->data[0] - $b->data[0];
}


?>
