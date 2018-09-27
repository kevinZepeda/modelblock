<div>
    <input style="margin-bottom:10px;width:100%;height:30px;text-align:center;background-color:#f6f6f6;border:none;border-color:none;font-size:22px;" value="{{@$project->project_name}}" class="input_edit" id="p_subject_preview" placeholder="{{trans('office.project.details.project_name')}}"/>
<div style="width:100%;background-color:#f6f6f6;color:gray;text-align:center;font-size:10px;padding-top:2px;padding-bottom:2px;">{{trans('office.project.details.save_command')}}</div>
    <textarea id="pd">{{@$project->project_description}}</textarea>
</div>
<script>
    $(document).ready(function(){

        var note_toolbar = [
            ['font', ['bold', 'underline']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen']]];

        $('textarea#pd').summernote({
            height: 600,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: true,                  // set focus to editable area after initializing summernote
            toolbar: note_toolbar,
            shortcuts: false
        });
    });
</script>