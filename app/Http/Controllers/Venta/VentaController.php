<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::with('paquete')
        ->orderBy('id')
        ->cliente(request()->get('cliente'))
        ->paginate(5);
        return response()->json([
            'ok' => true,
            'results' => $ventas
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'cliente' => ['required'],
            'paquete_id' => ['required'],
            'telefono' => ['required'],
            'cantidad_adultos' => ['required'],
            'cantidad_ninos' => ['required'],
            'subtotal' => ['required'],
            'total' => ['required'],
            'fecha' => ['required'],
            'user_id' => ['required'],
            'status' => 'in:'. Venta::RESERVADO.','. Venta::CANCELADO,
        ], [
            'cliente.required' => 'El campo cliente es requerido',
            'paquete_id.required' =>'El campo paquete es requerido',
            'cantidad_adultos.required' => 'El campo cantidad_adultos es requerido',
            'cantidad_ninos.required' => 'El campo cantidad_ninos es requerido',
            'subtotal.required' => 'El campo subtotal es requerido',
            'total.required' => 'El campo total es requerido',
            'fecha.required' => 'El campo fecha es requerido',
            'user_id.required' => 'El campo user_id es requerido',
            'status.in' => 'El status debe ser '. Venta::RESERVADO.' o '. Venta::CANCELADO
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear venta',
                'errors' => $validator->errors()
            ],400);
        } 

        if (!$request->status) {
            $request['status'] = Venta::RESERVADO;
        }else{
            $campos['status'] = $request->role;
        }


        $venta = Venta::create($request->all());

        return response()->json([
            'ok' => true,
            'venta' => $venta,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();

        return response()->json([
            'ok' => true,
            'venta' => $venta
        ]);
    }
}
