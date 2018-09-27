<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
    <thead>
    <tr role="row">
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 70%;">
            {{trans('settings.questionnarie_list.col.name')}}
        </th>
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 10%;">
            {{trans('settings.questionnarie_list.col.public')}}
        </th>
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 10%;">
            {{trans('settings.questionnarie_list.col.action')}}
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $item)
        <tr role="row" class="odd" id="td{{$item->id}}">
            <td>{{$item->name}}</td>
            <td>{{($item->public)?trans('settings.questionnarie_list.yes'):trans('settings.questionnarie_list.no')}}</td>
            <td>
                <div class="btn-group">
                    <button onclick="location.href='{{url('settings/questionnarie/edit/'.$item->id)}}'" type="submit" class="btn btn-xs">{{trans('settings.questionnarie_list.button.edit')}}</button>
                    <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="left:-50px;">
                        <li><a href="{{url('/quote/request/'.md5(Config::get('app.salt.qa') . $item->id))}}" target="_blank">{{trans('settings.questionnarie_list.button.preview')}}</a></li>
                        <li><a href="#" class="delete_qa" id="{{$item->id}}">{{trans('settings.questionnarie_list.button.delete')}}</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

