@extends('base')

@section('title', 'Toutes les dépenses')

@section('content')

    <div class="row m-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1>@yield('title')</h1>
            <a href="{{ route('depense.depense.create') }}" class="btn btn-primary">Ajouter une dépense</a>
        </div>
    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Montant</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($depenses as $depense)
                    <tr>
                        <td>{{ $depense->libelle }}</td>
                        <td>{{ number_format($depense->montant, thousands_separator: ' ') }}</td>
                        <td>
                            <div class="d-flex gap-2 w-100 justify-content-end">
                                <a href="{{ route('depense.depense.edit', $depense) }}" class="btn btn-primary">Editer</a>
                                <form action="{{ route('depense.depense.destroy', $depense) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        {{ $depenses->links() }}
    
    </div>
    
@endsection