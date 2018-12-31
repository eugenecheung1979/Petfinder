<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="{{url('/')}}">
		<img src="{{asset('images/mypetfinder.png')}}"/>
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarNavDropdown">
	    <ul class="navbar-nav">
	    	<!--
	      <li class="nav-item active">
	        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
	      </li>
			-->
	      <li class="{{Request::is('searchView/Dog') ? 'nav-item active' : 'nav-item'}}">
	        <a class="nav-link" href="/searchView/Dog"><h5>Dog Search</h5></a>
	      </li>
	      <li class="{{Request::is('searchView/Cat') ? 'nav-item active' : 'nav-item'}}">	      
	         <a class="nav-link" href="/searchView/Cat"><h5>Cat Search</h5></a>
	      </li>

	      <li class="{{Request::is('showMyFavorites') ? 'nav-item active' : 'nav-item'}}">	      
	      	 <a class="nav-link" href="/showMyFavorites"><h5>My Favorite</h5></a>
	      </li>

	      <li class="{{Request::is('showFavoritesMap') ? 'nav-item active' : 'nav-item'}}">	      
	      	 <a class="nav-link" href="/showFavoritesMap"><h5>Favorite Map</h5></a>
	      </li>
	    </ul>
	  </div>
</nav>