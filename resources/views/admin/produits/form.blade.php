@extends('base')

@section('title', $produit->exists? 'Editer le produit' : 'Ajouter un produit')

@section('content')
    <h1>@yield('title')</h1>
    <form action="{{route($produit->exists ? 'admin.produit.update' : 'admin.produit.store', $produit)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method($produit->exists ? 'put' : 'post')
        <div class="row d-flex flex-column justify-content-center">
        
            <div class="col-12 col-lg-6 mb-3">
                @include('shared.input', ['class' => 'col', 'name' => 'designation', 'label' => 'Désignation', 'value' => $produit->designation])
            </div>
            <div class="col-12 col-lg-6 mb-3">
                @include('shared.input', ['class' => 'col', 'name' => 'nombre', 'label' => 'Nombre','type' => 'number', 'value' => $produit->nombre])
            </div>
            <div class="col-12 col-lg-6 mb-3">
                @include('shared.input', ['class' => 'col', 'name' => 'montant', 'label' => 'Montant', 'type' => 'number', 'value' => $produit->montant])
            </div>
            
            <div>
                <button class="btn btn-primary">
                    @if ($produit->exists)
                        Modifier
                    @else
                        Créer
                    @endif
                </button>
            </div>
        </div>
    </form>
    
@endsection