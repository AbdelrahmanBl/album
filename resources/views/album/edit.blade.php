@extends('layouts.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    > <a href="{{ route('home') }}">{{ __('messages.Albums') }}</a>  > <a href="#">{{ __('messages.edit') }}</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="d-flex flex-wrap align-items-center">
                        <div  class="w-100">
                            <ul class="alert alert-danger d-none" id="errors">
                            </ul>
                            <div class="custom-form-div d-flex align-items-center">
                                <label for="name">{{ __('messages.album_name') }}</label>
                                <input name="album_name" id="name" class="form-control" value="{{ $album->name }}" type="text">
                            </div>

                            <form action="{{ route('album.upload') }}" class="dropzone mt-4" id="my-dropzone" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="file" name="file" multiple class="d-none" >
                            </form>

                            <div class="custom-form-div d-flex justify-content-center">
                                <button type="button" onclick="editAlbum(this)" class="btn btn-success mt-4">
                                    {{ __('messages.edit') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex flex-wrap align-items-center mt-3">
                        @foreach ($album->pictures as $picture)
                            @include('components.picture')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        let uploadUrl  = "{{ route('album.upload') }}"
        let updateUrl  = "{{ route('album.update', $album->id) }}"
        let CSRF_TOKEN = $("meta[name=csrf-token]").attr("content")
        let myPaths = []

        let myDropzone = $("form#my-dropzone").dropzone()
        Dropzone.options.myDropzone = {
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            init: function() {
                this.on("complete", function(file) { 
                    let res = JSON.parse(file.xhr.response)
                    myPaths.push(res.path)
                });
            }
        };

        function editAlbum(_this) {
            _this.disabled = true
            let data = {
                        "_token": CSRF_TOKEN,
                        "_method": "PATCH"
                       }
            data.name = $("input[name=album_name]").val()
            data.paths = myPaths

            $.ajax({
                url: updateUrl,
                type: "POST",
                data: data
            }).then((res) => {
                if(res.is_error) {
                    let errorHtml = ""
                    $("#errors").toggleClass('d-none')
                    if(res.message.name) {
                        res.message.name.forEach(msg => {
                            errorHtml += `
                                <li>${msg}</li>
                            `
                        })
                    }
                    
                    $("#errors").append(errorHtml)
                }else {
                    location.href = res.url
                }
            }).always(() => {
                _this.disabled = false
            })
        }
    </script>
@endsection