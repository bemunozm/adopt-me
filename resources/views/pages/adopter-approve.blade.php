@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Postulaciones'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="row align-items-center">
                        <!-- Columna para el Título, ocupando la mayor parte del espacio -->
                        <div class="col-8 col-md-9 col-lg-10">
                            <h3>Gestor de postulantes para {{$pet->name}}</h3>
                            <h5>Estado actual = {{$pet->status}}</h5>
                        </div>
                        <!-- Columna para el Botón, con menos espacio asignado para empujarlo más a la derecha -->
                        @if ($user->type == 'Organizacion')
                        <div class="col-4 col-md-3 col-lg-2 text-right">
                            <form action="{{ route('adopter.filter', $pet->id) }}" method="GET">
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">Todos los Estados</option>
                                    <option value="Pendiente" {{ $statusFilter == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="Aceptado" {{ $statusFilter == 'Aceptado' ? 'selected' : '' }}>Aceptado</option>
                                    <option value="Rechazado" {{ $statusFilter == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                </select>
                            </form>
                        </div>
                        @endif
                        
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <form action="{{ route('adopter.changeStatus') }}" method="POST">
                        @csrf
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lugar de residencia</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Completado</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingApprovals as $adopter)
                                    <tr>
                                        <td class="px-3">
                                            <span class="text-lg text-bold">{{ $adopter->user->name }}</span>
                                            <span class="text-lg text-bold">{{ $adopter->user->last_name }}</span>
                                            <p class="text-sm">{{ $adopter->user->email }}</p>
                                            <p class="text-sm">{{ $adopter->user->phone }}</p>
                                        </td>
                                        <td class="px-3">
                                            <span class="text-lg text-bold">{{ $adopter->user->city }} ,{{ $adopter->user->state }} </span>
                                        </td>
                                        <input type="hidden" name="relations[{{ $adopter->pivot->id }}][pivotId]" value="{{ $adopter->pivot->id }}">
                                        <td class="text-center px-3">
                                            <select class="form-select" name="relations[{{ $adopter->pivot->id }}][status]">
                                                <option value="Pendiente" {{ $adopter->pivot->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="Aceptado" {{ $adopter->pivot->status == 'Aceptado' ? 'selected' : '' }}>Aceptado</option>
                                                <option value="Rechazado" {{ $adopter->pivot->status == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                            </select>
                                        </td>
                                        
                                        <td class="text-center px-3">
                                            <div class="">
                                                <button type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                                  <li><a class="dropdown-item border-radius-md text-sm">Descargar Comprobante</a></li>
                                                
                                                </ul>
                                              </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay postulantes con este estado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Actualizar Estados</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
  
   
@endsection
