@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12" id="app">
            <router-link to="/home">Home</router-link>|
            <router-link to="/contactos">contactos</router-link>

            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <router-view/>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
