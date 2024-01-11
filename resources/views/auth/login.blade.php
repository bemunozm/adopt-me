@extends('layouts.app')

@section('content')
    
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 position-sticky z-index-sticky top-0">
        <div class="container px-5">
            <a class="navbar-brand" href="{{route('index')}}"><span class="fw-bolder text-primary">Adopta un compañero</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
                    <li class="nav-item"><a class="nav-link" href="index.html"></a></li>
                    <li class="nav-item"><a class="nav-link" href="resume.html"></a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('login')}}">Iniciar Sesion</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('register')}}">Registrarse</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Inicia Sesion</h4>
                                    <p class="mb-0">Ingresa tu correo y contraseña</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') ?? 'admin@argon.com' }}" aria-label="Email">
                                            @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" aria-label="Password" value="secret" >
                                            @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Recuerdame</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Iniciar Sesion</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-1 text-sm mx-auto">
                                        Olvidaste tu contraseña? Reiniciar contraseña
                                        <a href="{{ route('reset-password') }}" class="text-primary text-gradient font-weight-bold">aqui</a>
                                    </p>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        No tienes una cuenta?
                                        <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Registrate</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                 style="background-image: url('{{ asset('assets/img/undraw_pet_adoption_-2-qkw.svg') }}');
                                        background-size: cover;
                                        background-position: left center;">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Crea momentos inolvidables, llenos de juegos y cariño"</h4>
                                <p class="text-white position-relative">Empieza una nueva historia con la adopción de un amigo peludo, tu compañero fiel.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
