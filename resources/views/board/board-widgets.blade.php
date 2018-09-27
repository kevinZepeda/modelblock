
@if(!Auth::user()->isClient())
<div id="new-kanban" title="{{trans('board.widget_create_board.title')}}">
    <form role="form" method="POST" action="{{ url('/api/new-board') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input style="width:100%;text-align:center;" placeholder="{{trans('board.widget_create_board.input.name')}}" id="new-board-name" name="new-board-name"/>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input style="width:100%;text-align:center;" placeholder="{{trans('board.widget_create_board.input.description')}}" id="new-board-desc" name="new-board-desc"/>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <select style="width:100%;"  id="new-board-department" name="new-board-department">
                </select>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <select style="width:100%;"  id="new-board-template" name="new-board-template">
                </select>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;" id="custom_col">
            <div class="col-md-12" style="padding-right: 0px;">
                <select class="js-example-tags form-control" multiple="" tabindex="-1" style="display: none;width:100%;text-align:center;" id="new-board-col" name="new-board-col">
                </select>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input type="text" class="form-control pull-right" style="text-align:center;width:100% !important;height:24px;" placeholder="{{trans('board.widget_create_board.input.date_range')}}" id="new-board-date" name="new-board-date"/>
            </div>
        </div>
    </form>
</div>
@endif
@if(!Auth::user()->isClient())
    <div id="new-child-kanban" title="{{trans('board.widget_create_chained_board.title')}}">
        <form role="form" method="POST" action="{{ url('/api/new-board') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <select style="width:100%;"  id="new-child-board-department" name="new-child-board-department">
                    </select>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <select style="width:100%;"  id="new-child-board-template" name="new-child-board-template">
                    </select>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;" id="child-custom_col">
                <div class="col-md-12" style="padding-right: 0px;">
                    <select class="js-example-tags form-control" multiple="" tabindex="-1" style="display: none;width:100%;text-align:center;" id="new-child-board-col" name="new-child-board-col">
                    </select>
                </div>
            </div>
        </form>
    </div>
@endif
@if(is_object($board) && !Auth::user()->isClient())
<div id="edit-kanban" title="{{trans('board.widget_edit_board.title')}}">
    <form role="form" method="POST" action="{{ url('/api/edit-board') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input style="width:100%;text-align:center;" placeholder="{{trans('board.widget_edit_board.input.name')}}" id="edit-board-name" name="edit-board-name" value="{{$board->name}}"/>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input style="width:100%;text-align:center;" placeholder="{{trans('board.widget_edit_board.input.name')}}" id="edit-board-desc" name="edit-board-desc" value="{{$board->description}}"/>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <select style="width:100%;"  id="edit-board-department" name="edit-board-department">
                </select>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;" id="custom_col">
            <div class="col-md-12" style="padding-right: 0px;">
                <select class="js-example-tags form-control" multiple="" tabindex="-1" style="display: none;width:100%;text-align:center;" id="edit-board-col" name="edit-board-col">
                <?php $columns = json_decode($board->columns); ?>
                @foreach($columns as $board_col)
                    <option selected>{{$board_col}}</option>
                @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input type="text" class="form-control pull-right" style="text-align:center;width:100% !important;height:24px;" placeholder="{{trans('board.widget_edit_board.input.date_range')}}" id="edit-board-date" name="edit-board-date" value="{{date_format(date_create($board->start_date), 'd/m/Y')}} - {{date_format(date_create($board->end_date), 'd/m/Y')}}"/>
            </div>
        </div>
    </form>
</div>
@endif

