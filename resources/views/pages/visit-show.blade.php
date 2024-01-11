@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Visitas'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="row align-items-center">
                        <!-- Columna para el Título, ocupando la mayor parte del espacio -->
                        <div class="col-8 col-md-9 col-lg-10">
                            <h3>Visitas</h3>
                        </div>
                        <!-- Columna para el Botón, con menos espacio asignado para empujarlo más a la derecha -->
                        @if ($user->type == 'Organizacion')
                        <div class="col-4 col-md-3 col-lg-2 text-right">
                            <a href="{{ route('visit.create', ['pet_id' => $pet->id]) }}" class="btn btn-icon btn-3 btn-primary">
                                <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                                <span class="btn-inner--text">Agendar Visita</span>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Titulo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipo</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Notas</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($meetings->isEmpty())
                                    <tr><td><span class="text-sm">No tienes ninguna reunion programada.</span></td></tr>
                                @endif
                                @foreach ($meetings as $meeting)
                                    <tr>
                                        <td>{{ $meeting->pivot->title }}</td>
                                        <td class="px-3">
                                            <span class="text-sm">{{ $meeting->pivot->meeting_type }}</span>
                                        </td>
                                        <td class="text-center px-3">
                                            <span class="text-sm">{{ $meeting->pivot->meeting_date }}</span>
                                        </td>
                                        <td style="text-align: center;"> <!-- Alineación central del td -->
                                            <span class="text-sm">{{ $meeting->pivot->status }}</span>
                                        </td>
                                        <td class="text-center px-3">
                                            <a type="button" class=" text-sm" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $meeting->id }}">
                                                Ver NOTAS
                                            </a>
                                        </td>
                                        
                                        <td class="text-center px-3">
                                            <div class="">
                                                <button type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                                    @if ($user->type == 'Adoptante')
                                                  <li><form action="{{ route('visit.changeStatus', $meeting->pivot->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Confirmada">
                                                    <input type="hidden" name="pet_id" value="{{$pet->id}}">
                                                    <button type="submit" class="btn btn-success">Confirmar</button>
                                                </form></li>
                                                  <li><form action="{{ route('visit.changeStatus', $meeting->pivot->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Cancelada">
                                                    <input type="hidden" name="pet_id" value="{{$pet->id}}">
                                                    <button type="submit" class="btn btn-danger">Cancelar</button>
                                                </form></li>
                                                  @endif
                                                  @if ($user->type == 'Organizacion')
                                                  <li><form action="{{ route('visit.destroy', $meeting->pivot->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    <button type="submit" class="dropdown-item border-radius-md text-danger">Eliminar</button>
                                                </form>
                                                </li>
                                                @endif
                                                @if ($user->type == 'Organizacion')
                                                  <li>
                                                    <a href="{{route('visit.edit', $meeting->pivot->id)}}" class="dropdown-item border-radius-md text-primary">Modificar</a>
                                                  </li>
                                                @endif
                                                </ul>
                                              </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
   @if ($meetings->isNotEmpty())
   <div class="modal fade" id="imageModal-{{ $meeting->id }}" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Notas</h5>
                <button type="button" class="btn-primary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{$meeting->pivot->notes}}</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
