<div class="modal fade" id="createroom-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">닉네임 수정</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="room_name">닉네임</label>
                    <input id="createroom__roomname" type="text" class="form-control" placeholder="닉네임을 입력해주세요" maxlength="10">
                    <small class="form-text text-muted">10자 이내로 입력해주세요.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                <button id="createroom__submit" type="button" class="btn btn-primary">변경</button>
            </div>
        </div>
    </div>
</div>