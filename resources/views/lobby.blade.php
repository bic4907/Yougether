<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    </head>
    <html>
        <div class="container">
            <div class="container pt-4 mt-5">
                <h4 class="display-5">Yougether</h4>
                <p class="lead">유튜브 동영상 같이보기 플랫폼</p>
            </div>
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

        </div>




        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </html>
</html>