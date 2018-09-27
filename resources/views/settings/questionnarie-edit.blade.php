<div style='width:100%;background-color:white;border-bottom:1px solid #ccc;'>
	<input style="width:70%;border:0px solid;padding:10px;font-size:16px;" placeholder="{{trans('settings.questionnaire.name')}}" id="name" value="{{$template->name}}"/>
	<button  type="button" class="btn btn-sm btn-sm mr10 save" style="float:right;margin:5px;">{{trans('settings.questionnaire.save')}}</button>
	<button  type="button" class="btn btn-sm btn-sm mr10" style="float:right;margin:5px;" onclick="javascript:window.open('{{url('/quote/request/'.md5(Config::get('app.salt.qa') . $template->id))}}');">{{trans('settings.questionnaire.preview')}}</button>
	<button  type="button" class="btn btn-sm btn-sm mr10 publish" style="float:right;margin:5px;">{{(@$template->public)? trans('settings.questionnaire.unpublish'):trans('settings.questionnaire.publish')}}</button>
</div>
<div class='questionnarie'></div>