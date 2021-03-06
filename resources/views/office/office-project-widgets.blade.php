@if(!Auth::user()->isClient())
    <div id="new-project" title="{{trans('office.project.project_widget.title')}}">
        <div class="row"
             style="color:gray;margin-left:10px;padding-top:6px;margin-right:10px;padding-bottom:5px;text-align:center;">
            <div class="col-md-6">
                <input id="project_name" name="project_name"
                       style="width:100%;height:28px;text-align:center;border:1px solid #ccc;border-radius: 5px;"
                       placeholder="{{trans('office.project.project_widget.project_name')}}"/>
            </div>
            <div class="col-md-6" style="padding-right: 0px;">
                <select id="pcustomers-list" style="width:100%;height:100px;">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="row"
             style="margin: 0px !important;padding:10px 10px 10px 10px !important;font-size:13px;cursor: default !important;">
            <div class="col-md-12" style="padding-right: 0px;">
                <textarea id="project_description" name="project_description"
                          style="width:100%;resize:none;height:100%;background-color:#f6f6f6;cursor: default !important;text-align:justify;"
                          class="form-control" readonly="readonly"></textarea></div>
        </div>
    </div>
    <script type="text/javascript">
        var note_toolbar = [
            ['font', ['bold', 'underline']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', []],
            ['view', []]];

        $('textarea#project_description').summernote({
            height: 375,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: true,                  // set focus to editable area after initializing summernote
            toolbar: note_toolbar
        });
    </script>
    </div>
    @if(isset($project))
        @if($subblock == 'backlog')
            <div id="note-preview" name="note-preview" title="">
                <input id="task_id_h" name="taks_id_h" type="hidden"/>

                <div class="row task_details"
                     style="background-color:#f6f6f6;color:gray;margin-left:10px;padding-top:6px;margin-right:10px;padding-bottom:5px;text-align:center;">
                    <span id="subject" class="task_edit subject_preview"></span>
            <span id="subject_change" style="display:none;">
                <input style="width:100%;height:20px;text-align:center;background-color: transparent;border:none;"
                       value="" class="input_edit" id="subject_preview"
                       placeholder="{{trans('office.project.note_preview.task_note_label')}}"/>
            </span>
                </div>
                <div class="row task_details"
                     style="margin: 0px !important;padding:10px 10px 15px 10px !important;font-size:13px;cursor: default !important;">
                    <div class="col-md-8">
                        <textarea id="task_description_preview"
                                  style="width:100%;resize:none;height:210px;background-color:#f6f6f6;cursor: default !important;text-align:justify;"
                                  class="form-control" readonly="readonly"></textarea>

                        <div style="width:100%;background-color:#f6f6f6;color:gray;text-align:center;font-size:10px;padding-top:2px;padding-bottom:2px;">{{trans('office.project.note_preview.save_command')}}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="row"
                             style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;margin-bottom:10px;">
                            <div class="col-md-5">
                                <strong>{{trans('office.project.note_preview.backlog_label')}}</strong>
                            </div>
                            <div class="col-md-7">
                                <span id="board"
                                      class="task_edit board_change">{{trans('office.project.note_preview.backlog_label')}}</span>
                          <span id="board_change" style="display:none;">
                              <select style="width:100%;" id="board_edit">
                                  <option value="NULL"
                                          selected>{{trans('office.project.note_preview.backlog_value')}}</option>
                                  <option disabled>-</option>
                                  @foreach($boards_list as $board_entity)
                                      <option value="{{$board_entity->public_hash}}">{{$board_entity->name}}</option>
                                  @endforeach
                              </select>
                          </span>
                            </div>
                        </div>
                        <div class="row"
                             style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;margin-bottom:10px;">
                            <div style="padding-bottom:25px;">
                                <div class="col-md-5">
                                    <strong>{{trans('office.project.note_preview.project_label')}}</strong>
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
                                    <strong>{{trans('office.project.note_preview.type_label')}}</strong>
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
                                    <strong>{{trans('office.project.note_preview.priority_label')}}</strong>
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
                        <div class="row"
                             style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;">
                            <div style="padding-bottom:25px;">
                                <div class="col-md-5">
                                    <strong>{{trans('office.project.note_preview.manager_label')}}</strong>
                                </div>
                                <div class="col-md-7">
                                    <span id="manager" class="task_edit manager_edit" style="width:100%;">-</span>
                            <span id="manager_change" style="display:none;">
                                <select style="width:100%;" id="manager_edit">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                            </span>
                                </div>
                            </div>
                            <div>
                                <div class="col-md-5">
                                    <strong>{{trans('office.project.note_preview.estimate_label')}}</strong>
                                </div>
                                <div class="col-md-7">
                                    <span id="estimate" class="task_edit estimate_preview"></span>
                            <span id="estimate_change" style="display:none;">
                                <input style="width:100%;height:19px;" class="input_edit"
                                       id="estimate_input_task_change" placeholder="2mo1d or 1w3h5m"/>
                            </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="padding-left:17px;padding-top:10px;padding-right:0px;">
                                <button class="btn btn-small remove-task" style="width:100%;" id=""><i
                                            class="ion-trash-a"></i>&nbsp; Remove Permanently
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row"
                     style="font-size:11px;background-color:#f6f6f6;color:gray;margin-left:10px;margin-right:10px;padding-bottom:2px;text-align:center;">
                    <a href="#" style="width:100%;cursor:pointer;color:gray;" id="w_stream_extended"><i
                                class="ion-chevron-up" style="font-size:8px;"></i></a>

                    <div id="stream_label">{{trans('office.project.note_preview.workstream')}}</div>
                    <a href="#" id="w_stream_small" style="width:100%;display:none;cursor:pointer;color:gray;"><i
                                class="ion-chevron-down" style="font-size:8px;"></i></a>
                </div>

                <div style="height:200px;overflow-y:scroll;margin-left:10px;width:97%;border:1px solid #DBDBDB;"
                     id="work_stream">

                </div>
                <div class="row" style="margin: 0px !important;font-size:13px;padding-left:10px;padding-top:15px;">
                    <div class="col-md-12" style="padding-right:10px;padding-bottom:15px;">
                        <div class="input-group">
                            <textarea style="width:100%;resize:none;" class="form-control" id="comment"></textarea>
                            <span class="input-group-addon"><a href="#" id="post_comment"><i
                                            class="ion ion-arrow-return-left"></i></a></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($subblock == 'requirements')
            <div id="requirement-preview" name="requirement-preview"
                 title="{{trans('office.project.requirement_widget.title')}}">
                <input id="req_id_h" name="req_id_h" type="hidden"/>

                <div class="row task_details"
                     style="background-color:#f6f6f6;color:gray;margin-left:10px;padding-top:6px;margin-right:10px;padding-bottom:5px;text-align:center;">
            <span id="subject_change">
                <input style="width:100%;height:20px;text-align:center;background-color: transparent;border:none;"
                       placeholder="{{trans('office.project.requirement_widget.requiremenet')}}" value=""
                       class="input_edit r_subject" id="r_subject"/>
            </span>
                </div>
                <div class="row task_details"
                     style="margin: 0px !important;padding:10px 10px 10px 10px !important;font-size:13px;cursor: default !important;">
                    <div class="col-md-12" style="padding-right: 0px !important;">
                        <textarea id="req_description_preview"
                                  style="width:100%;resize:none;height:210px;background-color:#f6f6f6;cursor: default !important;text-align:justify;"
                                  class="form-control r_details" readonly="readonly"></textarea>

                        <div style="width:100%;background-color:#f6f6f6;color:gray;text-align:center;font-size:10px;padding-top:2px;padding-bottom:2px;">{{trans('office.project.note_preview.save_command')}}</div>
                    </div>
                    <div class="col-md-12" style="padding-right:0px;">
                        <button class="btn btn-small remove-req" style="width:100%;padding-right:0px;" id=""><i
                                    class="ion-trash-a"></i>&nbsp; {{trans('office.project.note_preview.remove')}}
                        </button>
                    </div>
                </div>
            </div>
        @endif
        @if($block == 'project')
            <div id="new-note" title="{{trans('office.project.note_preview.title')}}">
                <div class="row"
                     style="background-color:#f6f6f6;color:gray;margin-left:10px;padding-top:6px;margin-right:10px;padding-bottom:5px;text-align:center;">
            <span>
                <input id="task_subject" name="task_subject"
                       style="width:100%;height:23px;text-align:center;background-color: transparent;border:none;"
                       placeholder="{{trans('office.project.note_preview.task_note_label')}}"/>
            </span>
                </div>
                <div class="row"
                     style="margin: 0px !important;padding:10px 10px 15px 10px !important;font-size:13px;cursor: default !important;">
                    <div class="col-md-8">
                        <textarea id="task_description" name="task_description"
                                  style="width:100%;resize:none;height:410px;background-color:#f6f6f6;cursor: default !important;text-align:justify;"
                                  class="form-control" readonly="readonly"></textarea></div>
                    <div class="col-md-4">
                        <div class="row"
                             style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;margin-bottom:10px;">
                            <div style="padding-bottom:25px;">
                                <div class="col-md-5">
                                    <strong>{{trans('office.project.note_preview.project_label')}}</strong>
                                </div>
                                <div class="col-md-7">
                            <span>
                                @foreach($projects as $project)
                                    @if($s_project->id == $project->id)
                                        {{ucfirst($project->project_name)}}
                                    @endif
                                @endforeach   
                            </span>
                                </div>
                            </div>
                            <div style="padding-bottom:25px;">
                                <div class="col-md-5">
                                    <strong>{{trans('office.project.note_preview.type_label')}}</strong>
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
                                    <strong>{{trans('office.project.note_preview.priority_label')}}</strong>
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
                        <div class="row"
                             style="margin-left:1px;background-color:#f6f6f6;color:gray;padding-left:6px;padding-top:6px;padding-bottom:6px;">
                            <div>
                                <div class="col-md-5">
                                    <strong>{{trans('office.project.note_preview.estimate_label')}}</strong>
                                </div>
                                <div class="col-md-7">
                            <span>
                                <input style="width:100%;height:19px;" class="input_edit estimate_input"
                                       id="estimate_input_n_task" name="estimate_input_n_task"
                                       placeholder="2mo1d {{trans('office.project.note_preview.or')}} 1w3h5m"/>
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="new-requirement" name="new-requirement"
                 title="{{trans('office.project.requirement_widget.title')}}">
                <input id="task_id_h" name="taks_id_h" type="hidden"/>

                <div class="row task_details"
                     style="background-color:#f6f6f6;color:gray;margin-left:10px;padding-top:6px;margin-right:10px;padding-bottom:5px;text-align:center;">
            <span>
                <input class="s_subject"
                       style="width:100%;height:20px;text-align:center;background-color: transparent;border:none;"
                       placeholder="{{trans('office.project.requirement_widget.requiremenet')}}" class="input_edit"
                       id="s_subject"/>
            </span>
                </div>
                <div class="row task_details"
                     style="margin: 0px !important;padding:10px 10px 10px 10px !important;font-size:13px;cursor: default !important;">
                    <div class="col-md-12" style="padding-right: 0px !important;">
                        <textarea id="n_details"
                                  style="width:100%;resize:none;height:210px;background-color:#f6f6f6;cursor: default !important;text-align:justify;"
                                  class="form-control" readonly="readonly"></textarea>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif