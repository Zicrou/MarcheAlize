@extends('base')
@section('title', 'Toutes les opérations')

@section('content')
<style>
    body {
        background-color: #F2F2F2;
    }
</style>

    <div class="d-flex justify-content-between align-items-center">
        <h1>@yield('title')</h1>
        <!-- <a href="{{ route('admin.type.create') }}" class="btn btn-primary">Ajouter un type</a> -->
    </div>

        <div class="table-responsive">
            <table class="table table-bordered w-100 fs-5 align-middle mb-0" style="min-width: 760px;">
                <thead>
                    <tr>
                        <th>Intitulé</th>
                        <th>Type</th>
                        <th>Nombre</th>
                        <th>Prix</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operations as $operation)
                        <tr>
                            @if ($operation->designation)
                                <td>{{ $operation->designation }}</td>
                            @else
                                <td>{{ $operation->produit->designation }}</td>
                            @endif
                            <td>{{ $operation->type }}</td>
                            <td>{{ $operation->nombre }}</td>
                            
                            <td>{{ number_format($operation->prix, thousands_separator: ' ', decimal_separator: '.', decimals: 2) }} FCFA</td>
                            <td>{{ number_format($operation->prix * $operation->nombre, thousands_separator: ' ', decimal_separator: '.', decimals: 2) }} FCFA</td>
                            <td>{{ $operation->created_at->format('d/m/Y H:i:s') }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
                    
    {{ $operations->links() }}

@endsection