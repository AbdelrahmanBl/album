@extends('layouts.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    > <a href="{{ route('home') }}">{{ __('messages.Albums') }}</a>    
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-center">
                        <button type="button" onclick="location.href = '{{ route('album.create') }}'" class="btn btn-primary ">{{ __('messages.add_new') }}</button>
                    </div>

                    @if (count($albums) > 0)
                    <div class="d-flex flex-wrap align-items-center">
                        @foreach ($albums as $album)
                            @include('components.album')
                        @endforeach
                    </div>
                    @else 
                        <div class="alert alert-danger mt-3 text-center">
                            <span>{{ __('messages.no_data') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
