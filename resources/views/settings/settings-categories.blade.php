<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.categories.title')}}</h4>
@include('val-errors')
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">
    <?php $cnt = 0; ?>
    @foreach($categories as $category)

        <div class="tab-edit-item{{ $cnt++ == $users_count ? '-last' : '' }} preview" id="user-{{ $category->id }}">

            <div style="display:inline-block;">
                <div style="width:200px;display:inline-block;">{{ $category->name }}</div>
            </div>
            <div style="float:right;display:inline-block;">
                {{trans('settings.button.edit')}}
            </div>
        </div>
        <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="user-{{ $category->id }}-editor">
            <form role="form" method="POST" action="{{ url('/settings/finance/category') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_category_id" id="_category_id" value="{{ $category->id }}">
                <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.categories.category_name')}}"  style="width:200px;" id="name" name="name" value="{{$category->name}}"/></div>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.categories.explanation'); ?></h6></div>
                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="action" name="action" value="update_category" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button id="action" onclick="javascript:return confirm('{{trans('settings.categories.delete_message')}}');" name="action" value="delete_category" style="float:left;margin-right:10px;"/>{{trans('settings.button.delete')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="user-{{ $category->id }}" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
                </div>
            </form>
        </div>

    @endforeach
</div>
