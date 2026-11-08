<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OperationFormRequest;
use App\Http\Requests\SearchOperationRequest;
use App\Models\Depense;
use App\Models\Operation;
use App\Models\Produit;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchOperationRequest $request)
    {
        $depenseTotal = 0;
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $queryDepenses = Depense::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        foreach ($queryDepenses as $qd) {
            $depenseTotal += $qd->montant;
        }
        // dd($depenseTotal);
        $query = Operation::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');
        if ($price = $request->validated('price')) {
            $query->where('prix', '<=', $price);
        }
        if ($title = $request->validated('title')) {
            $query->with('produit')->whereHas('produit', function ($query) use ($title) {
                $query->where('designation', 'like', "%{$title}%");
            });
        }
        //  $totalOfTheDay = $query->sum("prix");
        $totalOfTheDay = 0;
        $totalVenteOfTheDay = 0;
        $totalReparationOfTheDay = 0;
        $totalVenteEtReparationOfTheDay = 0;
        $operations = $query->get();
        foreach ($operations as $operation) {
            $total = $operation->prix * $operation->nombre;
            $totalOfTheDay += $total;
            $type_operations = $operation->types()->get();
            foreach ($type_operations as $type_operation) {
                // dd($tv);
                if ($type_operation->name == 'Vente') {
                    $total = $operation->prix * $operation->nombre;
                    $totalVenteOfTheDay += $total;
                } elseif ($type_operation->name == 'Réparation') {
                    $total = $operation->prix * $operation->nombre;
                    $totalReparationOfTheDay += $total;
                }
                // elseif ($tv->id == 4) {
                //     $total = $vente->prix * $vente->nombre;
                //     $totalVenteEtReparationOfTheDay += $total;
                // }
            }
        }

        return view('operations.index', [
            'operations' => $query->paginate(2),
            'input' => $request->validated(),
            'totalOfTheDay' => $totalOfTheDay,
            'totalVenteOfTheDay' => $totalVenteOfTheDay,
            'totalReparationOfTheDay' => $totalReparationOfTheDay,
            'depenseTotal' => $depenseTotal,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operation = new Operation;
        $user = Auth::user();

        $operation->fill([
            'user_id' => $user->id,
        ]);
        $produits = Produit::pluck('designation', 'id');

        return view('operations.form', [
            'operation' => $operation,
            'produits' => $produits,
            'types' => Type::pluck('name', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperationFormRequest $request)
    {
        $produit = Produit::where('id', $request->validated('produit_id'))->first();
        if ($request->validated(key: 'designation') and $produit) {
            return redirect()->route('operation.create')->with('error', 'Choisir entre Stock et Désignation');
        } elseif ($request->validated('designation') or $produit) {
            if ($produit) {
                if ($produit->nombre < $request->validated('nombre')) {
                    return redirect()->route('operation.create')->with('error', 'Pas assez de produit de le stock');
                } else {
                    $operation = Operation::create($request->validated());
                    $produit->nombre -= $request->validated('nombre');
                    $produit->save();
                    $operation->types()->sync($request->validated('types'));

                    return to_route('operation.index')->with('success', 'L\'opération a été créée');
                }
            } else {
                $operation = Operation::create($request->validated());
                $operation->types()->sync($request->validated('types'));

                return to_route('operation.index')->with('success', 'L\'opération a été créée');
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operation $operation)
    {
        return view('operations.form', [
            'operation' => $operation,
            'types' => Type::pluck('name', 'id'),
            'produits' => Produit::pluck('designation', 'id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperationFormRequest $request, Operation $operation)
    {
        // dd($request->validated()) ;
        if ($request->validated('produit_id')) {
            $produit = Produit::where('id', $request->validated('produit_id'))->first();
            if ($request->validated('nombre') > $operation->nombre) {
                $produit->nombre = $produit->nombre - ($request->validated('nombre') - $operation->nombre);
            } elseif ($request->validated('nombre') < $operation->nombre) {
                $produit->nombre = $produit->nombre + ($operation->nombre - $request->validated('nombre'));
            }
            $produit->save();
        }
        $operation->update($request->validated());
        $operation->types()->sync($request->validated('types'));

        return to_route('operation.index')->with('success', 'L\'opération a été modifiée');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        $produit = Produit::find(id: $operation->produit_id);
        if ($produit) {
            $produit->nombre += $operation->nombre;
            $produit->save();
        }
        $operation->delete();

        return to_route('operation.index')->with('success', 'L\'opération a été annulée');
    }
}
