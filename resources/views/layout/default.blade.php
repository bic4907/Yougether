<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/style.css">
        <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="//cdn.jsdelivr.net/jquery.amaran/0.5.4/jquery.amaran.min.js"></script>
        <script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
        <script src="/js/app.js"></script>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.amaran/0.5.4/amaran.min.css">

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
    </head>
<html>
<div class="container">
    <div class="container pt-4 mt-5 row">

        <div class="col">
        <h1 id="app-title" class="display-5"><a href="/">Yougether</a></h1>
        <p class="lead">유튜브 동영상 같이보기 플랫폼</p>
        </div>

        <div class="col">
            <div id="nick-setting" class="d-inline lead pt-3" style="float:right">
                <span>@{{ nickname ? nickname : '미설정' }}</span><span @click="showModifyModal"><i class="fas fa-cog ml-2" style="cursor:pointer"></i></span>
            </div>

        </div>

    </div>

    <div class="container">
        @yield('content')
    </div>

</div>
@include('layout.nick-setting')
<script>

    var NICK_URL = '{{ route('checkingSession') }}';

    var app = new Vue({
        el: '#nick-setting',
        data: {
            nickname: '로딩중',
            flag_loading: true,
        },
        mounted: function() {
            var self = this
            this.refresh()
            $('#nick-setting-modal #nicksetting__submit').click(function() {
                self.modify()
            })
        },
        methods: {
            refresh: function() {
                var self = this
                self.flag_loading = true
                $.ajax({
                    type: "GET",
                    url: NICK_URL,
                    success: function(data) {
                        if(data == '') {
                            self.nickname = ''
                            self.showModifyModal()
                        } else {
                            self.nickname = data
                        }
                        self.flag_loading = false
                    },
                    error: function(data) {

                    }
                });
            },
            modify: function() {
                console.log('modify');
                var self = this
                var nickname = $('#nick-setting-modal #nicksetting__nickname').val()
                $.ajax({
                    type: "POST",
                    url: NICK_URL,
                    data: { nickname: nickname },
                    success: function(data) {
                        self.refresh()
                        $('#nick-setting-modal').modal('hide')
                    },
                    error: function(data) {
                        $.amaran({content:{'message':'닉네임 변경 실패'}});
                    }
                });
            },
            showModifyModal: function() {
                var self = this
                $('#nick-setting-modal #nicksetting__nickname').val(self.nickname)
                $('#nick-setting-modal').modal('show')

            }
        }
    })
</script>

<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>
</html>
