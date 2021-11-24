@extends('layouts.app')
@section('content')
<div class="container">

@if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible" role="alert">
{{Session::get('mensaje')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


<a href="{{url('empleado/create')}}" class="btn btn-success">Registrar nuevo empleado</a>
<br/>
<br/>

<table class="table table-light">
    <thead class>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Foto</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($empleados as $empleado)
        <tr>
            <td>{{$empleado->id}}</td>
            <td>{{$empleado->nombre}}</td>
            <td>{{$empleado->apellido}}</td>
            <td>{{$empleado->correo}}</td>
            
            <td>
                <img class="img-thumbnail img-fluid" src="{{asset('storage'.'/'.$empleado->foto)}}" width="100" alt="">
            </td>

            <td>
            <a href="{{url('/empleado/'.$empleado->id.'/edit')}}" class="btn btn-warning">
                Editar
            </a>
            
             | 
               
            <form action="{{url('/empleado/'.$empleado->id)}}" class="d-inline" method="post">
            @csrf
            {{method_field('DELETE')}}
            <input class="btn btn-danger" type="submit" onclick="return confirm('¿Quieres borrar?')"
             value="borrar">

            </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!!$empleados->links() !!}
</div>
@endsection