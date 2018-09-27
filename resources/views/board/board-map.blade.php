<div id="board-map" class="board b-map" style="overflow: hidden;">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <div style="height:50px !important;white-space:nowrap;text-align:center;z-index:99999999999999;padding-top:10px;"
             id="boardc" class="bmapzoom">
            <?php

            $color_factor = 0;
            $layoutColors = '#909090';
            $j = 0;

            if(is_object(\Auth::user())){
                $sysColors = \Auth::user()->getSystemColors();
                $systemlayoutColors = $sysColors['layout_color'];
                $textColor = $sysColors['text_color'];
            }else {
                $systemlayoutColors = $layoutColors;
                $textColor = '#FFFFFF';
            }

            foreach($bards_map as $key => $boardz) {
            $color_factor = $color_factor + 15;
            ?>

            <div style="display: inline-block;">
                <div class="go-to-board" id="{{$boardz->public_hash}}" style="cursor: pointer; margin-left:14px;padding-right:22px;margin-bottom:5px;color:{{$textColor}};display:inline-block;width:100%;padding-top:10px;padding-bottom: 10px;background-color:{{\App\adjustBrightness($systemlayoutColors, $color_factor)}};">
                    {{strtoupper($boardz->department_name)}}
                </div>

                <?
                $col_classes = array(
                        'task',
                        'task-in-progress',
                        'task-completed',
                        'task-verified',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed',
                        'task-deployed'
                );
                $columns = json_decode($boardz->columns);

                $columns_number = count($columns);
                if (isset($boardz->parent_board) && !empty($boardz->parent_board)) {
                    $columns_number++;
                }
                if (isset($boardz->child_board) && !empty($boardz->child_board)) {
                    $columns_number++;
                }

                $i = 0;
                ?>
                <?php $parent = 0;?>
                <div style="display:block;">
                    @if(isset($boardz->parent_board) && !empty($boardz->parent_board))
                        <?php $parent = 1;?>
                        <div id="{{$j}}" class="bg-gray color-palette kanban-coulmn "
                             style="display:inline-block;opacity:0.4;border:1px dashed gray;margin-left:{{($j == 0)?'0px':'22px'}};">
                            <i style="float:left;" class="ion-reply">&nbsp;</i><span id="c_{{$j++}}"
                                                                                     class="col-name">{{strtoupper(trans('board.previous_stage'))}}</span>
                        </div>
                    @endif

                    @foreach($columns as $col)
                        <div id="{{$i}}" class="bg-gray {{$col_classes[$i]}} color-palette kanban-coulmn"
                             style="display:inline-block;;margin-left:{{($j == 0)?'14px':'24px'}};">
                            <span id="c_{{$i++}}" class="col-name">{{strtoupper(trim($col))}}</span> <?php $j++; ?>
                        </div>
                    @endforeach

                    @if(isset($board->child_board) && !empty($boardz->child_board))
                        <div id="{{$j}}" class="bg-gray color-palette kanban-coulmn"
                             style="display:inline-block;opacity:0.4;border:1px dashed gray;margin-left:22px;">
                        <span style="display:inline;" id="c_{{$j++}}"
                              class="col-name">{{strtoupper(trans('board.next_stage'))}}</span><i
                                    style="float:right;display:inline;position:relative;" class="ion-forward">&nbsp;</i>
                        </div>
                    @endif

                    </div>
                </div>
                <div style="display:inline;position:absolute;height:100%;width:12px; border-right: 1px dashed gray;">
            </div>

            <?php } ?>

            <div class="gridster gridster-map ready">
                <ul>
                    <?php $row = [];

                    $y_transition = 0;

                    foreach($bards_map as $key => $boardz) {

                    $board_tasks = \App\Models\ktBoard::getBoardTasks($boardz->public_hash);

                    $columns = json_decode($boardz->columns);

                    $columns_number = count($columns);
                    if (isset($boardz->parent_board) && !empty($boardz->parent_board)) {
                        $columns_number++;
                    }
                    if (isset($boardz->child_board) && !empty($boardz->child_board)) {
                        $columns_number++;
                    }

                    ?>

                    @foreach($board_tasks as $task)
                        <?php
                        if (isset($row[$task->size_y])) {
                            $row[$task->size_y]++;
                        } else {
                            $row[$task->size_y] = 1;
                        }
                        ?>
                        <li id="{{$task->id}}" name="{{$task->id}}" data-row="{{$row[$task->size_y]}}"
                            data-col="{{$task->size_y + $y_transition}}" data-sizex="1" data-sizey="1" class="gs-w"
                            style="position: absolute; min-width: 140px; min-height: 140px;text-align:left;">
                            <div class="box box-no-shadow {{\App\Models\ktBoard::getPriorityColor($task->priority)}}"
                                 id="p_{{$task->id}}">
                                <div class="ribbon previous"
                                     style="display:{{(is_object($board->parent_board) && $task->size_y + $y_transition == $y_transition + 1)?'block':'none'}};">
                                    <span>{{trans('board.returned')}}</span></div>
                                <div class="ribbon"
                                     style="display:{{(is_object($board->child_board) && $task->size_y == $columns_number)?'block':'none'}};">
                                    <span>{{trans('board.completed')}}</span></div>
                                <div class="box-header with-border">
                                    <div style="font-size:12px;"
                                         id="t_priority">{{trans('board.priority.' . \App\Models\ktBoard::getPriorityLabel($task->priority))}}</div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body" style="font-size:12px;height:90px;text-align:left;">
                                    <div class="box-tools pull-right">
                                        @if(!empty($task->avatar))
                                            <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{$task->avatar}}"
                                                 class="user-image-task" alt="User Image"/>
                                        @else
                                            <img src="{{ asset('/assets/dist/img/profile-placeholder.png') }}"
                                                 class="user-image-task" alt="User Image"/>
                                        @endif
                                    </div>
                                    <strong id="t_type"
                                            style="text-align:left;">{{trans('board.task')}} {{ucfirst(\App\Models\ktBoard::getTaskType(@$task->type, $task->subject))}}</strong><br/>

                                    <div id="t_desc"
                                         style="text-overflow: ellipsis;white-space: normal;word-break: break-all !important;padding-right:10px !important;">{{substr(strip_tags(@$task->description),0,50)}}
                                        ...
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer agile text-center" id="t_assignee">
                                    @if(!empty($task->user_id))
                                        {{\App\Models\ktUser::getUserFullName(@$task->user_id)}}
                                    @else
                                        {{trans('board.unassigned')}}
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach

                    <?
                    $y_transition = $columns_number + $y_transition;

                    } ?>

                </ul>
            </div>
        </div>
    </div>
</div>