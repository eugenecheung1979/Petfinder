<?php
namespace App\tools;

trait Tools{
	public function generatePageData($currentPage, $numOfElemtsPerPage, $data){
       $start = $numOfElemtsPerPage * ( $currentPage - 1 ) + 1;
        $end = $start + $numOfElemtsPerPage - 1;

        $retlist = array();

        if(!empty($data)){
            $count = 1;            
            foreach($data as $id => $rec){
                if($count >= $start && $count <= $end)
                    $retlist[$id] = $rec;

                if($count > $end)
                    break;

                $count++;
            }
        }

        return $retlist;
	}

    
}