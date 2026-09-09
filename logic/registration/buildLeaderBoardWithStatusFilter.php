<?php
include __DIR__ . "/components/getLeaderBoardData.php";

function buildLeaderBoardWithStatusFilter($database, $filter = []){
    $rawData = getLeaderBoardData($database);
    $result = [];
    $leaderboard = [];

    foreach($rawData as $data){
        if(in_array($data['statusCode'], $filter, true)){
            continue;
        }
        $result[] = $data;
    }

    foreach ($result as $item){
        $leaderboard[$item['jabatanId']][] = $item;
    }

    foreach ($leaderboard as &$list){

        $rank = 1;

        foreach($list as &$item){
            $item['rank'] = $rank++;
        }

    }

    array_walk($leaderboard, function(&$item){
        usort($item, function($a, $b) {
            return $a['rank'] <=> $b['rank']; 
        });
    });

    #$leaderboard = array_values($leaderboard);

    return $leaderboard;
}
?>