<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{ 
            $pro = Proveedor::all();
            return $pro; 
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.proveedor');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $ae = new Proveedor();
            $ae->nombre = $request->nombre;
            if($ae->save()>=1){
                return response()->json(['status'=>'ok', 'data'=>$ae], 201);
            } else {
                return response()->json(['status'=>'fail', 'data'=>null], 409);   
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
            $pro= Proveedor::findOrfail($id);
            return $pro;
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
            $pro = Proveedor::findOrfail($id);
            $pro->nombre = $request->nombre;
            if($pro->update() >=1){
                return response()->json(['status' =>'ok','data'=>$pro], 202);
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
            $pro = Proveedor::findOrFail($id);
            if($pro->delete()>=1){
                return response()->json(['status'=>'ok', 'data'=>null], 200);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
