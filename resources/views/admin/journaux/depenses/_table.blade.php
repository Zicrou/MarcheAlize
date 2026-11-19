<div class="d-flex justify-content-between align-items-center">
    <h1>Toutes les dépenses</h1>
</div>

<div class="table-responsive">
    <table class="table table-bordered w-100 fs-5 align-middle mb-0" style="min-width: 760px;">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Montant</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($depenses as $depense)
                <tr>
                    <td>Titre: {{ $depense->libelle }}</td>
                    <td>{{ $depense->montant }}</td>
                    <td>{{ $depense->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $depenses->links() }}
