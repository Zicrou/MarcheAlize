<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depenseTotal = 0;
        // Add Depenses de chaque jour dans Journal

        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $depenses = Depense::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $queryDepenses = $depenses->groupBy(function ($m) {
            return $m->created_at->format('d-m-Y');
        });
        $journal = [];

        $oper = Operation::all();
        $operations = $oper->groupBy(function ($m) {
            return $m->created_at->format('d-m-Y');
        });

        $deps = Depense::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(1);
        $allDepenses = $deps->groupBy(function ($m) {
            return $m->created_at->format('d-m-Y');
        });

        foreach ($allDepenses as $date => $depenses) {
            $journal[$date]['depenses'] = $depenses;
            $journal[$date]['operations'] = collect();
        }

        foreach ($operations as $date => $operationsDuJour) {
            if (isset($journal[$date])) {
                $journal[$date]['operations'] = $operationsDuJour;
            } else {
                $journal[$date]['depenses'] = collect();
                $journal[$date]['operations'] = $operationsDuJour;
            }

        }
        // $vents_year = Vente::where('statut', 1)->get();
        $operations_year = $oper->groupBy(function ($m) {
            return $m->created_at->format('Y');
        });

        return view('admin.journaux.index', [
            'operations' => $operations,
            'operations_year' => $operations_year,
            'journal' => $journal,
            'depenses' => $allDepenses,
        ]);
    }

    public function journalDepense(){
        $depenses = Depense::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.journaux.depenses.index', compact('depenses'));
    }
    public function journalOperation(){
        $operations = Operation::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.journaux.operations.index', compact('operations'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
