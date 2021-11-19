<?php

namespace App\Http\Controllers\Galeria;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GaleriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galerias = Galeria::all();

        return response()->json([
            'ok' => true,
            'galerias' =>$galerias
        ]);
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
            'imagen' => ['required','image'],
            'paquete_id' => ['required']
        ],[
            'imagen.required' => 'La imagen es requerida',
            'imagen.image' => 'El campo imagen deber ser de tipo image',
            'paquete_id.required' => 'El campo paquete_id  es requerido'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear paquete',
                'errors' => $validator->errors()
            ],400);
        }

        $paquete = Paquete::with(['empresa'])->find($request->paquete_id);

        $data = $request->all();

        $data['imagen'] = $request->imagen->store($paquete->empresa->nombre.'/'.$paquete->nombre);

        $galeria = Galeria::create($data);

        return response()->json([
            'ok' => true,
            'galeria' => $galeria,
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
    public function update(Request $request, Galeria $galeria)
    {
        $validator = Validator::make($request->all(),[
            'paquete_id' => ['required']
        ],[
            'paquete_id.required' => 'El campo paquete_id  es requerido'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear paquete',
                'errors' => $validator->errors()
            ],400);
        }

        $paquete = Paquete::with(['empresa'])->find($request->paquete_id);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            Storage::delete($galeria->portada);
            $data['imagen'] = $request->imagen->store($paquete->empresa->nombre.'/'.$paquete->nombre);
        }

        $galeria->update($data);

        return response()->json([
            'ok' => true,
            'galeria' => $galeria
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Galeria $galeria)
    {
        Storage::delete($galeria->imagen);
        $galeria->delete();

        return response()->json([
            'ok' => true,
            'galeria' => $galeria
        ]); 
    }
}
