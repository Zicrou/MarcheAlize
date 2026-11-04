@extends('base')

@section('title', $depense->exists? 'Editer' : 'Ajouter une depense')

@section('content')
    <h1 class="">@yield('title')</h1>
    <form action="{{route($depense->exists ? 'depense.depense.update' : 'depense.depense.store', $depense)}}" method="post">
        @csrf
        @method($depense->exists ? 'put' : 'post')
        <div class="row d-flex justify-content-center">
            <input type="hidden" name="user_id" value={{ $depense->user_id }}>
            <div class="col-12 col-lg-6 mb-3">
                @include('shared.input', ['class' => 'col', 'name' => 'libelle', 'label' => 'Libellé', 'value' => $depense->libelle])
            </div>
            <div class="col-12 col-lg-6 mb-3">
                @include('shared.input', ['class' => 'col', 'name' => 'montant', 'label' => 'Montant', 'type' => 'number', 'value' => $depense->montant])
            </div>
            <div class="d-flex justify-content-start mt-3">
                <button class="btn btn-primary">
                    @if ($depense->exists)
                        Modifier
                    @else
                        Créer
                    @endif
                </button>
            </div>
        </div>
    </form>
@endsection