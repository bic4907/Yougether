@extends('layout.default')



@section('content')

    @include('room-create')
    <div style="float:right;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createroom-modal">
            방 만들기
        </button>
    </div>
    <div id="room-cards"class="container py-5 my-1">

        @for ($i = 0; $i < 10; $i++)
            <a href="#">
            <div class="room-card d-inline-block my-1 rounded col-5 ml-1 border border-light py-3">
                <div class="container row">
                    <div class="col-auto">
                        <img src="http://placehold.it/100x100" class="rounded-circle">

                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <h6 class="lead" style="font-size:20px;font-weight:bold;">ROOM NAME</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h6 class="lead" style="font-size:20px;">VIDEO NAME</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h6 class="lead" style="font-size:20px;">Persons</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        @endfor
    </div>
@endsection