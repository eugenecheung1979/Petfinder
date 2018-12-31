<?php
namespace App\tools;


use Cache;
use Response;
use Carbon\Carbon;
use Config;

trait CacheDrive{

	/*
		LastSearchResult is what is showing on frontpage. 
	*/
	public function getLastSearchCache(){
		$lastSearch = array();
		//$lastSearch = $this->getRandomList(config('myapi.defaultCount'));
		//Cache::forever('lastSearch', $lastSearch);
		$lastSearch = Cache::get('lastSearch');
		if (!Cache::has('lastSearch') || empty($lastSearch)){
			$lastSearch = $this->getRandomList();
			Cache::forever('lastSearch', $lastSearch);

    	}

    	return $lastSearch;
	}


	public function updateLastSearchCache($searchResult){
		if(!empty($sarchResult)){
			Cache::forget('lastSearch');
			Cache::forever('lastSearch', $searchResult);
		}
	}


	public function getBreedList($animal){	
		$breedlist = array();
		//dd("i am at very beginning of getBreedList Mehtod");
		if(Cache::has('breedlist')){
			//dd("i am in cache breedlist");
			$breedlist = Cache::get('breedlist');
			//dd($breedlist);
			if(empty($breedlist[$animal])){
				$breedlist[$animal] = $this->getBreedFromAPI($animal);
				Cache::forever('breedlist', $breedlist);
			}
			return $breedlist[$animal];
		}
		else{
			$breedlist = array();
			//dd($breedlist);
			$breedlist[$animal] = $this->getBreedFromAPI($animal);
			if(!empty($breedlist[$animal]))
				Cache::forever('breedlist', $breedlist);
			return $breedlist[$animal];
		}
	}

	public function updateBreedList($breedlist){
		if(!empty($breedlist)){
			Cache::forget('breedlist');
			Cache::forever('breedlist', $breedlist);
		}
	}

	/******************************************************/


	public function getRandomList(){

		$count = config('myapi.defaultCount');
		$location = config('myapi.defaultLocation');

		$url = "http://api.petfinder.com/pet.find?key=" . config('myapi.key') . "&count=" . $count . "&location=" . $location . "&format=json";

		$json_arr = json_decode(file_get_contents($url), true);
		$list = $this->generatePetArr($json_arr);

		//dd($list);
		return $list;
	}


	public function getBreedFromAPI($animal=null){
		if(!$animal)
			$animal = 'dog';
		$url = "http://api.petfinder.com/breed.list?key=" . config('myapi.key') . "&format=json&animal=" . strtolower($animal);
		//dd($url);
		$json_arr = json_decode(file_get_contents($url), true);
		$list = $this->generateBreedList($json_arr);
		//dd($json_arr);
		//dd($list);
		return $list;
	}

	public function generatePetArr($json_arr){
		if(isset($json_arr['petfinder']['pets']['pet']) && !empty($json_arr['petfinder']['pets']['pet']))
			return $this->populatePetArr($json_arr['petfinder']['pets']['pet']);
		else
			return null;		
	}


	public function generatePetDetails($json_arr){
		//dd($json_arr);
		if(isset($json_arr['petfinder']['pet']))
			return $this->populatePetArr($json_arr['petfinder']['pet'], true);
		else
			return null;
	}

