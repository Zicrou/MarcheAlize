<?php

namespace App\Http\Controllers\Api\v1\Admin;

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
        return[
            "types" => Type::all()
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeFormRequest $request)
    {
        $type = Type::create($request->validated());
        return [
            "type" => $type,
            'message' => 'Le type a été créé',
            'success' => true
            ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeFormRequest $request, Type $type)
    {
        $type->update($request->validated());
        return [
            "type" => $type,
            'message' => 'Le type a été modifié',
            'success' => true
            ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $operation_type = DB::table('operation_type')->where('type_id', $type->id)->count();
        
        if($operation_type != 0){
            return [
                "type" => $type,
                'error', 'Le type est utilisé dans une vente'
            ];
        }
        $type->delete();
        return [
            "success" => true,
            'message' => 'Le type a été supprimé'
            ];
    }
}
