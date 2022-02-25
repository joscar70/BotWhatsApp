<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.ModulosApp.cdnCss')
        

        
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        @include('home')
                    @else
                        @include('auth.login')
                    @endauth
                </div>
            @endif

            
        </div>
    </body>
    @include('layouts.ModulosApp.cdnJs')
</html>
