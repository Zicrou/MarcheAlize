@extends('base')

@section('title', $operation->exists? 'Editer l\'opération' : 'Ajouter une opération')
@php
$route = request()->route()->getName();
// dd($operation);
@endphp
@section('content')
    <h1>@yield('title')</h1>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session(key: 'error') }}
        </div>
    @endif

    <form action="{{route($operation->exists ? 'operation.update' : 'operation.store', $operation)}}" method="post">
        @csrf
        @method($operation->exists ? 'put' : 'post')
        <div class="row d-flex justify-content-center mt-5">
            <input type="hidden" name="user_id" value={{ $operation->user_id }}>
            <input type="hidden" name="statut" value={{ $operation->statut }}>
            <div class="d-flex flex-column mb-3 col-10">
                <div class="d-flex justify-content-center">
                    @include('shared.select', ['class' => 'col mb-3', 'name' => 'types', 'label' => 'Types', 'value' => $operation->types()->pluck('id')])
                </div>
                @if (str_contains($route, 'edit'))
                    @if (!$operation->designation)
                        <h4>{{ $operation->produit->designation }}: stock</h4>
                        <input type="hidden" name="produit_id" value={{ $operation->produit_id }}>
                    @elseif (!$operation->produit_id)
                        @include('shared.input', ['class' => '', 'name' => 'designation', 'label' => 'Désignation', 'type' => 'text', 'value' => $operation->designation])
                    @endif 
                @elseif (str_contains($route, 'create'))
                    <div class="row">
                        <div class="col-10 col-lg-5">
                            @include('shared.selectProduit', ['class' => '', 'name' => 'produit_id', 'label' => 'A partir du stock', 'value' => $operation->produit()->pluck('id'), 'disabled' => true, 'multiple' => false])
                        </div>
                        <span class="fw-bold fs-2 ps-5 col-lg-2">Ou</span>
                        <div class="col-10 col-lg-5">
                            @include('shared.input', ['class' => 'col', 'name' => 'designation', 'label' => 'Désignation', 'type' => 'text', 'value' => $operation->designation])
                        </div>
                    </div>
                @endif
                <div class="row mt-4">
                    <div class="col-12 col-lg-6 mb-3">
                        @include('shared.input', ['class' => 'col', 'name' => 'nombre', 'label' => 'Nombre','type' => 'number', 'value' => $operation->nombre])
                    </div>
                    <div class="col-12 col-lg-6">
                        @include('shared.input', ['class' => 'col', 'name' => 'prix', 'label' => 'Prix','type' => 'number', 'value' => $operation->prix])
                    </div>
                    <div class="col-12 col-lg-6" hidden>
                        @include('shared.input', ['class' => 'col', 'name' => 'team_id', 'type' => 'text', 'value' => $operation->team_id ?? Auth::user()->team_id])
                    </div>
                </div>
                <!-- @include('shared.input', ['name' => 'image', 'label' => 'Image', 'type' => 'file'])  -->
            </div>
            {{-- <label class="mb-1">Image</label>
            <input type="file" name="image" class="form-control" value="{{ old('image') }}"> --}}
            {{-- @include('shared.checkbox', ['name' => 'etat', 'label' => 'Etat normal', 'value' => $operation->etat]) --}}
            <div class="col-3 col-lg-3">
                <button class="btn btn-primary">
                    @if ($operation->exists)
                        Modifier
                    @else
                        Créer
                    @endif
                </button>
            </div>
        </div>
    </form>
@endsection