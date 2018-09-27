<div class="gridster ready" >
    <ul>
        <?php
            $row = 1;
            $col = 1;
        ?>
        @if(!empty($requirements))
            @foreach($requirements as $requirement)
                <li id="{{$requirement->id}}" name="1" data-row="{{$row}}" data-col="{{$col++}}" data-sizex="1" data-sizey="1" class="gs-w" style="position: absolute; min-width: 140px;">
                    <div class="box box-no-shadow">
                        <div class="box-header">
                            <div style="font-size:12px;" id="t_priority">{{substr(Crypt::decrypt($requirement->subject),0 , 20)}}...</div>
                        </div><!-- /.box-header -->
                    </div>
                </li>
                <?php
                    if($col > 5){
                        $col = 1;
                        $row++;
                    }
                ?>
            @endforeach
        @endif  
    </ul>
</div>