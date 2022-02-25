<body class="workSans" data-wow-delay="0.3s" style="animation-name: blind; animation-delay: 0.5s;background-color: white">
    {{-- <img src="{{ url('/') }}/img/{{ env('APP_LOGOEMPRESA') }}" alt="" class="" style="width:500px;height:150px;"> --}}
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="md-form">
                                    <i class="fas fa-user prefix grey-text"></i>
                                    <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                                    @if ($errors->has('username'))
                                        <script >
                                            Noty("ERROR! Usuario o Clave Incorrecto. Intente nuevamente", 4);
                                        </script>
                                    @endif
                                    <label for="username" class="" >Nombre de usuario</label>
                                </div>
                            </div>
                            
                            {{-- <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-sm-12" >
                                <div class="md-form">
                                    <i class="fas fa-key prefix grey-text"></i>
                                    <input id="password" type="password" class="form-control passw" @error('password') is-invalid @enderror name="password" required autocomplete="current-password">
                                    <a href="avascript:void(0)"></a><i class="fa fa-eye-slash icon nav-icon verPassw " style="" id="show_password"></i>
                                    @error('password')
                                    <script >
                                        Noty("ERROR! Usuario o Clave Incorrecto. Intente nuevamente", 4);
                                    </script>
                                    @enderror
                                    <label for="password">Clave</label>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="md-form">
                                    <i class="fas fa-key prefix grey-text"></i>
                                    <input id="password" type="password" class="form-control passw" @error('password') is-invalid @enderror name="password" required autocomplete="current-password">
                                    <i class="fa fa-eye-slash icon nav-icon verPassw " style="" id="show_password"></i>
                                    @error('password')
                                        <script >
                                            toastr.error("ERROR! Usuario o Clave Incorrecto. Intente nuevamente", "Ingreso");
                                        </script>
                                    @enderror
                                    <label for="password">Ingrese Clave</label>
                              </div>
                            </div>
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-outline-info btn-sm btn-block waves-effect" style="    border-radius: 50px;">{{ __('Ingresar')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>


</body>
