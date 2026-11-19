@extends('base')

@section('title', 'Journaux')

@section('content')
    <section class="mb-5">
        <div class="mb-5">
            @include('admin.journaux.depenses._table')
        </div>

        <div>
            @include('admin.journaux.operations._table')
        </div>
    </section>
@endsection
