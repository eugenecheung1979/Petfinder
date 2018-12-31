   @extends('main')
   @section('content')

   <div class="container">
         <input hidden id="currentAnimal" value="{{$currentAnimal}}" />
         <input hidden id="currentPage" value="{{$currentPage}}" />         
   		<div class="row">
   			<div class="col-2">
   				<p class="h4">Breed</p>
   				<select class="form-control" id="breedSelector" disabled="disabled">
   					@foreach($breedlist as $breed)
   						<option value="{{$breed}}">{{$breed}}</option>
   					@endforeach
                  <option value="Any" selected="selected">Any</option>
   				</select>
   			</div>

   			
  			<div class="col-2">
   				<p class="h4">Age</p>
   				<select class="form-control" id="ageSelector" disabled="disabled">
   					@foreach($agelist as $age)
   						<option value="{{$age}}">{{$age}}</option>
   					@endforeach
                  <option value="Any" selected="selected">Any</option>
   				</select>
   			</div>
			

  			<div class="col-2">
   				<p class="h4">Size</p>
   				<select class="form-control" id="sizeSelector" disabled="disabled">
   					@foreach($sizelist as $sizekey => $size)
   						<option value="{{$sizekey}}">{{$size}}</option>
   					@endforeach
                  <option value="Any" selected="selected">Any</option>
   				</select>
   			</div>


  			<div class="col-2">
   				<p class="h4">Gender</p>
   				<select class="form-control" id="genderSelector" disabled="disabled">
   					@foreach($genderlist as $genderkey => $gender)
   						<option value="{{$genderkey}}">{{$gender}}</option>
   					@endforeach
                  <option value="Any" selected="selected">Any</option>
   				</select>
   			</div>

  			<div class="col-2">
   				<p class="h4">State</p>
   				<select class="form-control" id="stateSelector" disabled="disabled">
   					@foreach($statelist as $state)
   						<option value="{{$state}}">{{$state}}</option>
   					@endforeach
   				</select>
   			</div>   			   			   			

   		</div>
   </div>	

   <hr>

   <div class="container">
   		<div class="row" id="showlist">
   			@foreach($showlist as $pid => $pet)
	   			<div class="col-3">
	   				<div class="thumbnail" id="pet-{{$pid}}">
                     <a href="/details/{{$pet['animal']}}/{{$pid}}">
                        @if(!empty($pet['smallpic']))
   				                 <img id="petImg-{{$pid}}" src="{!!$pet['smallpic']!!}" class="rounded float-left" alt="{{$pet['name']}}" width="250" height="300">
                       @else
                           <img id="petImg-{{$pid}}" src="#" class="rounded float-left" alt="Lights" width="250" height="300">
                       @endif
   				            </a>
                     <!--<input type="checkbox" class="form-check-input" id="chkbx-{{$pid}}">-->
   			        <div class="caption">
   			          <p><span id="petName-{{$pid}}">{{$pet['name']}}</span>, <span id="petSex-{{$pid}}">{{$pet['sex']}}</span>, <span id="petCity-{{$pid}}">{{$pet['city']}}</span></p> 
    			        </div>   				      
				      </div>
	   			</div>
   			@endforeach
   		</div>         
         <!--<center><button type="button" class="btn btn-primary">Favorite</button></center>-->

      <nav aria-label="Search Result">
           <ul class="pagination" id="pageNav">
            @if($currentPage > 1)   
             <li class="page-item"><a class="page-link" href="/showHistory/{{$lastSearchAnimal}}/{{$currentPage - 1}}">Previous</a></li>
            @endif
            @for($i = 1; $i <= $totalpageForLastSearch; $i++)
               @if($i == $currentPage)
                  <li class="page-item active"><a class="page-link" href="/showHistory/{{$lastSearchAnimal}}/{{$i}}">{{$i}}</a></li>
               @else
                  <li class="page-item"><a class="page-link" href="/showHistory/{{$lastSearchAnimal}}/{{$i}}">{{$i}}</a></li>
               @endif
            @endfor
            @if($currentPage < $totalpageForLastSearch)
               <li class="page-item"><a class="page-link" href="/showHistory/{{$lastSearchAnimal}}/{{$currentPage + 1}}">Next</a></li>
            @endif
           </ul>
      </nav>
   </div>
   
   @stop

   @section('extrascripts')    
      <script src="/js/search.js"></script>
   @stop
