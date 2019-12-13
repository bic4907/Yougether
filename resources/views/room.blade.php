@extends('layout.default')
@section('content')


<div id="room-container" class="">
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col py-3">
                    <span id="room-name" class="lead text-white">@{{ room_title }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div id="yt-player" class="pb-3"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ route('room.enter', ['room_id'=>$room->id]) }}" readonly>

                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" id="button-copyurl">URL 복사</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-auto">
            <div class="row no-gutters">
                <div class="col">
                    <div id="video-queue" class="" style="margin-right: -1px;">
                        <ul class="video-items">
                            <li v-for="item in video_queue" class="item">
                                <div class="row no-gutters" data-toggle="tooltip" data-placement="top" v-bind:title="item.videoTitle">
                                    <div class="col-auto">
                                        <div class="img rounded" :style="{ backgroundImage: 'url(\'' + item.thumbnail + '\')' }"></div>
                                    </div>
                                    <div class="col">
                                        <div class="title px-1 py-1">@{{ item.videoTitle }}</div>
                                        <div class="title px-1 py-1">@{{ parseInt(item.duration / 60) }}m@{{ item.duration % 60 }}s</div>
                                        <div class="title px-1 py-1" v-on:click="deleteVideo(item.id)">X</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="video-control" style="background-color:transparent">
                            <center>
                                <button type="button" class="btn btn-primary addBtn " onclick="addVideoApp.showModal()">비디오 추가</button>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div id="chat-window" class="">
                        <ul class="chat-items">
                            <li v-for="chat in chat_logs" class="item">
                                <span style="font-weight: bold">@{{ chat.name }}</span>
                                <span>
                                    @{{ chat.content }}
                                </span>
                            </li>
                        </ul>
                        <div class="chat-input">
                            <input type="text" class="form-control" placeholder="채팅을 입력하세요" v-on:keyup.13="chatSubmit" v-model="chat_input">
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>



