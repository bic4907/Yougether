<script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>
<div id="add-video-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">동영상 추가</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="search-bar">
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="동영상 URL이나 검색키워드 입력" v-model="search_keyword">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary" @click="executeSearch">검색</button>
                        </div>
                    </div>
                </div>
                <div class="search-results">
                    <div v-if="search_result.length==0" class="my-5 py-5 text-center lead">
                        검색결과가 없습니다
                    </div>
                    <div v-if="search_result.length!=0" class="my-1">
                        <ul>
                            <li v-for="item in search_result" class="item">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="img rounded" :style="{ backgroundImage: 'url(\'' + item.snippet.thumbnails.default.url + '\')' }"></div>
                                    </div>

                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <h6>@{{ item.snippet.title }}</h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <p class="desc lead" style="font-size:0.95em;">@{{ item.snippet.description }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <span><i class="far fa-eye"></i> @{{ item.statistics.viewCount }}</span>
                                                <span class="ml-3"><i class="far fa-thumbs-up"></i> @{{ item.statistics.likeCount }}</span>
                                                <span class="ml-3"><i class="far fa-thumbs-down"></i> @{{ item.statistics.dislikeCount }}</span>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-auto">
                                        <div class="add-video mt-5">
                                            <button type="button" class="btn btn-outline-primary" @click="videoAddSignal(item.id)">추가</button>
                                        </div>
                                    </div>
                                </div>


                            </li>
                        </ul>
                    </div>
                    <div v-if="search_nextPageToken != ''" class="getMore py-1 text-center lead" @click="appendNextPage">
                        <i class="fas fa-chevron-down mr-3"></i>더 보기
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<script>
    var addVideoApp = new Vue({
        el: '#add-video-modal',
        data: {
            $modal: null,
            search_result: [],
            search_keyword: '',
            search_nextPageToken: ''
        },
        mounted: function() {
            this.$modal = $('#add-video-modal')


            // 첫 리스트 자동로딩
            //setTimeout(this.executeSearch, 2000)
        },
        methods: {
            showModal: function() { this.$modal.modal('show') },
            hideModal: function() { this.$modal.modal('hide') },
            executeSearch: function () {
                var self = this

                // 1. 먼저 검색어가 유튜브 검색 URL인지 아닌지 검사
                var flag_isURL = false;
                var parsedVideoId = null

                if(this.search_keyword.substr(0, 32) == 'https://www.youtube.com/watch?v=') {
                    flag_isURL = true
                    parsedVideoId = this.search_keyword.substr(32)

                } else if(this.search_keyword.substr(0, 17) == 'https://youtu.be/') {
                    flag_isURL = true
                    parsedVideoId = this.search_keyword.substr(18)
                }


                gapi.client.setApiKey('AIzaSyCwFrzlv37L5efs-MHHdUxT-S9fAQCVfAQ');

                this.search_result = []
                if(flag_isURL) {
                    // 유투브 동영상 URL 형태인 경우
                    this.search_nextPageToken = ''
                    this.appendResultByVideoId(parsedVideoId)
                } else {
                    // 검색어로 입력한 경우
                    gapi.client.load('youtube', 'v3', function () {
                        try {
                            var request = gapi.client.youtube.search.list({
                                q: self.search_keyword,
                                part: 'id, snippet',
                                maxResults: 3,
                            });

                            request.execute(function (response) {
                                if(response.code == 403 && response.data[0].domain == 'usageLimits') {
                                    $.amaran({content: {'message': '오늘 이용량을 초과하였습니다'}});
                                    return
                                }

                                self.search_nextPageToken = response.result.nextPageToken

                                $.each(response.result.items, function(i, e) {
                                    self.appendResultByVideoId(e.id.videoId)
                                })
                            });
                        } catch {
                            $.amaran({content: {'message': '다시 시도해주세요'}});
                        }

                    });

                }


            },

            videoAddSignal: function(videoId) {
                this.hideModal()
                roomApp.appendQueue(videoId)
            },
            appendResultByVideoId: function(videoId) {
                var self = this

                if(videoId == null) return

                gapi.client.setApiKey('AIzaSyCwFrzlv37L5efs-MHHdUxT-S9fAQCVfAQ');

                gapi.client.load('youtube', 'v3', function () {
                    try {
                        var request = gapi.client.youtube.videos.list({
                            id: videoId,
                            part: 'statistics, snippet',
                        });
                        request.execute(function (response) {
                            if(response.code == 403 && response.data[0].domain == 'usageLimits') {
                                $.amaran({content: {'message': '오늘 이용량을 초과하였습니다'}});
                                return
                            }
                            self.search_result = self.search_result.concat(response.result.items)
                        })
                    } catch {
                        $.amaran({content: {'message': '다시 시도해주세요'}});
                    }

                });
            },
            appendNextPage: function() {
                var self = this

                console.log(self.search_nextPageToken)

                gapi.client.load('youtube', 'v3', function () {
                    try {
                        var request = gapi.client.youtube.search.list({
                            q: self.search_keyword,
                            part: 'id, snippet',
                            maxResults: 3,
                            pageToken: self.search_nextPageToken
                        });

                        request.execute(function (response) {

                            self.search_nextPageToken = response.result.nextPageToken

                            $.each(response.result.items, function(i, e) {
                                self.appendResultByVideoId(e.id.videoId)
                            })
                        });
                    } catch {
                        $.amaran({content: {'message': '다시 시도해주세요'}});
                    }

                });
            }
        },
    })


</script>
