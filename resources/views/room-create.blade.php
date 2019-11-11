<div class="modal fade" id="createroom-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">새로운 방 만들기</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="room_name">방 제목</label>
                    <input id="createroom__roomname" type="text" class="form-control" placeholder="방 제목을 입력해주세요" maxlength="30">
                    <small class="form-text text-muted">30자 이내로 입력해주세요.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                <button id="createroom__submit" type="button" class="btn btn-primary">입장</button>
            </div>
        </div>
    </div>
</div>
<script>
    var CREATEROOM_POST_URL = '/room';
    var flag_creating = false;

    $('#createroom-modal #createroom__submit').click(function() {
        var roomName = $('#createroom-modal #createroom__roomname').val()

        if(flag_creating) $.amaran({content:{'message':'방을 만들고 있습니다'}});

        $.ajax({
            url: CREATEROOM_POST_URL,
            method: 'post',
            data: {
                roomName: roomName
            },
            beforeSend: function() {
                flag_creating = true;
                $.amaran({content:{'message':'방을 만들고 있습니다'}});
            },
            success: function(data) {
                // 서버로 부터 만들어진 방 모델의 번호를 반환받음
                $.amaran({content:{'message':'방으로 입장합니다'}});
                console.log(data)

                $('#createroom-modal').modal('hide')
            },
            error: function() {
                $.amaran({content:{'message':'방 생성에 실패하였습니다'}});
            },
            complete: function() {
                flag_creating = false;
            }
        })
    })
</script>
