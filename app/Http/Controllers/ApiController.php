<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tools\CacheDrive;
use Response;

class ApiController extends Controller
{
	use CacheDrive;

	//protected $lastSearch;
	//protected $breedlist;
 	//protected $configlist;

    public function getBreed($animal){
    	//dd($animal);
    	//$breedlist = $this->getBreedList($animal);
    	$breedlist = $this->getBreedList($animal);
    	//Response::json($breedlist);
    	return json_encode($breedlist);
    }

    public function getConfig(){
    	$configlist = config('myapi');
    	$configlist['numOfPetsPerPage'] = 12;
    	//Response::json($configlist);
    	return json_encode($configlist);
    }

    public function getLastSearch(){
    	$lastSearch = $this->getLastSearchCache();
    	//dd($lastSearch);
    	//Response::json($lastSearch);
    	return json_encode($lastSearch);
    }


    public function searchPet(Request $request){
        $ret = array();
        $data = $request->all();
        $url = "";
        if($request->has('url'))
            $url = $data['url'];        

        if($url){
            $json_arr = json_decode(file_get_contents($url), true);
            $ret = $this->generatePetArr($json_arr);
        }

        if(!empty($ret)){            
            $this->updateLastSearchCache($ret);
        }
        return json_encode($ret);
    }
}
