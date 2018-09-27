<!-- =============================================== -->
<div>
    <div class="wrapper">
        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content" style="padding-top:0px;">

                <div class="row">
                    @include('office.office-customer-menu')
                    <div class="col-md-10 tab-right" style="min-height:600px;">
                        @include('office.office-customer-'.$subblock)                    
                    </div>
                </div>
            </section> <!-- /.content -->

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