@extends('layout.default')

@section('content')

    @include('room-create')

    <div style="float:right;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createroom-modal">
            방 만들기
        </button>
    </div>
    <div id="room-cards"class="container py-5 my-1">

        @for($i=0;$i<sizeof($room_info);$i++)
            <a href="{{route('room.enter', ['room_id' => $room_info[$i]->id])}}">
                <div class="room-card d-inline-block my-1 rounded col-5 ml-1 border border-light py-3">
                    <div class="container row">
                        <div class="col-auto">
                            <img src="http://placehold.it/100x100" class="rounded-circle">

                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <h6 class="lead" style="font-size:20px;font-weight:bold;">{{$room_info[$i]->title}}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h6 class="lead" style="font-size:20px;">{{$room_info[$i]->videoTitle == Null ? '재생되고 있는 동영상이 없습니다.' : $room_info[$i]->videoTitle}}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h6 class="lead" style="font-size:20px;">{{$admission[$i]}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endfor
    </div>
@endsection
