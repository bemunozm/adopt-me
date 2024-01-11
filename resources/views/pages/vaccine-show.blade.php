@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Vacunas'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="row align-items-center">
                        <!-- Columna para el Título, ocupando la mayor parte del espacio -->
                        <div class="col-8 col-md-9 col-lg-10">
                            <h3>Vacunas</h3>
                        </div>
                        <!-- Columna para el Botón, con menos espacio asignado para empujarlo más a la derecha -->
                        
                            <div class="col-4 col-md-3 col-lg-2 text-right">
                                <a href="{{ route('vaccine.create', ['pet_id' => $pet->id]) }}" class="btn btn-icon btn-3 btn-primary">
                                    <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                                    <span class="btn-inner--text">Agregar Vacuna</span>
                                </a>
                            </div>

                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                                  
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Descripcion</th>
                    
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fecha</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">A cargo</th>
                                    
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Certificado</th>  

                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($vaccines->isEmpty())
                                <tr><td>
                                    <span class="text-lg">Aun no registras vacunas para tu mascota :c</span>
                                </td></tr>
                                @else
                                @foreach ($vaccines as $vaccine)
                                    <tr>

                                        
                                        <td class="px-3">
                                            <span class="text-sm">{{ $vaccine->name }}</span>
                                        </td>
                                        
                                       
                                        <td class="px-3">
                                            <textarea class="text-sm">{{ $vaccine->description }}</textarea>
                                        </td>
                                      
                                    
                                        <td class="px-3">
                                            <span class="text-sm">{{ $vaccine->date }}</span>
                                        </td>
                         
                                        <td class="text-center px-3">
                                            <span class="text-sm">{{ $vaccine->vet }}</span>
                                        </td>

                                        <td class="text-center px-3">
                                            <a type="button" class=" text-sm" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $vaccine->id }}">
                                                Ver Imagen
                                            </a>
                                        </td>
                         
                                        <td class="text-center px-3">
                                            <div class="">
                                                <button type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                                  <li><a href="{{ Storage::url($vaccine->image) }}" download class="dropdown-item border-radius-md text-sm">Descargar Certificado</a></li>
                                                  
                                                  <li><form action="{{ route('vaccine.destroy', $vaccine->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item border-radius-md text-danger">Eliminar</button>
                                                </form>
                                                </li>
                                                
                                               
                                                <a href="{{ route('vaccine.edit', $vaccine->id) }}" class="dropdown-item border-radius-md text-primary">
                                                    Editar
                                                </a>
                                                  
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

    <!-- Modal -->
   @if ($vaccines->isNotEmpty())
   <div class="modal fade" id="imageModal-{{ $vaccine->id }}" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Imagen del Certificado</h5>
                <button type="button" class="btn-primary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ Storage::url($vaccine->image) }}" class="img-fluid" alt="Imagen de Contribución">
            </div>
        </div>
    </div>
</div>
   @endif
@endsection
