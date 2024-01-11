@extends('layouts.app')

@section('content')
<style>
    @keyframes progressBarIncrease {
        from {
            width: 0%;
        }
        to {
            width: var(--progress-width);
        }
    }
    .progress-bar {
        animation: progressBarIncrease 1s ease-in-out forwards;
    }
</style>
    @include('layouts.navbars.auth.topnav', ['title' => 'Colectas'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="row align-items-center">
                        <!-- Columna para el Título, ocupando la mayor parte del espacio -->
                        <div class="col-8 col-md-9 col-lg-10">
                            <h3>Colectas</h3>
                        </div>
                        <!-- Columna para el Botón, con menos espacio asignado para empujarlo más a la derecha -->
                        @if ($user->type == 'Organizacion')
                        <div class="col-4 col-md-3 col-lg-2 text-right">
                            <a href="{{ route('collection.create') }}" class="btn btn-icon btn-3 btn-primary">
                                <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                                <span class="btn-inner--text">Crear Colecta</span>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Monto</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha limite</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Completado</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($collections->isEmpty())
                                    <tr><td><span class="text-sm">No tienes ninguna colecta.</span></td></tr>
                                @endif
                                @foreach ($collections as $collection)
                                    <tr>
                                        <td class="px-3">
                                            <span class="text-sm">{{ $collection->title }}</span>
                                            <span class="text-sm">{{ $collection->organization->organization_name }}</span>
                                        </td>
                                        <td class="px-3">
                                            <span class="text-sm">{{ $collection->amount }}</span>
                                        </td>
                                        <td class="text-center px-3">
                                            <span class="text-sm">{{ $collection->finish_date }}</span>
                                        </td>
                                        <td style="text-align: center;"> <!-- Alineación central del td -->
                                            @php
                                                $amountCollected = $collection->approvedDonations->sum('pivot.amount');
                                                $percentage = ($amountCollected / $collection->amount) * 100;
                                            @endphp
                                            <div class="progress-wrapper" style="display: flex; align-items: center; justify-content: center; flex-direction: column;"> <!-- Estilos Flexbox para alinear los elementos internos -->
                                                <div class="progress-info">
                                                    <div class="progress-percentage">
                                                        <span class="text-sm font-weight-bold">{{ number_format($percentage) }}%</span>
                                                    </div>
                                                </div>
                                                <div class="progress" style="height: 10px; width: 100%;"> <!-- Asegura que la barra de progreso ocupe todo el ancho disponible -->
                                                    <div class="progress-bar bg-gradient-success" role="progressbar" style="--progress-width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="text-center px-3">
                                            <div class="">
                                                <button type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                                                  <li><a class="dropdown-item border-radius-md" href="{{ route('collection.show', $collection->id) }}">Ver Mas</a></li>
                                                  @if ($user->type == 'Organizacion')
                                                  <li><form action="{{ route('collection.destroy', $collection->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item border-radius-md text-danger">Eliminar</button>
                                                </form>
                                                </li>
                                                @endif
                                                @if ($user->type == 'Organizacion')
                                                  <li>
                                                    <form action="{{ route('collection.approve', $collection->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-radius-md text-primary">Ver Solicitudes</button>
                                                    </form>
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
@endsection
