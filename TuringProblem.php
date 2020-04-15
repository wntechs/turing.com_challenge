<?php


class TuringProblem
{
    private $points, $travel_time, $current_point;
    public function __construct($points, $travel_time, $start_token)
    {
        $this->current_point = $start_token;
        foreach($points as $point){
            $this->points[$point[0]] = $point[1];
        }

        $this->travel_time = $travel_time;
    }

    public function findScore(){
        $has_more_points = true;
        $path = [];
        $total = 0;
        array_push($path, $this->current_point);
        do{
            $effectiveNode = $this->getEffectiveNode();
           if($effectiveNode != false){

                array_push($path, $effectiveNode[0]);
                $total += $effectiveNode[1];
                $this->current_point = $effectiveNode[0];
           }else{
               $has_more_points = false;
           }
        }while($has_more_points);

       return [$total, $path];
    }

    private function getEffectiveNode(){
        $effectiveness = 0;
        $effectiveNode = false;
        $score = 0;
        $reachableNodes = $this->getReachableNodes();
        if(count($reachableNodes) > 0 ) {
            foreach ($reachableNodes as $node) {
                $time = $node[2];
                $pointValue = $this->points[$node[1]];
                if (($pointValue / $time) > $effectiveness) {
                    $effectiveness = $pointValue / $time;
                    $effectiveNode = $node[1];
                    $score = $pointValue - $time;
                }
            }
            return [$effectiveNode, $score];
        }
        return false;
    }

    private function getReachableNodes(){

        $reachableNodes = [];
        foreach($this->travel_time as $t_node){
            if($t_node[0] == $this->current_point){
                $reachableNodes[] = $t_node;
            }
        }
        return $reachableNodes;
    }

}
$points = [
    ["START", 0],
    ["A", 24],
    ["B", 3],
    ["C", 10],
    ["D", 7],
    ["E", 24],
    ["F", 3],
    ["END_1", 4],
    ["END_2", 7]
];
$travel_time = [
    ["START", "A", 5],
    ["START", "B", 6],
    ["START", "C", 10],
    ["A", "D", 4],
    ["B", "D", 5],
    ["C", "D", 6],
    ["C", "E", 5],
    ["D", "F", 3],
    ["E", "F", 1],
    ["F", "END_1", 5],
    ["F", "END_2", 10],
];

$obj = new TuringProblem($points, $travel_time, "START");
$results = $obj->findScore();
echo $results[0] . ' ' . implode(', ',$results[1]);
