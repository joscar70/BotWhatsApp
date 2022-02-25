<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/turbolinks/5.2.0/turbolinks.js"></script>
        @include('layouts.ModulosApp.cdnCss')
        @include('layouts.ModulosApp.cdnJs')
        
    </head>
     @if (Route::has('login'))
        @auth
            <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed layout-footer-fixed texto" onload="" style="background-color: gray;font-size: ">
               
                <!-- Site wrapper -->

                <div class="wrapper">
                    
                    {{-- @if ( auth()->user()->rol=='ADMIN' ) --}}
                    
                        @include('layouts.ModulosApp.header')
                        @include('layouts.ModulosApp.sidebar')
                        @yield('content')
                        
                       
                        {{-- @yield('content') --}}
                       @include('layouts.ModulosApp.footer')
                </div>
            </body>
        @else
            @include('auth.login')
        @endauth
     @endif
   
</html>

