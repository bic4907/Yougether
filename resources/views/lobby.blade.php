@extends('layout.default')

@section('content')

    @include('room-create')

    <div style="float:right;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createroom-modal">
            방 만들기
        </button>
    </div>

    <div id="room-element" class="container py-5 my-1">
        <div id="room-cards">
            <div v-for="room in room_list" class="room-card d-inline-block my-1 rounded col-5 ml-1 py-3">
                <a :href="'/room/'+ room.room_id">
                    <div class="container row">
                        <div class="col-auto">
                            <div :style="{ backgroundImage: 'url(\'' + room.thumbnail + '\')' }"
                                 class="thumb rounded"></div>
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
                                            <span v-bind:id="'room-videoTitle' + room.room_id">
                                            @{{ room.videoTitle ? room.videoTitle : '재생중인 동영상이 없습니다' }}
                                            </span>
                                        </h6>
                                    </marquee>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h6 class="lead" style="font-size:15px;">
                                        <i class="fas fa-users mr-2"></i>
                                        <span v-bind:id="'room-admission' + room.room_id">@{{ room.admission }}</span>
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

        roomUpdate();
        function roomUpdate() {
            var GET_ROOM = '{{ route('room.get') }}'
            var CURRENT_ROOMS = [ {{$room_info}} ];
            var room_element = new Vue({
                el: '#room-element',
                data: {
                    room_list: [],
                },
                mounted: function () {
                    // 첫 리스트 자동로딩
                    var self = this

                    $.each(CURRENT_ROOMS, function (idx, item) {
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
                                $("#room-admission" + room_id).html(data.admission);
                                $("#room-videoTitle" + room_id).html(data.videoTitle ? data.videoTitle : '재생중인 동영상이 없습니다');
                            },
                            error: function () {
                            }
                        });
                    }
                }
            })
        }

        setInterval(roomUpdate, 4400)
    </script>
@endsection
