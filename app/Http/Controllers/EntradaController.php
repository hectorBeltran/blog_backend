<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Exception;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resp = array();
        try {
            // obtiene la información
            $entradas = Entrada::all();

            foreach($entradas as $entrada) {
                if(strlen($entrada->contenido) > 70)
                    $entrada->contenido = substr($entrada->contenido, 0, 70) . '...';
            }

            $resp['resultado'] = 'ok';
            $resp['entradas'] = $entradas;            
        }
        catch (Exception $e) {
            $resp['resultado'] = 'fail';
            $resp['mensaje'] = $e->getMessage();
        }

        return response()->json($resp);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = array();
        try {
            $validacion = EntradaController::validaciones($request);

            if($validacion['resultado'] != 'ok') {
                $resp['resultado'] = 'fail';
                $resp['mensaje'] = $validacion['mensaje'];
            }
            else {
                $entrada = new Entrada;
                $entrada->titulo = $request->titulo;
                $entrada->autor = $request->autor;
                $entrada->fechaPublicacion = $request->fechaPublicacion;
                $entrada->contenido = $request->contenido;

                $entrada->save();
                $resp['resultado'] = 'ok';
                $resp['mensaje'] = 'Información almacenada';
            }
        }
        catch (Exception $e) {
            $resp['resultado'] = 'fail';
            $resp['mensaje'] = 'No se puedo guardar la información, error: ' . $e->getMessage();
        }

        return response()->json($resp);
    }

    /**
     * Display the specified resource.
     */
    public function show($entrada)
    {
        $resp = array();
        try {
            $registro = Entrada::where('idEntrada', $entrada)->first();

            $resp['resultado'] = 'ok';
            $resp['entrada'] = $registro;
        }
        catch(Exception $e) {
            $resp['resultado'] = 'fail';
            $resp['mensaje'] = 'No se pudo consultar el registro, error: ' . $e->getMessage();
        }

        return response()->json($resp);
    }

    /**
     * Filtra la información por título o autor o contenido
     */
    public function find(Request $request)
    {
        $resp = array();
        try {
            if($request->titulo != '')
                $registro = Entrada::where('titulo', 'like', '%' . $request->titulo . '%')->get();
            else if($request->autor != '')
                $registro = Entrada::where('autor', 'like', '%' . $request->autor . '%')->get();
            else
                $registro = Entrada::where('contenido', 'like', '%' . $request->contenido . '%')->get();

            $resp['resultado'] = 'ok';
            $resp['datos'] = $registro;
        }
        catch(Exception $e) {
            $resp['resultado'] = 'fail';
            $resp['mensaje'] = 'No se pudo consultar el registro, error: ' . $e->getMessage();
        }

        return response()->json($resp);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrada $entrada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entrada $entrada)
    {
        try {
            $validacion = EntradaController::validaciones($request);

            if($validacion['resultado'] != 'ok') {
                $resp['resultado'] = 'fail';
                $resp['mensaje'] = $validacion['mensaje'];
            }
            else {
                $entrada->titulo = $request->titulo;
                $entrada->autor = $request->autor;
                $entrada->fechaPublicacion = $request->fechaPublicacion;
                $entrada->contenido = $request->contenido;

                $entrada->save();
                $resp['resultado'] = 'ok';
                $resp['mensaje'] = 'Información actualizada';
            }
        }
        catch(Exception $e) {
            $resp['resultado'] = 'fail';
            $resp['mensaje'] = 'No se pudo actualizar el registro, error: ' . $e->getMessage();
        }

        return response()->json($resp);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrada $entrada)
    {
        //
    }

    /**
     * Valida la longitud de los datos a guardar
     */
    private function validaciones($request)
    {
        $resp = array();
        $resp['resultado'] = 'fail';
        
        if(strlen($request->titulo) == 0 || strlen($request->titulo) > 100)
            $resp['mensaje'] = 'El título debe tener entre 1 y 100 carácteres';
        else if(strlen($request->autor) == 0 || strlen($request->autor) > 100)
            $resp['mensaje'] = 'El autor debe tener entre 1 y 100 carácteres';
        else if(strlen($request->fechaPublicacion) != 10)
            $resp['mensaje'] = 'Es necesario la fecha de publicación';
        else if(strlen($request->contenido) == 0 || strlen($request->contenido) > 500)
            $resp['mensaje'] = 'El contenido debe tener entre 1 y 500 carácteres';
        else {
            $resp['resultado'] = 'ok';
            $resp['mensaje'] = '';   
        }

        return $resp;
    }
}
