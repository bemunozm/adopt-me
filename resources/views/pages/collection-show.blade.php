@extends('layouts.app')
{{--ANIMACION BARRA DE PROGRESO--}}
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
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $collection->title])

<div class="container mt-4">
    <div class="row">
        <!-- Columna principal para la imagen y el título -->
        <div class="col-md-8">
            <div class="card mb-3">
                <img src="{{ Storage::url($collection->image) }}" alt="Imagen">

                <div class="card-body">
                    <h5 class="card-title">{{ $collection->title }}</h5>
                    <p>{{ $collection->description }}</p>
                </div>
            </div>
            <!-- Aquí van los detalles de transferencia debajo de la imagen -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detalles de Transferencia</h5>
                    <p><strong>Nombre:</strong> {{ $collection->organization->organization_name }}</p>
                    <p><strong>Email:</strong> {{ $collection->email }}</p>
                    <p><strong>Banco:</strong> {{ $collection->bank }}</p>
                    <p><strong>Tipo de Cuenta:</strong> {{ $collection->type_of_account }}</p>
                    <p><strong>Numero de cuenta:</strong> {{ $collection->account_number }}</p>
                </div>
            </div>
        </div>
        <!-- Columna secundaria para los detalles del proyecto y acciones -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    {{--<h5 class="card-title">Contribución</h5>--}}
                    <div class="progress">
                        @php
                            $percentage = ($amountCollected / $collection->amount) * 100;
                        @endphp
                        <div class="progress-bar bg-gradient-success" role="progressbar" style="--progress-width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    

                    <div class="mt-3">
                        <h2 class="card-title text-success">{{ number_format($amountCollected) }}$</h2>
                        <p class="text-muted">contribuido de {{ number_format($collection->amount) }}$</p>
                            <h3 class="mb-0">{{ $sponsorCount }}</h3>
                            <p class="text-sm text-muted mb-0">patrocinadores</p>
                            <h3 class="mt-3">{{ $daysLeft }}</h3>
                            <p class="text-sm text-muted mb-4">días más</p>
                    </div>
                    <a href="{{ route('donate.show', $collection->id) }}" class="btn btn-success w-100">Sumate a la colecta!</a>
                    @if ($collection->organization->user_id == $user->id)
                    <p class="text-sm text-muted mb-4 text-center">ó</p>
                        <a href="{{ route('collection.edit', $collection->id) }}" class="btn btn-primary w-100">Modificar colecta</a>
                     @endif
                </div>
            </div>
        </div>
        
        
    </div> 
</div>

@endsection
