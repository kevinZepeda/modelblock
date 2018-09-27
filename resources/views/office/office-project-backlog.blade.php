<div class="gridster ready">
    <ul>
        <?php
            $row = 1;
            $col = 1;
        ?>
        @foreach($tasks as $task)
            <li id="{{$task->id}}" name="1" data-row="{{$row}}" data-col="{{$col++}}" data-sizex="1" data-sizey="1" class="gs-w" style="position: absolute; min-width: 140px; min-height: 130px;">
                <div class="box box-no-shadow">
                    <div class="box-header with-border">
                        <div style="font-size:12px;" id="t_priority">{{trans('board.priority.' . \App\Models\ktBoard::getPriorityLabel($task->priority))}}</div>
                        <div class="box-tools pull-right" id="t_estimate">
                            <h6>{{@$task->estimate}}</h6>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body" style="font-size:12px;height:70px;">
                        <strong id="t_type">{{trans('board.task')}} {{substr(ucfirst(\App\Models\ktBoard::getTaskType(@$task->type, $task->subject)),0,15)}}</strong><br/>
                        <div id="t_desc">{{substr(strip_tags(@$task->description),0,30)}}...</div>
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
            <?php
                if($col > 5){
                    $col = 1;
                    $row++;
                }
            ?>
        @endforeach
    </ul>
</div>