<div id="note-preview" name="note-preview" title="">
    <input id="task_id_h" name="taks_id_h"  type="hidden"/>
    <div class="row task_details" style="background-color:#f6f6f6;color:gray;margin-left:10px;padding-top:6px;margin-right:10px;padding-bottom:5px;text-align:center;">
        <span id="subject" class="task_edit subject_preview"></span>
        <span id="subject_change" style="display:none;">
            <input style="width:100%;height:20px;text-align:center;background-color: transparent;border:none;" value="" class="input_edit" id="subject_preview"/>
        </span>
    </div>
    <div class="row task_details" style="margin: 0px !important;padding:10px 10px 15px 10px !important;font-size:13px;cursor: default !important;">
        <div class="col-md-8" >
            <textarea id="task_description_preview" style="width:100%;resize:none;height:210px;background-color:#f6f6f6;cursor: default !important;text-align:justify;" class="form-control" readonly="readonly"></textarea>
            @if(!Auth::user()->isClient())
            <div style="width:100%;background-color:#f6f6f6;color:gray;text-align:center;font-size:10px;padding-top:2px;padding-bottom:2px;">{{trans('board.widget_note_preview.save_event')}}</div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="row" style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;margin-bottom:10px;">
                <div class="col-md-5">
                    <strong>{{trans('board.widget_note_preview.labels.board')}}</strong>
                </div>
                <div class="col-md-7">
                    <span id="board" class="task_edit board_change">-</span>
                      <span id="board_change" style="display:none;">
                          <select style="width:100%;" id="board_edit">
                              <option value="NULL">{{trans('board.widget_note_preview.labels.backlog')}}</option>
                              <option disabled>-</option>
                              @foreach($boards_list as $board_entity)
                                  <option value="{{$board_entity->public_hash}}" @if('/'.$board_entity->public_hash == $hash) selected @endif>{{ucfirst(strtolower($board_entity->name))}} @if(!empty($board_entity->department_name))({{ucfirst(strtolower($board_entity->department_name))}})@endif</option>
                              @endforeach
                          </select>
                      </span>
                </div>
            </div>
            <div class="row" style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;margin-bottom:10px;">
                <div class="col-md-5">
                    <strong>{{trans('board.widget_note_preview.labels.state')}}</strong>
                </div>
                <div class="col-md-7" id="task_state">
                    -
                </div>
            </div>
            <div class="row" style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;margin-bottom:10px;">
                <div style="padding-bottom:25px;">
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_note_preview.labels.project')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span id="project" class="task_edit project_edit">-</span>
                        <span id="project_change" style="display:none;">
                            <select style="width:100%;" id="project_edit">
                                <option value="">-</option>
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}">{{ucfirst($project->project_name)}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
                <div style="padding-bottom:25px;">
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_note_preview.labels.type')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span id="type" class="task_edit type_edit" style="width:100%;">-</span>
                        <span id="type_change" style="display:none;">
                            <select style="width:100%;" id="type_edit">
                                <option value="">-</option>
                                @foreach(\App\Models\ktBoard::$task_type as $key => $type)
                                    <option value="{{$key}}">{{ucfirst(trans('board.task_type.'.$type))}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
                <div>
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_note_preview.labels.priority')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span id="priority" class="task_edit priority_edit" style="width:100%;">-</span>
                        <span id="priority_change" style="display:none;">
                            <select style="width:100%;" id="priority_edit">
                                <option value="">-</option>
                                @foreach(\App\Models\ktBoard::$priority as $key => $priority)
                                    <option value="{{$key}}">{{ucfirst(trans('board.priority.'.$priority))}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;">
                <div style="padding-bottom:25px;">
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_note_preview.labels.manager')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span id="manager"class="task_edit manager_edit" style="width:100%;">-</span>
                        <span id="manager_change" style="display:none;">
                            <select style="width:100%;" id="manager_edit">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
                <div style="padding-bottom:25px;">
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_note_preview.labels.assignee')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span id="assignee" class="task_edit user_edit"></span>
                        <span id="assignee_change" style="display:none;">
                            <select style="width:100%;" id="user_edit">
                                <option value="">-</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
                <div>
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_note_preview.labels.estimate')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span id="estimate" class="task_edit estimate_preview"></span>
                        <span id="estimate_change" style="display:none;">
                            <input style="width:100%;height:19px;" class="input_edit" id="estimate_input_task_change" placeholder="2mo1d or 1w3h5m" />
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!Auth::user()->isClient())
    <div class="row" style="font-size:11px;background-color:#f6f6f6;color:gray;margin-left:10px;margin-right:10px;padding-bottom:2px;text-align:center;">
        <a href="#" style="width:100%;cursor:pointer;color:gray;" id="w_stream_extended" ><i class="ion-chevron-up" style="font-size:8px;"></i></a>
        <div id="stream_label">{{trans('board.widget_note_preview.workstream')}}</div>
        <a href="#" id="w_stream_small" style="width:100%;display:none;cursor:pointer;color:gray;" ><i class="ion-chevron-down" style="font-size:8px;"></i></a>
    </div>

    <div style="height:200px;overflow-y:scroll;margin-left:10px;width:97%;border:1px solid #DBDBDB;" id="work_stream">

    </div>
    <div class="row" style="margin: 0px !important;font-size:13px;padding-left:10px;padding-top:15px;">
        <div class="col-md-12" style="padding-right:10px;padding-bottom:15px;">
            <div class="input-group">
                <textarea style="width:100%;resize:none;" class="form-control" id="comment"></textarea>
                <span class="input-group-addon"><a href="#" id="post_comment"><i class="ion ion-arrow-return-left"></i></a></span>
            </div>
        </div>
    </div>
    @endif
</div>
@if(!Auth::user()->isClient())
<div id="new-note" title="{{trans('board.widget_new_note.title')}}">
    <div class="row" style="background-color:#f6f6f6;color:gray;margin-left:10px;padding-top:6px;margin-right:10px;padding-bottom:5px;text-align:center;">
        <span>
            <input id="task_subject" name="task_subject" style="width:100%;height:23px;text-align:center;background-color: transparent;border:none;" placeholder="{{trans('board.widget_new_note.subject')}}"/>
        </span>
    </div>
    <div class="row" style="margin: 0px !important;padding:10px 10px 15px 10px !important;font-size:13px;cursor: default !important;">
        <div class="col-md-8" >
            <textarea id="task_description" name="task_description" style="width:100%;resize:none;height:410px;background-color:#f6f6f6;cursor: default !important;text-align:justify;" class="form-control" readonly="readonly"></textarea></div>
        <div class="col-md-4">
            <div class="row" style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;margin-bottom:10px;">
                <div class="col-md-5">
                    <strong>{{trans('board.widget_new_note.labels.board')}}</strong>
                </div>
                <div class="col-md-7">
                      <span>
                        <select style="width:100%;" id="board_hash" name="board_hash">
                            <option value="">-</option>
                            @foreach($boards_list as $board_entity)
                                <option value="{{$board_entity->public_hash}}" @if('/'.$board_entity->public_hash == $hash) selected @endif>{{ucfirst(strtolower($board_entity->name))}} @if(!empty($board_entity->department_name))({{ucfirst(strtolower($board_entity->department_name))}})@endif</option>
                            @endforeach
                        </select>
                      </span>
                </div>
            </div>
            <div class="row" style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;margin-bottom:10px;">
                <div style="padding-bottom:25px;">
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_new_note.labels.project')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span>
                            <select style="width:100%;" id="project_id" name="project_id">
                                <option value="">-</option>
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}">{{ucfirst($project->project_name)}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
                <div style="padding-bottom:25px;">
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_new_note.labels.type')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span>
                            <select style="width:100%;" id="task_type" name="task_type">
                                <option value="">-</option>
                                @foreach(\App\Models\ktBoard::$task_type as $key => $type)
                                    <option value="{{$key}}">{{ucfirst(trans('board.task_type.'.$type))}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
                <div>
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_new_note.labels.priority')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span>
                            <select style="width:100%;" id="task_priority" name="task_priority">
                                <option value="">-</option>
                                @foreach(\App\Models\ktBoard::$priority as $key => $priority)
                                    <option value="{{$key}}">{{ucfirst(trans('board.priority.'.$priority))}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;">
                <div>
                    <div class="col-md-5">
                        <strong>{{trans('board.widget_new_note.labels.estimate')}}</strong>
                    </div>
                    <div class="col-md-7">
                        <span>
                            <input style="width:100%;height:19px;" class="input_edit estimate_input" id="estimate_input_n_task" name="estimate_input_n_task" placeholder="2mo1d or 1w3h5m" />
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif