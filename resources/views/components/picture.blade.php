<div class="custom-card col-md-3 col-sm-12 mt-3">
    <div class="image">
        <img src="{{ $picture->picture_path }}" alt="">
    </div>
    <div class="details d-flex align-items-center justify-content-between mt-1">
        @php
            $string = $picture->name;
        @endphp
        <p title="{{ $string }}" class="m-0">
            <a href="{{ $picture->picture_path }}">{{ (strlen($string) >= 11)? substr($string, 0 , 11) : $string }}</a>
        </p>
        <button type="button" class="btn btn-s btn-danger" data-toggle="modal" data-target="#modal-{{ $picture->id }}">
            <i class="fa-solid fa-trash-can"></i>
        </button>
  
        <!-- Modal -->
        <div class="modal fade" id="modal-{{ $picture->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle-{{ $picture->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-{{ $picture->id }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-center mt-2">
                            <form method="POST" action="{{ route('picture.destroy', $picture->id) }}">
                                {{ csrf_field() }}
                                @method('DELETE')
                                <h5>{{ __('messages.are you sure delete') }} {{ $picture->name }}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-danger">{{ __('messages.yes') }}</button>
                                    <button class="btn btn-secondary" type="button" onclick="$('#close-{{ $picture->id }}').click()">
                                        {{ __('messages.no') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>