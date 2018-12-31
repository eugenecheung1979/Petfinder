<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Pet;

class FavoriteController extends Controller
{
    
	public function show(){
		if(!isset($page))
			$page = 1;

		$list = Pet::orderBy('created_at', 'desc')->paginate(12);

		return view('pages.favoritelist')
			->with('showlist', $list)->with('currentPage', $page);
			
	}

	public function showmap(){
		//allow show at most 12 pets on a map at a time
		$list = Pet::orderBy('created_at', 'desc')->paginate(12);
		//$list = Pet::orderBy('created_at', 'desc');
		$addresslist = array();
		$favlist = array();
		foreach($list as $pet){
			$addresslist[$pet['petrec']] = $pet['street'] . "," . $pet['city'] . "," . $pet['state'];
			$favlist[$pet['petrec']] = $pet;
		}
		


		//dd($addresslist);
		return view('pages.favoritemap')
			->with('favlist', $favlist)
			->with('addresslist', $addresslist);
	}

    public function add(Request $request){
    	$data = $request->all();
 		$ret = array();
 		$validate = Validator::make($data, [
 				'name' => 'required',
 				'animal' => 'required',
 				'breeds' => 'required',
 				'size' => 'required',
 				'sex' => 'required',
 				'street' => 'required',
 				'city' => 'required',
 				'state' => 'required',
 				'zip' => 'required',
 				'petrec' => 'required',
 			]);
 
 		if($validate->fails()){ 			
 			$errors = implode(', ', $validate->errors()->all());
 			$ret['msg'] = $errors;
 		}
 		else{ 

	 		$pet = new Pet;
	 		$pet->name = addslashes($request->name);

	 		$pet->petrec = $request->petrec;

	 		$pet->animal = $request->animal;

	 		if(count($request->breeds) > 1){
	 			$breeds = array();
	 			foreach($request->breeds as $breed){
	 				$breeds[] = $breed['$t'];
	 			}
	 			$pet->breed = implode("|", $breeds);	 			
	 		}
	 		else{
	 			$pet->breed = implode("|", $request->breeds);
	 		}

	 		$pet->breed = addslashes($pet->breed);

	 		$pet->size = $request->size;
	 		$pet->sex = $request->sex;
	 		$pet->street = addslashes($request->street);
	 		$pet->city = addslashes($request->city);
	 		$pet->state = $request->state;
	 		$pet->zip = $request->zip;


	 		

	 		if(isset($request->phone))
	 			$pet->phone = $request->phone;
	 		if(isset($request->email))
	 			$pet->email = addslashes($request->email);
	 		
	 		if(isset($request->description))
	 			$pet->description = addslashes($request->description); 		
	 		
	 		if(isset($request->status))
	 			$pet->status = $request->status;
	 			 		


	 		if(isset($request->mix)){	 			
	 			$pet->mix = $request->mix;
	 		}
	 		if(isset($request->shelterid))
	 			$pet->shelterid = $request->shelterid; 

	 		if(isset($request->smallpic))
	 			$pet->smallpic = addslashes($request->smallpic); 
	 		if(isset($request->largepic))
	 			$pet->largepic = addslashes($request->largepic); 	

	 		try{
	 			$pet->save();
	 		}catch(\Exception $e){
	 			$ret['msg'] = $e->getMessage(); 
	 			return json_encode($ret);
	 		}

	 		$ret['msg'] = 'success';
 		}

 		return json_encode($ret);
    }


    public function delete(Request $request){
    	$data = $request->all();
    	$pids = $data['pids'];
    	$ret = array();

    	if(empty($pids)){
    		$ret['msg'] = "emptyinput";
    	}
    	else{
    		//DB::table('pets')->whereIn('pid', $pids)->delete();
    		try{
    			Pet::whereIn('pid', $pids)->delete();
    		}catch(\Exception $e){
    			$ret['msg'] = $e->getMessage();
    			return json_encode($ret);    			
    		}
    		$ret['msg'] = 'success';
    	}
    	return json_encode($ret);
    }
}
