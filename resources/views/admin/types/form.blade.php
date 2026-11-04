@extends('base')

@section('title', $type->exists? 'Editer le type' : 'Ajouter un type')

@section('content')
    <h1>@yield('title')</h1>
    <form action="{{route($type->exists ? 'admin.type.update' : 'admin.type.store', $type)}}" method="post">
        @csrf
        @method($type->exists ? 'put' : 'post')
        <div class="row d-flex flex-column align-items-center justify-content-center">
            <div class="col-12 col-lg-6 mb-3">
            @include('shared.input', ['class' => 'col', 'name' => 'name', 'label' => 'Titre', 'value' => $type->name])
            </div>
            <div class="col-12 col-lg-6 mt-3">
            <button class="btn btn-primary">
                @if ($type->exists)
                Modifier
                @else
                Créer
                @endif
            </button>
            </div>
        </div>
    </form>
@endsection