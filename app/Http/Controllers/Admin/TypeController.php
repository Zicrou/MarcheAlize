<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TypeFormRequest;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.types.index",
    ["types" => Type::paginate(25) ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type = new Type();
        return view('admin.types.form', [
            'type' => $type
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeFormRequest $request)
    {
        $type = Type::create($request->validated());
        return to_route('admin.type.index')->with('success', 'Le type a été créé');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        return view('admin.types.form', ['type' => $type]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeFormRequest $request, Type $type)
    {
        $type->update($request->validated());
        return to_route('admin.type.index')->with('success', 'Le type a été modifié');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        // Update instead of delete
        // $type->update(['statut' => 0]);
        $type_vente = \DB::table('type_vente')->where('type_id', $type->id)->first();
        if($type_vente){
            return to_route('admin.type.index')->with('error', 'Le type est utilisé dans une vente');
        }
        $type->delete();
        return to_route('admin.type.index')->with('success', 'Le type a été supprimé');
    }
}
