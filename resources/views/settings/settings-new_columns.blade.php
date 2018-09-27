<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.new_board.title')}}</h4>
@include('val-errors')
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">
    <form role="form" method="POST" action="{{ url('/settings/new-board-template') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="tab-edit-item" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;">
            <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:500px;">
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.new_board.input.board_template_name')}}" style="width:100%;height:34px;padding-left:5px;" name="board_name" id="board_name"/></div>
                <select class="js-example-tags form-control" multiple="" tabindex="-1" style="display: none;width:100%;text-align:center;" id="board_columns[]" name="board_columns[]">
                </select>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.new_board.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="new_board"  style="float:left;margin-right:10px;"/>{{trans('settings.button.create')}} </button></div>
            </div>
        </div>
    </form>
</div>