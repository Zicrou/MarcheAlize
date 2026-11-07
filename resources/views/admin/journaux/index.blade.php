@extends('base')

@section('title', 'Journal des ventes et dépenses')
@section('content')
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-primary col-2 mb-3 fs-5"><a href="{{ route('admin.journal.depenses')}}" class="text-decoration-none text-light">Voir les dépenses</a></button>
            <button class="btn btn-primary col-2 mb-3 fs-5"><a href="{{ route('admin.journal.operations')}}" class="text-decoration-none text-light">Voir les opérations</a></button>
        </div>
    
    </section>
@endsection