	//internal tool for filling pet array
	public function populatePetArr($arr, $isSingle=false){
		$keysRequired = ['id', 'animal', 'name', 'breeds', 'size', 'sex', 'age', 'contact', 'description', 'status', 'mix', 'shelterid', 'media']; 
		$list = array();
		if(!empty($arr)){
			if(!$isSingle){
				foreach($arr as $pet){
					$id = $pet['id']['$t'];
					$list[$id]['animal'] = $pet['animal']['$t'];
					$list[$id]['name'] = $pet['name']['$t'];
					$list[$id]['petrec'] = $id;
					$list[$id]['breeds'] = array();
					if(!empty($pet['breeds']['breed'])){
						if(is_array($pet['breeds']['breed'])){
							$count = 0;
							foreach($pet['breeds']['breed'] as $breed){
								$list[$id]['breeds'][$count] = $breed;														
								$count++;
							}
						}
						else
							$list[$id]['breeds'][0] = $pet['breeds']['breed'];
						
					}
					
					$list[$id]['size'] = $pet['size']['$t'];
					$list[$id]['sex'] = $pet['sex']['$t'];
					$list[$id]['age'] = $pet['age']['$t'];

					$address1 = "";
					if(!empty($pet['contact']['address1']))
						$address1 .= $pet['contact']['address1']['$t'];

					$address2 = "";
					if(!empty($pet['contact']['address2']))
						$address2 .= ", " . $pet['contact']['address2']['$t'];
					$list[$id]['street'] = $address1 . $address2;

					$list[$id]['city'] = $pet['contact']['city']['$t'];
					$list[$id]['state'] = $pet['contact']['state']['$t'];				
					$list[$id]['zip'] = $pet['contact']['zip']['$t'];

					if(!empty($pet['contact']['phone']))
						$list[$id]['phone'] = $pet['contact']['phone']['$t'];

					if(!empty($pet['contact']['email']))
						$list[$id]['email'] = $pet['contact']['email']['$t'];

					if(!empty($pet['description']))
						$list[$id]['description'] = $pet['description']['$t'];
					$list[$id]['status'] = $pet['status']['$t'];
					$list[$id]['mix'] = $pet['mix']['$t'];
					$list[$id]['shelterid'] = $pet['shelterId']['$t'];

					if(!empty($pet['media']['photos']['photo'])){
						$list[$id]['smallpic'] = $pet['media']['photos']['photo'][3]['$t'];
						$list[$id]['largepic'] = $pet['media']['photos']['photo'][2]['$t'];
					}
				}
			}
			else{
				//dd($arr);
				foreach($arr as $key => $val){
					if(in_array($key, $keysRequired)){
						if($key != 'contact' && $key != 'breeds' && $key != 'media'){
							if(isset($val['$t']))
								$list[$key] = $val['$t'];
							else
								$list[$key] = $val;

							if($key == 'id'){
								$list['petrec'] = $list[$key]; // match to column in database;
							}
						}
						else if ($key == 'contact'){
							$address1 = "";
							if(!empty($val['address1']))
								$address1 .= $val['address1']['$t'];

							$address2 = "";
							if(!empty($val['address2']))
								$address2 .= "\n" . $val['address2']['$t'];
							$list['street'] = $address1 . $address2;

							$list['city'] = $val['city']['$t'];
							$list['state'] = $val['state']['$t'];				
							$list['zip'] = $val['zip']['$t'];

							if(!empty($val['phone']))
								$list['phone'] = $val['phone']['$t'];

							if(!empty($val['email']))
								$list['email'] = $val['email']['$t'];							
						}						
						else if ($key == 'breeds'){
							$list['breeds'] = array();
							if(!empty($val['breed'])){
								if(is_array($val['breed'])){
									$count = 0;
									foreach($val['breed'] as $breed){
										$list['breeds'][$count] = $breed;														
										$count++;
									}
								}
								else
									$list['breeds'][0] = $val['breed'];								
							}
						}
						else if ($key == 'media'){
							if(!empty($val['photos']['photo'])){
								$list['smallpic'] = $val['photos']['photo'][3]['$t'];
								$list['largepic'] = $val['photos']['photo'][2]['$t'];
							}							
						}
					}
				}
			}
		}
		return $list;
	}

	public function generateBreedList($json_arr){
		$list = array();
		//dd($json_arr);
		if(!empty($json_arr['petfinder']['breeds']['breed'])){
			foreach($json_arr['petfinder']['breeds']['breed'] as $breed ){
				$list[] = $breed['$t'];
			}
		}
		//dd("return list: " . $list);
		//dd($list);
		return $list;
	}


	public function getDetailsFromAPI($id){
		$url = "http://api.petfinder.com/pet.get?key=" . config('myapi.key') . "&id=" . $id . "&format=json";
		$json_arr = json_decode(file_get_contents($url), true);
		//dd($json_arr);
		$list = $this->generatePetDetails($json_arr); 
		//dd($list);
		return $list;
	}
}
