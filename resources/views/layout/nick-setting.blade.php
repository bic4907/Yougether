<div class="modal fade" id="nick-setting-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <input id="nicksetting__nickname" type="text" class="form-control" placeholder="닉네임을 입력해주세요" minlength="2" maxlength="10">
                    <small class="form-text text-muted">10자 이내로 입력해주세요.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                <button id="nicksetting__submit" type="button" class="btn btn-primary">수정</button>
            </div>
        </div>
    </div>
</div>
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
