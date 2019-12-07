<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/style.css">
        <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="//cdn.jsdelivr.net/jquery.amaran/0.5.4/jquery.amaran.min.js"></script>
        <script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
        <script src="/js/jquery.mCustomScrollbar.min.js"></script>

        <script src="/js/app.js"></script>

        <link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.amaran/0.5.4/amaran.min.css">
        <link rel="stylesheet" href="/css/jquery.mCustomScrollbar.css">

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
    </head>
<body>
<div class="container">
    <div class="container pt-4 mt-5 row">

        <div class="col">
        <h1 id="app-title" class="display-5"><a href="/" class="text-white">Yougether</a></h1>
        <p class="lead text-white">유튜브 동영상 같이보기 플랫폼</p>
        </div>

        <div class="col">
            <div id="nick-setting" class="d-inline lead pt-3" style="float:right">
                <span class="text-white">@{{ nickname ? nickname : '미설정' }}</span><span @click="showModifyModal"><i class="fas fa-cog ml-2 text-white" style="cursor:pointer"></i></span>
            </div>

        </div>

    </div>

    <div class="container">
        @yield('content')
    </div>

</div>
@include('layout.nick-setting')


<script>
    // 유저 브라우저가 존재하는 것을 감지하기 위해 1초마다 서버로 생조신고를 보낸다.
    var KEEPALIVE_URL = '{{ route('user.keepalive') }}';
    var keepAliveTimer = setInterval(function() {
        $.ajax({
            type: "PUT",
            url: KEEPALIVE_URL
        });
        $('[data-toggle="tooltip"]').tooltip()
    }, 1000)

</script>


<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
