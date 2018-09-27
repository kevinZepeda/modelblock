<!-- =============================================== -->
<div class="board">
    <div class="wrapper" style="background: none;padding-top:7px;">
        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background:none;overflow-x:hidden;">

            <!-- Main content -->
            <div style="height:50px !important;white-space:nowrap;text-align:center;z-index:99999999999999;" id="boardc">
                <?php
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
                $columns = json_decode($board->columns);

                $columns_number = count($columns);
                if(is_object($board->parent_board)){
                    $columns_number++;
                }
                if(is_object($board->child_board)){
                    $columns_number++;
                }


                $i=0;
                $j=0;
                ?>

                <script>
                    var tmp_columns = $.parseJSON('<?php echo $board->columns;?>');
                </script>

                <?php $parent = 0;?>
                @if(is_object($board->parent_board))
                    <?php $parent = 1;?>
                    <script>
                        tmp_columns.unshift('{{strtoupper(trans('board.previous_stage'))}}');
                    </script>
                    <div id="{{$j}}" class="bg-gray color-palette kanban-coulmn go-to-parent" style="display:inline-block;cursor: pointer;opacity:0.4;border:1px dashed gray;">
                        <i style="float:left;margin-left:20px;" class="ion-reply">&nbsp;</i><span id="c_{{$j++}}" class="col-name">{{strtoupper(trans('board.previous_stage'))}}</span>
                    </div>
                @endif

                @foreach($columns as $col)
                    <div id="{{$i}}" class="bg-gray {{$col_classes[$i]}} color-palette kanban-coulmn column-edit" style="display:inline-block;@if($j > 0 || $i > 0)margin-left:24px;@endif cursor: pointer;">
                        <span id="c_{{$i}}" class="col-name">{{strtoupper(trim($col))}}</span>
                        <span class="col-edit" style="display:none;"><i class="ion ion-edit">&nbsp;</i>&nbsp;{{trans('board.rename')}}</span>
                        <input id="{{$i++}}" class="col-edit-input" value="{{strtoupper(trim($col))}}" style="height:95%;width:90%;color:white; border: none; background-color: transparent; display:none;text-align:center;text-transform: uppercase;"/>
                    </div>
                @endforeach


                @if(is_object($board->child_board))
                    <script>
                        tmp_columns.push('{{strtoupper(trans('board.next_stage'))}}');
                    </script>
                    <div id="{{$j}}" class="bg-gray color-palette kanban-coulmn go-to-child" style="display:inline-block;cursor: pointer;opacity:0.4;margin-left:22px;border:1px dashed gray;">
                        <span style="display:inline;" id="c_{{$j++}}" class="col-name">{{strtoupper(trans('board.next_stage'))}}</span><i style="float:right;display:inline;position:relative;" class="ion-forward">&nbsp;</i>
                    </div>
                @endif
            </div>
            <div class="gridster gridster-kanban ready">
                <ul>
                    <?php $row = []; ?>
                    @if(is_object($board))
                        @foreach($tasks as $task)
                            <?php
                                if(isset($row[$task->size_y])){
                                    $row[$task->size_y]++;
                                } else {
                                    $row[$task->size_y] = 1;
                                }
                                ?>
                            <li id="{{$task->id}}" name="{{$task->id}}" data-row="{{$row[$task->size_y]}}" data-col="{{$task->size_y}}" data-sizex="1" data-sizey="1" class="gs-w realtask {{(is_object($board->parent_board) && $task->size_y == 1)?'ignore-update':''}}{{(is_object($board->child_board) && $task->size_y == $columns_number)?'ignore-update':''}}" style="position: absolute; min-width: 140px; min-height: 140px;">
                                <div class="box box-no-shadow {{\App\Models\ktBoard::getPriorityColor($task->priority)}}" id="p_{{$task->id}}">
                                    <div class="rc ribbon previous" style="display:{{(is_object($board->parent_board) && $task->size_y == 1)?'block':'none'}};"><span>{{trans('board.returned')}}</span></div>
                                    <div class="rp ribbon" style="display:{{(is_object($board->child_board) && $task->size_y == $columns_number)?'block':'none'}};"><span>{{trans('board.completed')}}</span></div>
                                    <div class="box-header with-border">
                                        <div style="font-size:12px;" id="t_priority">{{trans('board.priority.' . \App\Models\ktBoard::getPriorityLabel($task->priority))}}</div>
                                        <div class="box-tools pull-right" id="t_estimate">
                                            <h6>{{@$task->estimate}}</h6>
                                        </div>
                                    </div><!-- /.box-header -->
                                    <div class="box-body" style="font-size:12px;height:90px;">
                                        <div class="box-tools pull-right">
                                            @if(!empty($task->avatar))
                                                <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{$task->avatar}}" class="user-image-task" alt="User Image"/>
                                            @else
                                                <img src="{{ asset('/assets/dist/img/profile-placeholder.png') }}" class="user-image-task" alt="User Image"/>
                                            @endif
                                        </div>
                                        <strong id="t_type">{{trans('board.task')}} {{ucfirst(\App\Models\ktBoard::getTaskType(@$task->type, $task->subject))}}</strong><br/>
                                        <div id="t_desc">{{substr(strip_tags(@$task->description),0,100)}}...</div>
                                    </div><!-- /.box-body -->
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
                    @endif


                </ul>
            </div>

            <!--                     </div>
                              </div> -->


        </div><!-- /.content-wrapper -->

    </div><!-- ./wrapper -->
</div>

<div style="background-color:#fff">

    <div class="wrapper">

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <?php echo Config::get('app.copyright.terms'); ?>
            </div>
            <?php echo Config::get('app.copyright.html'); ?>
        </footer>

    </div><!-- ./wrapper -->

</div>
