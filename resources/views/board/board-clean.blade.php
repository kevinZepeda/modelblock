<!-- =============================================== -->
<div class="board">
    <div class="wrapper" style="background: none;padding-top:7px;">
        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background:none;text-align:center;font-size:30px;color:gray;padding-top:300px;opacity:0.4;">
            @if(Auth::user()->isAdmin() || Auth::user()->canManage())
                <i class="ion-arrow-up-c"></i> {{trans('board.no_entries')}} <i class="ion-arrow-up-c"></i>
            @else
                <i class="ion-arrow-up-c"></i> {{trans('board.no_entries_user')}} <i class="ion-arrow-up-c"></i>
            @endif

        </div><!-- /.content-wrapper -->

    </div><!-- ./wrapper -->
</div>

<div id="new-kanban" title="CREATE A NEW BOARD">
    <form role="form" method="POST" action="{{ url('/api/new-board') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input style="width:100%;text-align:center;" placeholder="NAME" id="new-board-name" name="new-board-name"/>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input style="width:100%;text-align:center;" placeholder="DESCRIPTION" id="new-board-desc" name="new-board-desc"/>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <select style="width:100%;"  id="new-board-template" name="new-board-template">
                </select>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;" id="custom_col">
            <div class="col-md-12" style="padding-right: 0px;">
                <select class="js-example-tags form-control" multiple="" tabindex="-1" style="display: none;width:100%;text-align:center;" id="new-board-col" name="new-board-col">
                </select>
            </div>
        </div>
        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <input type="text" class="form-control pull-right" style="text-align:center;width:100% !important;height:24px;" placeholder="DATE RANGE" id="new-board-date" name="new-board-date"/>
            </div>
        </div>
    </form>
</div>

<div style="background-color:#fff">

    <div class="wrapper">

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <?php echo Config::get('app.copyright.terms'); ?>
            </div>
            <?php echo Config::get('app.copyright.html'); ?>
        </footer>

    </div><!-- ./wrapper -->

</div>