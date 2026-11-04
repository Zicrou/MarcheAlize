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
            <div class=" col-12 col-lg-6 mb-3">
                @include('shared.input', ['name' => 'image', 'label' => 'Image', 'type' => 'file'])
                {{-- @include('shared.checkbox', ['name' => 'etat', 'label' => 'Etat normal', 'value' => $produit->etat]) --}}
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
    @php
    //   dd($produit->image )
    @endphp
    <div class="row">
        <h2>Images</h2>
        @if ($produit !== '' && $produit->image !== '' && $produit->id !== '')
            {{-- @if ($produit->image !== '' && $produit->id !== '' ) --}}
                {{-- @foreach ( $produit as $img ) --}}
                    <div class="d-flex col-sm-3 mt-4">
                        @if ($produit->image)
                            <img class="" src="{{ asset($produit->image) }}" alt="image" style="width:600px;height:300px">
                        @endif 
                        {{-- <a class="px-2" href="{{ route('produit.destroyImage', $produit->id) }}">Effacer</a> --}}
                    </div>
                {{-- @endforeach --}}
            {{-- @endif --}}
        @endif
    </div>
@endsection