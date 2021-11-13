<?php

namespace App\Http\Controllers\Paquete;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Paquete;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaqueteController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orden = 'desc';
        if(request()->has('precio')){
            $orden = request()->precio == 'mayor' ? 'desc' : 'asc';
        }
        $paquetes = Paquete::with('empresa')
        ->orderBy('precio_adulto',$orden)
        ->nombre(request()->get('nombre'))
        ->empresa_id(request()->get('filtro'))
        ->paginate(9);

        return response()->json([
            'ok' => true,
            'paquetes' => $paquetes
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
            'nombre' => ['required'],
            'empresa_id' => ['required'],
            'precio_adulto' => ['required'],
            'precio_nino' => ['required'],
            'informacion' => ['required'],
            'caracteristicas' => ['required'],
            'portada' => ['required'],
            'empresa_id' => ['required'],
        ], [
            'nombre.required' => 'El nombre es requerido',
            'descripcion.required' =>'La descripcion es requerida',
            'precio_adulto.required' => 'El precio de adulto es requerido',
            'precio_nino.required' => 'El precio de niño es requerido',
            'informacion.required' => 'La informacion es requerida en formado json',
            'caracteristicas.required' => 'Las caracteristicas son requeridas en formato json',
            'portada.required' => 'La portada es requerida',
            'empresa_id.required' => 'La empresa es requerida',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear paquete',
                'errors' => $validator->errors()
            ],400);
        }

        $data = $request->all();

        $data['portada'] = $request->portada->store('portadas');

        $paquete = Paquete::create($data);

        return response()->json([
            'ok' => true,
            'paquete' => $paquete,
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
        $paquete = Paquete::with([
            'galerias',
            'empresa'
        ])->find($id);

        return response()->json([
            'ok' => true,
            'paquete' => $paquete
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paquete $paquete)
    {     

        $data = $request->all();

        if ($request->hasFile('portada')) {
            Storage::delete($paquete->portada);
            $data['portada'] = $request->portada->store('portadas');
        }

        $paquete->update($data);
        $paquete = Paquete::with(['empresa', 'galerias'])->findOrFail($paquete->id);

        return response()->json([
            'ok' => true,
            'paquete' => $paquete
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paquete $paquete)
    {
        Storage::delete($paquete->portada);
        $paquete->delete();

        return response()->json([
            'ok' => true,
            'paquete' => $paquete
        ]); 
    }
}
