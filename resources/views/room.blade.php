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
                        <input type="text" class="form-control" value="룸 URL" readonly>

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


    var URL_CHAT = '{{ route('room.chat.send', ['room_id'=> '1']) }}'; //room_id 변경해야함

    var app = new Vue({
        el: '#room-container',
        data: {
            room_title: '안녕하세요 Vue!',
            chat_input: '',
            chat_logs: [
                {name:'이름', content: '채팅1'},
                {name:'이름', content: '채팅2'}
            ],
            player: null
        },
        mounted: function() {

        },
        methods: {
            chatSubmit: function() {
                if(this.chat_input == '') return;
                var _user_id = 1; //user_id 변경해야함
                var _text = this.chat_input;

                $.ajax({
                    type: "POST",
                    url: URL_CHAT,
                    data: {
                        user_id : _user_id,
                        text : _text
                    },
                    success: function(data) {
                        console.log('채팅 전송됨', data)
                    },
                    error: function(data) {
                        
                    }
                });

                // 채팅전송 완료
                this.chat_input = '';

            },
            onPlayerReady: function(event) {
                consoe.log('onPlayerReady')
                this.player.loadVideoById({
                    videoId:'l502xg11uNM',
                    startSeconds:5,
                    endSeconds:10,
                    suggestedQuality:'hd720'
                })
                this.player.playVideo()

            }
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
        console.log('API ready')
        app.player = new YT.Player('yt-player', {
            width: '100%',
            height: '430px;',
            videoId: null,
            events: {
                'onReady': onPlayerReady,
                'onStateChange': app.onPlayerStatusChange
            }
        });
    }

    function onPlayerReady(event) {
        console.log(app)
        app.onPlayerReady(event)
    }

</script>
@endsection