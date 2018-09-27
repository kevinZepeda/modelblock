<!-- =============================================== -->
<div class="board">
    <div class="wrapper" style="background: none;padding-top:7px;">
        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background:none;text-align:center;font-size:30px;color:gray;padding-top:300px;opacity:0.4;">
            @if(Auth::user()->isAdmin() || Auth::user()->canManage())
                @if((@$block == 'customer' || @$block == 'clean')  && @$subblock != 'details')
                    <i class="ion-arrow-up-c"></i> <div style="display:inline-block;" id="info-label">{{trans('office.select_or_create_customer')}}</div> <i class="ion-arrow-up-c"></i>
                @else
                    <i class="ion-arrow-up-c"></i> <div style="display:inline-block;" id="info-label">{{trans('office.select_or_create_project')}}</div> <i class="ion-arrow-up-c"></i>
                @endif
            @else
                @if((@$block == 'customer' || @$block == 'clean')  && @$subblock != 'details')
                    <i class="ion-arrow-up-c"></i> {{trans('office.select_customer')}} <i class="ion-arrow-up-c"></i>
                @else
                    <i class="ion-arrow-up-c"></i> {{trans('office.select_project')}} <i class="ion-arrow-up-c"></i>
                @endif
            @endif

        </div><!-- /.content-wrapper -->

    </div><!-- ./wrapper -->
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