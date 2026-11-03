<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepenseFormRequest;
use Illuminate\Http\Request;
use App\Models\Depense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $query = Depense::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');
        return view("depenses.index", [
            "depenses" => $query->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $depense = new Depense();
        $depense->fill([
            'user_id' => User::first()->id,
        ]);
        return view("depenses.form",[
            "depense" => $depense,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepenseFormRequest $request)
    {
        $depense = Depense::create($request->validated());
        return to_route('depense.depense.index')->with('success', 'La dépense a été créée');
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
    public function edit(Depense $depense)
    {
        return view('depenses.form', ['depense' => $depense]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepenseFormRequest $request, Depense $depense)
    {
        $depense->update($request->validated());
        return to_route('depense.depense.index')->with('success', 'La dépense a été modifiée');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depense $depense)
    {
        $depense->delete();
        return to_route('depense.depense.index')->with('success', 'Le type a été supprimé');
    }
}
