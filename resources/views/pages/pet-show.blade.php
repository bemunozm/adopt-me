@extends('layouts.app')

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $pet->name])

<div class="container mt-4">
    <div class="card position-relative">
        @if ($user->type == 'Adoptante' && $adopted)
            <button class="btn btn-danger position-absolute" style="top: 20px; right: 20px;" disabled>Solicitud Enviada!</button>
        @elseif($user->type == 'Adoptante')
            <form method="POST" action="{{ route('pet.adopt', ['pet_id' => $pet->id]) }}">
                @csrf
                <button type="submit" class="btn btn-success position-absolute" style="top: 20px; right: 20px;">Adopta Ahora!</button>
            </form>
        
        @endif
        
        <div class="row g-0">
            <div class="col-md-6">
                
                <div class="card-body">
                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carouselPreview">
                            {{-- Assuming $pet->images contains URLs to the image files --}}
                            @foreach ($pet->images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($image->image) }}" class="d-block w-100" alt="{{ $pet->name }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6" >
                <!-- Pet details -->
                <div class="card-body" >
                    <h1 class="card-title">{{ $pet->name }}</h1>
                    <p class="card-text">{{ $pet->description }}</p>
                    <ul class="list-unstyled">
                        <li><strong class="text-sm">Especie:</strong> {{ $pet->species }}</li>
                        <li><strong>Raza:</strong> {{ $pet->race }}</li>
                        <li><strong>Tamaño:</strong> {{ $pet->size }}</li>
                        <li><strong>Edad:</strong> {{ $pet->age }} meses</li>
                        <li><strong>Sexo:</strong> {{ $pet->sex }}</li>
                        <li><strong>Nivel de Energia:</strong> {{ $pet->energy }}</li>
                        <li><strong>Es sociable con niños?:</strong> {{ $pet->social_children }}</li>
                        <li><strong>Es sociable con otros perros?:</strong> {{ $pet->social_dog }}</li>
                        <li><strong>Es sociable con gatos?:</strong> {{ $pet->social_cat }}</li>
                    </ul>
                    @if($user->type == 'Organizacion')
                    <div class="row">
                        <div class="col-md-4" >
                            <a href="{{ route('visit.show', $pet->id) }}" class="btn btn-primary position-absolute">Visitas Programadas</a>
                        </div>
                        <div class="col-md-3" >
                            <a href="{{ route('vaccine.show', $pet->id) }}" class="btn btn-primary position-absolute">Ver Vacunas</a>
                        </div>
                        <div class="col-md-4" >
                            <a href="{{ route('pet.edit', $pet->id) }}" class="btn btn-primary position-absolute" >Modificar Mascota</a>
                        </div>
                    </div>
                    @endif                                
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function handleFileSelect(evt) {
        var files = evt.target.files; 
        var carouselInner = document.getElementById('carouselPreview');
        carouselInner.innerHTML = ''; // Limpiar carrusel

        for (var i = 0, f; f = files[i]; i++) {
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {
                    var carouselItem = document.createElement('div');
                    carouselItem.className = 'carousel-item h-100';
                    if (carouselInner.children.length === 0) {
                        carouselItem.classList.add('active');
                    }
                    
                    carouselItem.innerHTML = `
                        <img src="${e.target.result}" class="d-block w-100" alt="${theFile.name}">`;
                    carouselInner.appendChild(carouselItem);
                };
            })(f);

            reader.readAsDataURL(f);
        }
    }

    document.getElementById('images').addEventListener('change', handleFileSelect, false);
</script>
@endsection
