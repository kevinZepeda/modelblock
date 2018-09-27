<h4 style="margin: 0px !important;color:#909090;">{{trans('office.questionnarie.questionnaries')}}</h4>
<div style="margin-top:20px;">
    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
        <thead>
        <tr role="row">
            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 10%;">
                {{trans('office.questionnarie.col.date')}}
            </th>
            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 60%;">
                {{trans('office.questionnarie.col.questionarie_name')}}
            </th>
            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
                {{trans('office.questionnarie.col.status')}}
            </th>
            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
                {{trans('office.questionnarie.col.action')}}
            </th>
        </tr>
        </thead>
        <tbody> 
            @foreach($questionnaries as $item)
                <tr role="row" class="odd" id="td{{$item->id}}">
                    <td>{{date("m.d.Y", strtotime($item->submission_date))}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{trans('quote.pending_review.status.'.$item->status)}}</td>
                    <td>    
                        @if(!in_array($item->status, ['SUBMITTED','REVIEWED']))
                            <div class="btn-group">
                                <button onclick="location.href='{{url('settings/questionnarie/edit/'.$item->id)}}'" type="submit" class="btn btn-xs">{{trans('office.questionnarie.button.edit')}}</button>
                                <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu" style="left:-50px;">
                                    <li><a download href="{{url('/quote/request/'.md5(Config::get('app.salt.qa') . $item->id))}}" target="_blank">{{trans('office.questionnarie.button.preview')}}</a></li>
                                    <li><a id="{{$item->id}}" href="#" class="delete_qa">{{trans('office.questionnarie.button.delete')}}</a></li>
                                </ul>
                            </div>
                        @else
                            <div class="btn-group">
                                <button onclick="location.href='{{url('/quote/review/'.$item->id)}}'" type="submit" class="btn btn-xs">{{trans('office.questionnarie.button.review')}}</button>
                                <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu" style="left:-50px;">
                                    <li><a id="{{$item->id}}" href="#" class="delete_qa">{{trans('office.questionnarie.button.delete')}}</a></li>
                                </ul>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>