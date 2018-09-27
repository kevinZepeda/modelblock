<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.manage_columns.title')}}</h4>
@include('val-errors')
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">
    <?php $cnt = 0; ?>
    @foreach($board_templates as $board_template)
        <div class="tab-edit-item{{ $cnt++ == $board_counts ? '-last' : '' }} preview" id="bcol-{{ $board_template->id }}">
            <div style="display:inline-block;">
                <div style="width:200px;display:inline-block;">{{$board_template->name}}</div>
                <div style="display:inline-block;color:#BBB2B2;font-size:9px;">{{implode(' | ', json_decode(strtoupper($board_template->columns)))}}</div>
            </div>
            <div style="float:right;display:inline-block;">
                {{trans('settings.button.edit')}}
            </div>
        </div>
        <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="bcol-{{ $board_template->id }}-editor">
            <form role="form" method="POST" action="{{ url('/settings/manage-board-templates') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_bc_id" id="_bc_id" value="{{ $board_template->id }}">
                <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.manage_columns.board_name')}}"  style="width:100%;height:34px;padding-left:5px;" id="board_name" name="board_name" value="{{$board_template->name}}"/></div>
                    <select class="js-example-tags form-control" multiple="" tabindex="-1" style="display: none;width:100%;text-align:center;" id="board_columns[]" name="board_columns[]">
                        <?php $columns = json_decode($board_template->columns); ?>
                        @foreach($columns as $board_col)
                            <option selected>{{$board_col}}</option>
                        @endforeach
                    </select>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.finance.manage_columns.explanation'); ?></h6></div>
                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="action" name="action" value="update_board_template" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="bcol-{{ $board_template->id }}" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
                </div>
            </form>
        </div>
    @endforeach
</div>

