<!DOCTYPE html>
<html>
    @include('partials.header')
  <body>    
    <div class="container" id="nav-container">
       @include('partials.home-navbar')    
    </div>
    @include('partials.message')
    @yield('content')

    @include('partials.footer') 
    @include('partials.scripts')

    @yield('extrascripts') 
  </body>
</html>