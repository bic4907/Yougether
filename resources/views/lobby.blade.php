@extends('layout.default')

@section('content')

    @include('room-create')

    <div style="float:right;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createroom-modal">
            방 만들기
        </button>
    </div>
{{--    <div id="room-cards"class="container py-5 my-1">--}}

{{--        @for($i=0;$i<sizeof($room_info);$i++)--}}
{{--            <a href="{{route('room.enter', ['room_id' => $room_info[$i]->id])}}">--}}
{{--                <div class="room-card d-inline-block my-1 rounded col-5 ml-1 border border-light py-3">--}}
{{--                    <div class="container row">--}}
{{--                        <div class="col-auto">--}}
{{--                            <img src="http://placehold.it/100x100" class="rounded-circle">--}}

{{--                        </div>--}}
{{--                        <div class="col">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col">--}}
{{--                                    <h6 class="lead" style="font-size:20px;font-weight:bold;">{{$room_info[$i]->title}}</h6>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col">--}}
{{--                                    <marquee><h6 class="lead" style="font-size:15px;">{{$room_info[$i]->videoTitle == Null ? '재생되고 있는 동영상이 없습니다.' : $room_info[$i]->videoTitle}}</h6></marquee>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col">--}}
{{--                                    <h6 class="lead" style="font-size:15px;"><i class="fas fa-users mr-2"></i>{{$admission[$i]}}</h6>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </a>--}}
{{--        @endfor--}}
{{--    </div>--}}

    <div id="room-element"class="container py-5 my-1">
        <div id="room-cards">
                <div v-for="room in room_list" class="room-card d-inline-block my-1 rounded col-5 ml-1 py-3">
                    <a :href="'/room/'+ room.room_id">
                        <div class="container row">
                            <div class="col-auto">
                                <div :style="{ backgroundImage: 'url(\'' + room.thumbnail + '\')' }" class="thumb rounded"></div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="lead" style="font-size:20px;font-weight:bold;">
                                            @{{ room.title }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <marquee>
                                            <h6 class="lead" style="font-size:15px;">
                                                @{{ room.videoTitle ? room.videoTitle : '재생중인 동영상이 없습니다' }}
                                            </h6>
                                        </marquee>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h6 class="lead" style="font-size:15px;"><i class="fas fa-users mr-2"></i>
                                            @{{ room.admission }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </a>
        </div>
    </div>

    <script>
        var GET_ROOM = '{{ route('room.get') }}'
        var CURRENT_ROOMS = [ {{$room_info}} ];
        var room_element = new Vue({
            el: '#room-element',
            data: {
                room_list: [],
            },
            mounted: function() {
                // 첫 리스트 자동로딩
                var self = this
                $.each(CURRENT_ROOMS, function(idx, item) {
                    self.getRoom(item)
                })
            },
            methods: {
                getRoom: function (room_id) {
                    var self = this
                    $.ajax({
                        method: "GET",
                        url: GET_ROOM,
                        type: 'json',
                        data: {room_id: room_id},
                        success: function (data) {


                            data['thumbnail'] = decodeURIComponent(data['thumbnail'])


                            self.room_list.push(data)
                        },
                        error: function () {
                        }
                    });
                }
            }
        })

        $(window).ready( function() {
            var time = 3
            setInterval( function() {
                time--;
                $('#time').html(time);
                if (time === 0) {
                    location.reload();
                }
            }, 1000 );

        });
    </script>
@endsection
