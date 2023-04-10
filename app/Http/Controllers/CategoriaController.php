<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{ 
            $categoria = Categoria::all();
            return $categoria; 
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categorias');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $categoria = new Categoria();
            $categoria->nombre = $request->nombre;
            if($categoria->save() >= 1){
                return response()->json(['status'=>'ok','data'=>$categoria],201);
            }else{
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $categoria = Categoria::findOrfail($id);
            return $categoria;
        }catch(\Exception $e){
            return $e->getMessage();
        }
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
        try{
            $categoria = Categoria::findOrfail($id);
            $categoria->nombre = $request->nombre;
            if($categoria->update() >=1){
                return response()->json(['status' =>'ok','data'=>$categoria], 202);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $ca = Categoria::findOrFail($id);
            if($ca->delete()>=1){
                return response()->json(['status'=>'ok', 'data'=>null], 200);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
