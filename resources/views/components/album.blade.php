<div class="custom-card col-md-3 col-sm-12 mt-3">
    <div class="image">
        <img src="{{ $album->first_picture }}" alt="">
    </div>
    <div class="details d-flex align-items-center justify-content-between mt-1">
        @php
            $string = $album->name;
        @endphp
        <p title="{{ $string }}" class="m-0">
            <a href="{{ route('album.edit', $album->id) }}">{{ (strlen($string) >= 15)? substr($string, 0 , 15) : $string }}</a>
        </p>
        <button type="button" class="btn btn-s btn-secondary" data-toggle="modal" data-target="#modal-{{ $album->id }}">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
  
        <!-- Modal -->
        <div class="modal fade" id="modal-{{ $album->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle-{{ $album->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle-{{ $album->id }}">{{ $album->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-center mt-2">
                            <form method="POST" action="{{ route('album.destroy', $album->id) }}">
                                {{ csrf_field() }}
                                @method('DELETE')
                                <input type="hidden" name="type" value="all">
                                <button class="btn btn-danger btn-width">{{ __('messages.delete_album') }}</button>
                            </form>
                        </div>
                        @if (count($album->pictures) > 0)
                        <div class="d-flex justify-content-center mt-2">
                            <form method="POST" action="{{ route('album.destroy', $album->id) }}">
                                {{ csrf_field() }}
                                @method('DELETE')
                                <input type="hidden" name="type" value="images">
                                <button class="btn btn-danger btn-width">{{ __('messages.delete_all') }}</button>
                            </form>
                        </div>
                        @endif
                        @if (count($albums->where('id','!=',$album->id)) > 0 && count($album->pictures) > 0)
                        <div class="d-flex justify-content-center mt-2">
                            <form method="POST" action="{{ route('album.destroy', $album->id) }}">
                                {{ csrf_field() }}
                                @method('DELETE')
                                <input type="hidden" name="type" value="all">
                            </form>
                            <button type="button" class="btn btn-secondary btn-width" data-toggle="modal" data-target="#modal-move-{{ $album->id }}">
                                {{ __('messages.move_all') }}
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-move-{{ $album->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle-move-{{ $album->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle-move-{{ $album->id }}">{{ __('messages.move') }} ({{ $album->name }})</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body pb-4">
            <form method="POST" action="{{ route('album.move', $album->id) }}">
                {{ csrf_field() }}
                <div class="d-flex align-items-center custom-form-div">
                    <label for="select-{{ $album->id }}">{{ __('messages.move_to') }}</label>
                    <select required class="form-control" name="move_to_id">
                        @if (count($albums->where('id','!=',$album->id)) == 0)
                            <option disabled selected value="">{{ __('messages.no_data') }}</option>
                        @endif
                        @foreach ($albums->where('id','!=',$album->id) as $album)
                            <option value="{{ $album->id }}">{{ $album->name }}</option>
                        @endforeach
                    </select>
                </div>  
                <div class="d-flex align-items-center justify-content-center">
                    <button class="btn btn-success mt-3">
                        Save
                    </button>
                </div>    
            </form> 
        </div>
        </div>
    </div>
</div>