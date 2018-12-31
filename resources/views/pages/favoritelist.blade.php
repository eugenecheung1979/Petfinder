 @extends('main')
   @section('content')

   <div class="container">
   		<div class="row" id="showlist">
   			@foreach($showlist as $pet)
          @php
            $pid = $pet['pid'];
            $petrec = $pet['petrec'];
          @endphp
	   			<div class="col-3">
	   				<div class="thumbnail" id="pet-{{$pid}}">
                     <a href="/details/{{$pet['animal']}}/{{$petrec}}">
                        @if(!empty($pet['smallpic']))
   				                 <img id="petImg-{{$pid}}" src="{!!$pet['smallpic']!!}" class="rounded float-left" alt="{{$pet['name']}}" width="250" height="300">
                       @else
                           <img id="petImg-{{$pid}}" src="#" class="rounded float-left" alt="Lights" width="250" height="300">
                       @endif
   				            </a>
                     <input type="checkbox" class="form-check-input" id="chkbx-{{$pid}}">
   			        <div class="caption">
   			          <p><span id="petName-{{$pid}}">{{$pet['name']}}</span>, <span id="petSex-{{$pid}}">{{$pet['sex']}}</span>, <span id="petCity-{{$pid}}">{{$pet['city']}}</span></p> 
    			        </div>   				      
				      </div>
	   			</div>
   			@endforeach
   		</div>         
         <!--<center><button type="button" class="btn btn-primary">Favorite</button></center>-->

       {{$showlist->links()}}

       <br>
       <center><button type="button" class="btn btn-danger btn-lg" id="delBtn" disabled>Delete</button>
   </div>
   
   @stop

   @section('extrascripts')    
      <script src="/js/favoritelist.js"></script>
   @stop
