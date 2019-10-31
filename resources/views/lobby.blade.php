@extends('layout.default')
@section('content')

    <div id="room-cards"class="container py-5 my-1">

        @for ($i = 0; $i < 10; $i++)
            <div class="room-card d-inline-block my-3 rounded col-5 ml-1 border border-light py-3 ml-5">
                <div class="container row">
                    <div class="col-auto">
                        <img src="http://placehold.it/100x100" class="rounded-circle">

                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                ㅇㅇㅇㅇㅇ
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                ㅇㄹㅇㄹㅇㄹㄹㅇ
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                ㅇㄹㅇㄹㄹㅇㄹ
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endfor
    </div>
@endsection