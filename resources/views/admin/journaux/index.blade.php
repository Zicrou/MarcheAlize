@extends('base')
@section('Ajourd\'hui')

@section('content')
    <section class="mb-5">
        <div class="d-flex justify-content-around align-items-center" style="min-height: 100vh;">
            
            <button class="btn btn-primary col-3 mb-3 fs-5"><a href="{{ route('admin.journal.depenses', ["coequipier" => $coequipier])}}" class="text-decoration-none text-light">Voir les dépenses</a></button>
            <button class="btn btn-primary col-3 mb-3 fs-5"><a href="{{ route('admin.journal.operations', ["coequipier" => $coequipier])}}" class="text-decoration-none text-light">Voir les opérations</a></button>
        </div>
    
    </section>
@endsection
