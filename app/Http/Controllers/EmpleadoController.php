<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datos['empleados'] = Empleado::paginate(1);
        return view('empleado.index', $datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('empleado.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $campos=[
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email',
            'foto' => 'required|max:10000|mimes:jpeg,png,jpg'
        ];

        $mensaje = [
            'required'=>'El :attribute es requerido',
            'foto.required'=>'La foto es requerida'
        ];

        $this->validate($request, $campos, $mensaje);

        $datosEmpleados = $request->except('_token');

        if($request->hasFile('foto')){
            $datosEmpleados['foto'] = $request->file('foto')->store('uploads', 'public');
        }

        Empleado::insert($datosEmpleados);
        //return response()->json($datosEmpleados);

        return redirect('empleado')->with('mensaje', 'Empleado agregado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $empleado = Empleado::findOrFail($id);
        return view('empleado.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $campos=[
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email',
        ];

        $mensaje = [
            'required'=>'El :attribute es requerido',
        ];

        if($request->hasFile('foto')){
            $campos = ['foto' => 'required|max:10000|mimes:jpeg,png,jpg'];
            $mensaje = ['foto.required'=>'La foto es requerida'];
        }
        $this->validate($request, $campos, $mensaje);

        //
        $datosEmpleados = $request->except('_token', '_method');

        if($request->hasFile('foto')){
            $empleado = Empleado::findOrFail($id);

            $urlFoto = trim($empleado->foto);
            Storage::delete('public/'.$urlFoto);

            $datosEmpleados['foto'] = $request->file('foto')->store('uploads', 'public');
        }

        Empleado::where('id', '=', $id)->update($datosEmpleados);

        $empleado = Empleado::findOrFail($id);
        //return view('empleado.edit', compact('empleado'));
        return redirect('empleado')->with('mensaje', 'Empleado Modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $empleado = Empleado::findOrFail($id);
        $urlFoto = trim($empleado->foto);

        if(Storage::delete('public/'.$urlFoto))
        {
            Empleado::destroy($id);
        }
        
        return redirect('empleado')->with('mensaje', 'Empleado borrado');
    }
}
