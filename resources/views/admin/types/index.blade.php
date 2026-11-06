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

        <div class="table-responsive">
                <table class="table table-bordered w-100 fs-5 align-middle mb-0" style="min-width: 760px;">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($types as $type)
                            <tr>
                                <td>Titre: {{ $type->name }}</td>
                                <td>
                                    <a href="{{ route('admin.type.edit', $type) }}" class="btn btn-primary">Editer</a>
                                    <form action="{{ route('admin.type.destroy', $type) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    {{ $types->links() }}

@endsection