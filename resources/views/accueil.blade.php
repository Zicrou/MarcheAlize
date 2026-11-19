@extends('base')

@section('title', 'Accueil')
@section('content')
    <section class="min-vh-100 d-flex align-items-center justify-content-center">
        <h1 class="mb-0">
            <a href="{{ route('operation.index') }}" class="btn btn-primary btn-lg fs-2 px-5 py-3">Commencer</a>
        </h1>
    </section>
@endsection