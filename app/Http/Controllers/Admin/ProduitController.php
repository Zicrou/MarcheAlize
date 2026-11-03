<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProduitFormRequest;
use App\Models\Produit;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.produits.index",
    ["produits" => Produit::orderBy('created_at', 'desc')->paginate(25) ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.produits.form', [
            'produit' => new Produit(),
            // 'types' => Type::pluck('name', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProduitFormRequest $request)
    {
        $data = $request->validated();
        if (request()->hasFile('image')) {
            if($image = $request->file('image')){
                $filename = $image->getClientOriginalName();
                $imageName = time().'-'.uniqid().'_'.$filename;
                $path = 'pictures/produit/';
                $data['image'] = $path.$imageName;
                // $image->storeAs($path, $imageName, 'public'); 
                $image->move($path, $imageName);
            }
        }
        $produit = Produit::create($data);
        // $produit->types()->sync($request->validated('types'));
        return to_route('admin.produit.index')->with('success', 'Le produit a été créé');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        return view('admin.produits.form', [
            'produit' => $produit, 
            // 'types' => Type::pluck('name', 'id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProduitFormRequest $request, Produit $produit)
    {
        $data = $request->validated();
        if (request()->hasFile('image')) {
            if($image = $request->file('image')){
                $filename = $image->getClientOriginalName();
                $imageName = time().'-'.uniqid().'_'.$filename;
                $path = 'pictures/produit/';
                $data['image'] = $path.$imageName;
                // $image->storeAs($path, $imageName, 'public'); 
                $image->move($path, $imageName);
            }
        }
        if (File::exists($produit->image)) {
            File::delete($produit->image);
        }
        $produit->update($data);
        // $produit->types()->sync($request->validated('types'));
        return to_route('admin.produit.index')->with('success', 'Le produit a été modifié');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
        if (File::exists($produit->image)) {
            File::delete($produit->image);
        }
        dd($produit->ventes);
        // $produit->delete();
        return to_route('admin.produit.index')->with('success', 'Le produit a été supprimé');
    }

    public function destroyImage($produit)
    {
        $produitImage = Produit::where('id', $produit)->first();
        if (File::exists($produitImage->image)) {
            File::delete($produitImage->image);
        }
        $produitImage->update(['image'=> '']);
        return to_route('admin.produit.index')->with('success', 'Le produit a été supprimé');
    }
}
