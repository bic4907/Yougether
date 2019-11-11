@extends('layout.default')
@section('content')


<div id="room-container" class="container border border-white rounded">
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col py-3">
                    <span id="room-name" class="lead px-3">@{{ room_title }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div id="yt-player" class="pb-3"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="ml-3">공유하기</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ route('room.enter', ['room_id'=>$room->id]) }}" readonly>

                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="button-copyurl">URL 복사</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-auto">
            <div class="row no-gutters">
                <div class="col">
                    <div id="video-queue" class="border" style="margin-right: -1px;">

                    </div>
                </div>
                <div class="col">
                    <div id="chat-window" class="border">
                        <ul class="chat-items">
                            <li v-for="chat in chat_logs" class="item">
                                <span style="font-weight: bold">@{{ chat.name }}</span>
                                <span>
                                    @{{ chat.content }}
                                </span>
                            </li>
                        </ul>
                        <div class="chat-input">
                            <input type="text" placeholder="채팅을 입력하세요" v-on:keyup.13="chatSubmit" v-model="chat_input">
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>



</div>
<script>
    /*
        var done = false;
        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING && !done) {
                setTimeout(stopVideo, 6000);
                done = true;
            }
        }
        function stopVideo() {
            player.stopVideo();
        }
    */
    var ROOM_ID = '{{ $room->id }}'
    var URL_CHAT = '{{ route('room.chat.send', ['room_id'=> $room->id]) }}'
    var URL_SYNC = '{{ route('room.chat.sync', ['room_id'=> $room->id]) }}'

    var roomApp = new Vue({
        el: '#room-container',
        data: {
            room_title: '{{ $room->title }}',
            chat_input: '',
            chat_logs: [
            ],
            player: null,

            is_host: {{ $isHost ? 'true' :' false' }},
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
                        if(!self.is_host) {
                            self.player.seekTo(e.videoTime)
                        }
                    })
            }

            if(this.is_host) {
                this.startVideoSyncHost()
            }

        },
        methods: {
            chatSubmit: function() {
                if(this.chat_input == '') return;
                var _user_id = 1; //user_id 변경해야함
                var _text = this.chat_input;

                $.ajax({
                    method: "POST",
                    url: URL_CHAT,
                    data: {
                        user_id : _user_id,
                        text : _text
                    }
                });

                // 채팅전송 완료
                this.chat_input = '';

            },
            startVideoSyncHost: function() {
                var self = this
                this.videoSyncTimer = setInterval(self.sendVideoSyncInfo, 1000)
            },
            stopVideoSyncHost: function() {
                clearInterval(this.videoSyncTimer)
            },
            sendVideoSyncInfo: function() {
                var self = this

                self.current_time = self.player.getCurrentTime()

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
            height: '430px;',
            events: {
                'onReady': roomApp.onPlayerReady,
                //'onStateChange': app.onPlayerStatusChange
            }
        });
    }


</script>
@endsection
