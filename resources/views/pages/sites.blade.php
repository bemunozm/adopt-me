@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Sedes'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-5">
                    <div class="row align-items-center">
                        <!-- Columna para el Título, ocupando la mayor parte del espacio -->
                        <div class="col-8 col-md-9 col-lg-10">
                            <h3>Sedes</h3>
                        </div>
                        <!-- Columna para el Botón, con menos espacio asignado para empujarlo más a la derecha -->
                        <div class="col-4 col-md-3 col-lg-2 text-right">
                            <a href="{{ route('sites.create') }}" class="btn btn-icon btn-3 btn-primary">
                                <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                                <span class="btn-inner--text">Agregar Sede</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre Sede</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cantidad Mascotas
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ubicacion</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($user->organization->sites->isEmpty())
                                    <tr><td><h6>Su organizacion no tiene ninguna sede</h6></td></tr>
                                @else
                                    @foreach ($user->organization->sites as $site)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $site->name }}</h6>
                                                    <p class="text-sm font-weight-bold mb-0">{{ $site->address }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach ( $user->organization->sites as $site )
                                                @if ($site->pets->isEmpty())
                                                    <span>0</span>
                                                @else
                                                    <span>{{ $site->pets->count() }}</span>
                                                @endif
                                                
                                            @endforeach
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-bold mb-0">{{ $site->city }}, {{ $site->state }}</p>
                                        </td>
                                        <td class="text-center px-3">
                                            <div class="">
                                                <button type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                                  <li>
                                                    <button type="button" class="dropdown-item border-radius-md text-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete-{{ $site->id }}">Eliminar</button>
                                                    </li>
                                                  <li><a class="dropdown-item border-radius-md text-primary" href="{{ route('sites.edit', $site->id) }}">Modificar</a></li>
                                                </ul>
                                              </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="confirm-delete-{{ $site->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
                                        <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modal-title-notification">Confirmar Eliminación</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="py-3 text-center">
                                                        <i class="ni ni-bell-55 ni-3x"></i>
                                                        <h4 class="text-gradient text-danger mt-4">¿Estás seguro de que deseas eliminar esta sede?</h4>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <!-- Formulario para eliminar la sede -->
                                                    <form action="{{ route('sites.destroy', $site->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        @if ($site->pets->count() > 0)
                                                        <button type="button" class="btn btn-danger" disabled>Eliminar</button>
                                                        @else
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        @endif
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    @if ($site->pets->count() > 0)
                                                        <p class="text-sm font-weight-bold mb-0">*No puedes eliminar esta sede porque tienes {{$site->pets->count()}} mascotas asociadas.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
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
