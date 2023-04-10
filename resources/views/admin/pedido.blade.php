@extends('adminlte::page')
@section('title', 'Pedidos')

@section('content_header')
    <h1>Pedidos</h1>
@stop

@section('content')
    <div id="app">
        <pedido-component :user="{{Auth::user() != null ? Auth::user() : json_encode($user= array())}}"></pedido-component>
        <!--form-component :user ="Auth::user() !=null ? Auth::user() : json_encode($user =array())}}" ></form-component-->
    </div>
@stop

@section('css')
    <!--link rel="stylesheet" href="/css/admin_custom.css"-->
    @vite('resources/css/app.css')
@stop

@section('js')
    <!--script> console.log('Hi!'); </script-->
    @vite('resources/js/app.js')
@stop