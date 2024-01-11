@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Mis Mascotas'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="row align-items-center">
                        <!-- Columna para el Título, ocupando la mayor parte del espacio -->
                        <div class="col-8 col-md-9 col-lg-10">
                            <h3>Mis Mascotas</h3>
                        </div>
                        <!-- Columna para el Botón, con menos espacio asignado para empujarlo más a la derecha -->
                        @if ($user->type == 'Organizacion')
                            <div class="col-4 col-md-3 col-lg-2 text-right">
                                <a href="{{ route('pet.create') }}" class="btn btn-icon btn-3 btn-primary">
                                    <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                                    <span class="btn-inner--text">Agregar Mascota</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                                    @if ($user->type == 'Adoptante')
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Organizacion</th>
                                    @endif

                                    @if ($user->type == 'Organizacion')                        
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sede</th>
                                    @endif

                                    @if ($user->type == 'Adoptante')
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ubicacion</th>   
                                    @endif
                                                                        
                                    @if ($user->type == 'Organizacion')
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                                    @endif
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pets->isEmpty())
                                <tr><td>
                                    <span class="text-lg">Aun no tienes ninguna mascota :c</span>
                                </td></tr>
                                @else
                                @foreach ($pets as $pet)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    @if ($pet->images->isNotEmpty())
                                                    
                                                    <img src="{{ Storage::url($pet->images->first()->image) }}" class="avatar avatar-lg me-3" alt="{{ $pet->name }}">
                                                    @else
                                                        <img src="/img/team-2.jpg" class="avatar avatar-lg me-3" alt="default">
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $pet->name }}</h6>
                                                </div>
                                            </div>
                                        </td> 

                                        @if ($user->type == 'Adoptante')
                                        <td class="px-3">
                                            <span class="text-sm">{{ $pet->site->organization->organization_name }}</span>
                                        </td>
                                        @endif
                                        @if ($user->type == 'Organizacion')
                                        <td class="px-3">
                                            <span class="text-sm">{{ $pet->site->name }}</span>
                                        </td>
                                        @endif
                                        @if ($user->type == 'Adoptante')
                                        <td class="px-3">
                                            <span class="text-sm">{{ $pet->site->city }},{{ $pet->site->state }}</span>
                                        </td>
                                        @endif
                                        @if ($user->type == 'Organizacion')
                                        <td class="text-center px-3">
                                            <span class="text-sm">{{ $pet->status }}</span>
                                        </td>
                                        @endif
                                        
                                        <td class="text-center px-3">
                                            <div class="">
                                                <button type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                                  <li><a class="dropdown-item border-radius-md" href="{{ route('vaccine.show', $pet->id) }}">Vacunas</a></li>
                                                  <li><a class="dropdown-item border-radius-md" href="{{ route('visit.show', $pet->id) }}">Visitas</a></li>
                                                  <li><a class="dropdown-item border-radius-md" href="{{ route('pet.show', $pet->id) }}">Otro</a></li>
                                                  @if ($user->type == 'Organizacion')
                                                  <li><form action="{{ route('pet.destroy', $pet->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item border-radius-md text-danger">Eliminar</button>
                                                </form>
                                                </li>
                                                @endif
                                                @if ($user->type == 'Organizacion')
                                                <form action="{{ route('adopter.approve', $pet->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item border-radius-md text-primary">Ver Solicitudes</button>
                                                </form>
                                                  @endif
                                                </ul>
                                              </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
