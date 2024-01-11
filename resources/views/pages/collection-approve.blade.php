@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Colectas'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="row align-items-center">
                        <!-- Columna para el Título, ocupando la mayor parte del espacio -->
                        <div class="col-8 col-md-9 col-lg-10">
                            <h3>Gestor de donaciones</h3>
                        </div>
                        <!-- Columna para el Botón, con menos espacio asignado para empujarlo más a la derecha -->
                        @if ($user->type == 'Organizacion')
                        <div class="col-4 col-md-3 col-lg-2 text-right">
                            <form action="{{ route('collection.filter', $collection->id) }}" method="GET">
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">Todos los Estados</option>
                                    <option value="Pendiente" {{ $statusFilter == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="Aprobado" {{ $statusFilter == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="Rechazado" {{ $statusFilter == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                </select>
                            </form>
                        </div>
                        @endif
                        
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <form action="{{ route('collection.changeStatus') }}" method="POST">
                        @csrf
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Monto</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Completado</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingApprovals as $sponsor)
                                    <tr>
                                        <td class="px-3">
                                            <span class="text-lg text-bold">{{ $sponsor->name }}</span>
                                            <span class="text-lg text-bold">{{ $sponsor->last_name }}</span>
                                            <p class="text-sm">{{ $sponsor->email }}</p>
                                            <p class="text-sm">{{ $sponsor->phone }}</p>
                                        </td>
                                        <td class="px-3">
                                            <span class="text-lg text-bold">${{ number_format($sponsor->pivot->amount) }}</span>
                                        </td>
                                        <input type="hidden" name="relations[{{ $sponsor->pivot->id }}][pivotId]" value="{{ $sponsor->pivot->id }}">
                                        <td class="text-center px-3">
                                            <select class="form-select" name="relations[{{ $sponsor->pivot->id }}][status]">
                                                <option value="Pendiente" {{ $sponsor->pivot->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="Aprobado" {{ $sponsor->pivot->status == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                                <option value="Rechazado" {{ $sponsor->pivot->status == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                            </select>
                                        </td>
                                        <td class="text-center px-3">
                                            <a type="button" class=" text-sm" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $sponsor->id }}">
                                                Ver Imagen
                                            </a>
                                        </td>
                                        <td class="text-center px-3">
                                            <div class="">
                                                <button type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                                  <li><a href="{{ Storage::url($sponsor->pivot->file) }}" download class="dropdown-item border-radius-md text-sm">Descargar Comprobante</a></li>
                                                
                                                </ul>
                                              </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay patrocinadores con este estado.</td>
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
   <!-- Modal -->
   @if ($pendingApprovals->isNotEmpty())
   <div class="modal fade" id="imageModal-{{ $sponsor->id }}" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Imagen de Contribución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ Storage::url($sponsor->pivot->file) }}" class="img-fluid" alt="Imagen de Contribución">
            </div>
        </div>
    </div>
</div>
   @endif
   
@endsection