</div>
<script>

    var ROOM_ID = '{{ $room->id }}'
    var URL_CHAT = '{{ route('room.chat.send', ['room_id'=> $room->id]) }}'
    var URL_SYNC = '{{ route('room.chat.sync', ['room_id'=> $room->id]) }}'
    var VIDEO_ADD = '{{ route('room.video.add', ['room_id'=> $room->id]) }}'
    var VIDEO_LIST = '{{ route('room.video.list', ['room_id'=> $room->id]) }}'

    var roomApp = new Vue({
        el: '#room-container',
        data: {
            room_title: '{{ $room->title }}',
            chat_input: '',
            chat_logs: [],
            video_queue: [],
            player: null,

            is_host: '{{ $isHost ? 'true' :' false' }}',
            current_videoId: '{{ $room->current_videoId }}',
            current_time: '{{ $room->current_time }}',

            videoSyncTimer: null,
        },
        mounted: function() {
            var self = this
            if (typeof Echo != "undefined") {
                var echo = Echo.private('room.' + ROOM_ID)
                    .listen('MessageSentEvent', function(e) {
                        self.chat_logs.push({'name':e.nickname, 'content':e.text})
                    })
                    .listen('VideoSyncEvent', function(e) {

                        if(self.player.getPlayerState() == 5 || self.player.getPlayerState() == -1) { //시작되지 않음
                            self.player.loadVideoById({
                                videoId:e.videoId,
                                startSeconds:e.videoTime,
                                suggestedQuality:'auto'})
                        } else {
                            if(self.current_videoId != e.videoId) {
                                self.player.loadVideoById({
                                    videoId:e.videoId,
                                    startSeconds:e.videoTime,
                                    suggestedQuality:'auto'})
                            } else {
                                if(!self.is_host && Math.abs(self.player.getCurrentTime() - e.videoTime) >= 2) {
                                    self.player.seekTo(e.videoTime)
                                }
                            }
                        }

                        self.current_videoId = e.videoId
                        self.current_time = e.videoTime

                    })
                    .listen('VideoAddEvent', function(data) {
                        $.each(data.videoList, function(i, e) {
                            data.videoList[i]['thumbnail'] = decodeURIComponent(e['thumbnail'])
                        })
                        self.video_queue = data.videoList
                    })
                    .listen('RoomInfoChangeEvent', function(data) {
                        var current_host = data.roomInfo.host_nickname

                        if(authApp.nickname == current_host) {
                            self.is_host = true
                            self.startVideoSyncHost()
                        } else {
                            self.is_host = false
                            self.stopVideoSyncHost()
                        }
                    })

            }

            if(this.is_host) {
                this.startVideoSyncHost()
            }

            this.requestQueue()

        },
        methods: {
            chatSubmit: function() {
                if(this.chat_input == '') return;
                var _text = this.chat_input;

                $.ajax({
                    method: "POST",
                    url: URL_CHAT,
                    data: {
                        text : _text
                    }
                });

                // 채팅전송 완료
                this.chat_input = '';

            },
            startVideoSyncHost: function() {
                var self = this
                if(this.videoSyncTimer != null) return
                this.videoSyncTimer = setInterval(self.sendVideoSyncInfo, 1000)
            },
            stopVideoSyncHost: function() {
                if(this.videoSyncTimer == null) return;
                clearInterval(this.videoSyncTimer)
            },
            sendVideoSyncInfo: function() {
                var self = this;

                if(self.current_videoId == null || self.current_videoId == '') return;
                if(!self.is_host) return;

                if(self.is_host && self.player.getCurrentTime() - self.current_time > 5)
                {
                    '{{ route('room.log.add', ['room_id' => $room->id]) }}'
                }
                self.current_time = self.player.getCurrentTime();
                // 만약 비디오가 거의다 재생되가면 동기화하지 않음

                if(self.player.getDuration() - self.current_time  <= 3) return;
                $.ajax({
                    method: "POST",
                    url: URL_SYNC,
                    data: { videoId : self.current_videoId, videoTime: self.current_time }
                });
            },
            onPlayerReady: function(event) {
                var self = this
                if(self.current_videoId != '') {
                    self.player.loadVideoById({
                        videoId: self.current_videoId,
                        startSeconds: self.current_time,
                        suggestedQuality: 'auto'
                    })
                    this.player.playVideo()
                }
            },
            appendQueue: function(videoId) {

                $.ajax({
                    method: "POST",
                    url: VIDEO_ADD,
                    type: 'json',
                    data: { video_id : videoId },
                    success: function(data) {
                        $.amaran({content:{'message':'동영상을 추가 중 입니다'}});
                    },
                    error: function() {
                        $.amaran({content:{'message':'동영상 추가요청 실패'}});
                    }

                 });
            },
            deleteVideo: function(queueItemId) {
                console.log(queueItemId);
                $.ajax({
                    method: "DELETE",
                    url: VIDEO_ADD,
                    type: 'json',
                    data: { queueItemId : queueItemId },
                    success: function(data) {
                        $.amaran({content:{'message':'동영상을 삭제중 입니다'}});
                    },
                    error: function() {
                        $.amaran({content:{'message':'동영상을 삭제할 권한이 없습니다'}});
                    }

                });
            },
             requestQueue: function() {
                var self = this
                 $.ajax({
                     method: "GET",
                     url: VIDEO_LIST,
                     type: 'json',
                     success: function(data) {

                         $.each(data, function(i, e) {
                             data[i]['thumbnail'] = decodeURIComponent(e['thumbnail'])
                         })
                         self.video_queue = data
                         console.log('재생목록을 가져왔습니다')
                     },
                     error: function() {
                         console.log('재생목록을 가져오기 실패')
                     }

                 });

             },
        }
    })


    // 2. This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.

    function onYouTubeIframeAPIReady() {
        roomApp.player = new YT.Player('yt-player', {
            width: '100%',
            height: '482px;',
            events: {
                'onReady': roomApp.onPlayerReady,
                //'onStateChange': app.onPlayerStatusChange
            }
        });
    }

    function addVideoMaually(videoId) {
        var videoId = videoId ? videoId : 'l502xg11uNM'
        roomApp.appendQueue(videoId)
    }



</script>

@include('add-video-modal')

@endsection
