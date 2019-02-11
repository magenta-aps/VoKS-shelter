@extends('app')

@section('content')
    <div class="container-fluid">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
            </div>
        @endif
        <h3>Home sweet home</h3>
        <pre>
            {!! print_r(Auth::user()->toArray()) !!}
        </pre>
    </div>
@endsection
