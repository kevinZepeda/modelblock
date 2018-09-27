@if ($validation_messages !== false && count($validation_messages) > 0)
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> {{trans('application.validation.general')}}</h4>
        <?php $cnt=1; ?>
        @if(isset($validation_messages['messages']))
            @foreach ($validation_messages['messages'] as $error)
                &middot; {{ $error }}<br/>
            @endforeach
        @else
            @foreach ($validation_messages as $error)
            &middot; {{ $error }}<br/>
            @endforeach
        @endif
    </div>
@endif

@if ($validation_messages === false)
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fa fa-check"></i> {{trans('application.saved')}}
    </div>
@endif