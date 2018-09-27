<div style="color:gray;">

    <div class="row" style="margin: 0px !important;font-size:13px;">
        <div class="col-md-12" style="padding-right: 0px;">
            <div style="overflow-y:scroll;width:100%;border:1px solid #DBDBDB;height:700px;" id="work_stream">
                @foreach($workstream as $stream)
                    <div class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;" id="comment_{{$stream->id}}">
                        <div class="col-md-12" >
                            <div class="box-tools pull-left" style="padding-right:20px;">
                                @if(!empty($stream->avatar))
                                    <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{$stream->avatar}}" class="user-image-task" alt="User Image">
                                @else
                                    <img src="{{ asset('/assets/dist/img/profile-placeholder.png') }}" class="user-image-task" alt="User Image"/>
                                @endif
                            </div>
                            <div>
                                <strong>{{$stream->author}}</strong>: {{$stream->comment}}
                            </div>
                            @if(Auth::id() == $stream->user_id || Auth::user()->isAdmin())
                                <span class="direct-chat-timestamp pull-right" style="padding-left:10px;"><a href="#" id="{{$stream->id}}" class="delwscomment" style="color:gray;" title="{{trans('office.delete_comment')}}"><i class="ion-trash-a"></i></a></span>
                            @endif
                            <span class="direct-chat-timestamp pull-right"> {{$stream->comment_date}}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row" style="margin: 0px !important;font-size:13px;padding-top:10px;">
        <div class="col-md-12" style="padding-right: 0px;">
            <form role="form" method="POST" action="{{ url('/office/workstream/'.@$customer->id) }}">
                <div class="input-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <textarea style="width:100%;resize:none;" class="form-control workstream_post"></textarea>
                        <span class="input-group-addon"><a id="workstream_post"><i class="ion ion-arrow-return-left"></i></a></span>
                </div>
            </form>
        </div>
    </div>
</div>