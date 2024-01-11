@extends('layouts.app')

@section('content')
<!-- Asegúrate de que los enlaces a los CSS y JS estén en el layout principal o aquí, según sea necesario -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
<main class="flex-shrink-0">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
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
    <!-- Projects Section-->
    <section class="py-5">
        <div class="container px-5 mb-5">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Mascotas</span></h1>
            </div>
            <!-- Aquí empieza la grilla de mascotas -->
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($pets as $pet)
                    <div class="col">
                        <div class="card h-100 shadow-sm"> <!-- Utiliza h-100 para igualar la altura de las tarjetas -->
                            <!-- Imagen de la mascota -->
                            @if($pet->images->isNotEmpty())
                                <img src="{{ Storage::url($pet->images->first()->image) }}" class="card-img-top" alt="Imagen de {{ $pet->name }}">
                            @endif
                            <!-- Detalles de la mascota -->
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary">{{ $pet->name }}</h5>
                                <p class="card-text">{{ Str::limit($pet->description, 150) }}</p>
                                <p class="card-text"><small class="text-muted">Ubicación: {{ $pet->site->city }}, {{ $pet->site->state }}.</small></p>
                                <p class="card-text"><small class="text-muted">Organización: {{ $pet->site->organization->organization_name }}.</small></p>
                                <!-- Botón para ver más detalles -->
                                <a href="{{route('pet.show', $pet->id)}}" class="btn btn-sm btn-outline-primary mt-auto">Ver Más</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="py-5 bg-gradient-primary-to-secondary text-white">
        <div class="container my-5" style="max-width: 700px;"> 
            <div class="text-center">
                <h2 class="text-secondary display-4 fw-bolder mb-4">Adopta Ahora</h2>
                <a class="btn btn-primary btn-lg px-5 py-3 fs-6 fw-bolder" href="{{route('register')}}">
                    <i class="bi bi-heart-fill me-2"></i> Adopta
                </a>
            </div>
        </div>
    </section>
</main>
<!-- Footer-->
<footer class="bg-white py-4 mt-auto">
    <div class="container px-5">
        <div class="row align-items-center justify-content-between flex-column flex-sm-row">
            <div class="col-auto"><div class="small m-0">Copyright &copy; Adopt Me 2023</div></div>
            
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
