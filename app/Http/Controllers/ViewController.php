<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\tools\CacheDrive;
use App\tools\Tools;

use Cache;
use Session;
use App\Pet;

class ViewController extends Controller
{
	use CacheDrive;
    use Tools;

	protected $lastSearch;
	protected $breedlist;
	protected $agelist;
	protected $sizelist;
	protected $genderlist;
	protected $statelist;
    protected $totalpageForLastSearch;

    protected $totalpageForFavorite;

    protected $numOfPetsPerPage;

    protected $lastSearchAnimal;

	public function __construct(){
        $this->numOfPetsPerPage = 12;
		$this->lastSearch = $this->getLastSearchCache();
        if(!empty($this->lastSearch)){
            $this->totalpageForLastSearch = ceil(count($this->lastSearch) / $this->numOfPetsPerPage);
            $el = reset($this->lastSearch);
            $this->lastSearchAnimal = $el['animal'];

        }
        else{
            $this->totalpageForLastSearch = 0;
            $this->lastSearchAnimal = 'Dog';
        }
    	
    	$this->agelist = config('myapi.age');
    	$this->sizelist = config('myapi.size');
    	$this->genderlist = config('myapi.sex');
    	$this->statelist = config('myapi.state');

        //dd($this->lastSearchAnimal);
	}

    public function index(Request $request){
        $page = 1;
        $this->breedlist = $this->getBreedList($this->lastSearchAnimal);
        $showlist = $this->generatePageData($page, $this->numOfPetsPerPage, $this->lastSearch);  
        //dd($showlist);
     	return view('pages.list')
                ->with('lastSearchAnimal', $this->lastSearchAnimal)
    			->with('showlist', $showlist)->with('breedlist', $this->breedlist)
    			->with('agelist', $this->agelist)->with('genderlist', $this->genderlist)
    			->with('sizelist', $this->sizelist)->with('statelist', $this->statelist)
                ->with('currentPage', $page)->with('currentAnimal', 'Dog')
                ->with('totalpageForLastSearch', $this->totalpageForLastSearch);
    }


    public function showHistory($animal, $page){        
        $showlist = $this->generatePageData($page, $this->numOfPetsPerPage, $this->lastSearch);
        $this->breedlist = $this->getBreedList($this->lastSearchAnimal);
        return view('pages.list')
                ->with('lastSearchAnimal', $this->lastSearchAnimal)
                ->with('showlist', $showlist)->with('breedlist', $this->breedlist)
                ->with('agelist', $this->agelist)->with('genderlist', $this->genderlist)
                ->with('sizelist', $this->sizelist)->with('statelist', $this->statelist)
                ->with('currentPage', $page)->with('currentAnimal', $animal)
                ->with('totalpageForLastSearch', $this->totalpageForLastSearch);  
    }


    public function showSearch($animal){
        $page = 1;
        $showlist = $this->generatePageData($page, $this->numOfPetsPerPage, $this->lastSearch);
        $this->breedlist = $this->getBreedList($animal);

        return view('pages.list')
                ->with('lastSearchAnimal', $this->lastSearchAnimal)
                ->with('showlist', $showlist)->with('breedlist', $this->breedlist)
                ->with('agelist', $this->agelist)->with('genderlist', $this->genderlist)
                ->with('sizelist', $this->sizelist)->with('statelist', $this->statelist)
                ->with('currentPage', $page)->with('currentAnimal', $animal)
                ->with('totalpageForLastSearch', $this->totalpageForLastSearch);
    }

    public function showFavorite(){

    }

    public function showDetails($animal, $id, Request $request){
       // dd($id);
        $petDetail = $this->getDetailsFromAPI($id);
        //dd($petDetail);

        $isFavorite = false;

        if($petDetail != null){
            $subtitle = "";
            $breeds = array();
            foreach($petDetail['breeds'] as $breed){
                if(isset($breed['$t']))
                    $breeds[] = $breed['$t'];
                else
                    $breeds[] = $breed;
            }

            $addr = $petDetail['city'] . "," . $petDetail['state'];
            $subtitle .= implode(" & ", $breeds) . " ";
            if(count($breeds) > 1)
                $subtitle .= "mix ";

            $subtitle .= $addr;

            if($petDetail['sex'] == 'M'){
                $petDetail['sex'] = 'Male';
            }
            else{
                $petDetail['sex'] = 'Female';
            }

            $addressInput = $petDetail['street'] . ","  . $petDetail['city'] . "," . $petDetail['state']; 

            switch($petDetail['size']){
                case 'S':
                    $petDetail['size'] = "Small";
                break;
                case 'M':
                    $petDetail['size'] = "Medium";
                break;
                case 'L':
                    $petDetail['size'] = "Large";
                break; 
                case 'XL':
                    $petDetail['size'] = "Extra Large";
                break;  
                default:
                    $petDetail['size'] = 'Uknown';
                break;                     
            }
            //dd($petDetail);

            $isFavorite = Pet::where('petrec', '=', $petDetail['id'])->exists();

            return view('pages.detail')
                ->with('petDetail', $petDetail)->with('subtitle', $subtitle)
                ->with('googleKey', config('myapi.googleKey'))
                ->with('addressInput', $addressInput)->with('isFavorite', $isFavorite);
        }
        else{
            $request->session()->flash('failure', 'No Information !');
            return redirect()->route('home');
        }
    }
}

