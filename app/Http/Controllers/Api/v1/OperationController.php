<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\OperationFormRequest;
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
        $depenseTotal = 0.00;
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $queryDepenses = Depense::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        foreach ($queryDepenses as $qd) {
            $depenseTotal += $qd->montant;
        }
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
        $totalOfTheDay = 0.00;
        $totalVenteOfTheDay = 0.00;
        $totalReparationOfTheDay = 0.00;
        $totalVenteEtReparationOfTheDay = 0;
        $operations = $query->get();
        foreach ($operations as $operation) {
            $total = $operation->prix * $operation->nombre;
            $totalOfTheDay += $total;
            $type_operations = $operation->types()->get();
            foreach ($type_operations as $type_operation) {
                if ($type_operation->name == 'Vente') {
                    $total = $operation->prix * $operation->nombre;
                    $totalVenteOfTheDay += $total;
                } elseif ($type_operation->name == 'Réparation') {
                    $total = $operation->prix * $operation->nombre;
                    $totalReparationOfTheDay += $total;
                }
                
            }
        }
        return [
            'operations' => $operations->load('types'),
            'input' => $request->validated(),
            'totalOfTheDay' => $totalOfTheDay,
            'totalVenteOfTheDay' => $totalVenteOfTheDay,
            'totalReparationOfTheDay' => $totalReparationOfTheDay,
            'depenseTotal' => $depenseTotal,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperationFormRequest $request)
    {
        $validated =  $request->validated();
        $validated['user_id'] = $request->user()->id;
        $validated['team_id'] = $request->user()->team->id;
        // return $validated;
        $produit = '';
        if($request->validated('produit_id')){
            $produit = Produit::find($request->validated('produit_id'))->first();
        }
        if ($validated['designation'] and $produit != '') {
            return ['error' => 'Choisir entre Stock et Désignation'];
        } elseif ($validated['designation'] or $produit) {
            if ($produit) {
                if ($produit->nombre < $validated['nombre']) {
                    return ['error', 'Pas assez de produit de le stock'];
                } else {
                    $operation = Operation::create($validated);
                    $produit->nombre -= $validated['nombre'];
                    $produit->save();
                    $operation->types()->sync($validated['types']);
                    return [
                        "operation" => $operation,
                        'success' => 'L\'opération a été créée'
                    ];
                }
            } else {
                $operation = Operation::create($validated);
                $operation->types()->sync($validated['types']);

                return [
                    "operation" => $operation,
                    'message' => 'L\'opération a été créée',
                    "success" => true
                ];
            }
        }
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

        return [
            "operation" => $operation,
            'message' => 'L\'opération a été modifiée',
            "success" => true
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        $produit = Produit::find($operation->produit_id);
        if ($produit) {
            $produit->nombre += $operation->nombre;
            $produit->save();
        }
        $operation->delete();
        return [
            'message' => 'L\'opération a été annulée',
            "success" => true
        ];
    }
}
