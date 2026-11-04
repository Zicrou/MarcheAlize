@extends('base')
@section('title', 'Tous les types')

@section('content')
<style>
    body {
        background-color: #F2F2F2;
    }
</style>

    <div class="d-flex justify-content-between align-items-center">
        <h1>@yield('title')</h1>
        <a href="{{ route('admin.type.create') }}" class="btn btn-primary">Ajouter un type</a>
    </div>

    @foreach ($types as $type)
        <div class="d-flex justify-content-center align-items-center">
            <div class="card col-4 mb-5">
                <div class="card-body bg-white">
                    Titre: {{ $type->name }}
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-evenly">
                        <div class="col-12 col-lg-3">
                            <a href="{{ route('admin.type.edit', $type) }}" class="btn btn-primary">Editer</a>
                        </div>
                        <div class="col-12 col-lg-3">
                            <form action="{{ route('admin.type.destroy', $type) }}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger">Supprimer</button>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    @endforeach
    {{ $types->links() }}

@endsection