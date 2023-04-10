<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use Illuminate\Http\Request;

class DetallePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $detail = DetallePedido::all();
            $response = $detail->toArray();
            $i=0;
            foreach($detail as $ea){
                $response[$i]["pedido"] = $ea->pedido->toArray();
                
              $i++;  
            }
            return $response;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.detalles');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $ae = new DetallePedido();
            $ae->cantidad = $request->cantidad;
            $ae->fecha_pedido = $request->fecha_pedido;
            $ae->pedidos_id = $request->pedido['id'];
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
            $ae = DetallePedido::findOrFail($id);
            $response = $ae->toArray();
            $i=0;
              $response[$i]["pedido"] = $ae->pedido->toArray(); 
            //dd($response);
            return $response;
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
            $ae = new DetallePedido();
            $ae->cantidad = $request->cantidad;
            $ae->fecha_pedido = $request->fecha_pedido;
            $ae->pedidos_id = $request->pedido['id'];
            if ($ae->update() >= 1){
                return response()->json(['status'=>'ok', 'data'=>$ae], 202);
            } else {
                return response()->json(['status'=>'fail', 'data'=>null], 409);   
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
           $ae = DetallePedido::findOrFail($id);
           if($ae->delete() >= 1){
            return response()->json(['status'=>'ok', 'data'=>null], 200);
           }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
