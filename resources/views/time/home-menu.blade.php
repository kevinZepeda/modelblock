<div style="width:100%;text-align:center;background-color:#DEE2E7!important;z-index:9999999999;" id="top-menu-bar">
    <header id="top-menu-bar" class="main-header" style="max-width: 1100px;margin: 0 auto;text-align:left;background-color:#DEE2E7 !important;z-index:500;">
        <nav class="navbar navbar-static-top" role="navigation" style="background-color:#DEE2E7 !important;margin-left:0px;">
            <?php
                if(isset($_POST['event'])){
                    $s1 = 'display:none;';
                    $s2 = '';
                }else{
                    $s1 = '';
                    $s2 = 'display:none;';
                }
            ?>
            <div id="new-entry" style="{{$s1}}">
                <!-- Sidebar toggle button-->
                <div style="float:left;padding-top:12px;">
                    <div class="form-group has-feedback" style="margin-bottom:0px;">
                      <span class="ion ion-clock form-control-feedback" style="color:gray;font-size:20px;right:120px;padding-top:4px;"></span>
                      <input type="text" class="form-control" name="time" id="time" value="" placeholder="{{trans('timesheets.toolbar.time_input')}}" style="height:28px;padding-right:0px;width:150px;border-radius:5px !important; border:0px solid #B8B8B8;background-color:#ecf0f5 !important;text-align:center;">
                    </div>
                </div>

                <div style="float:left;padding-top:12px;padding-left:10px;">
                    <div class="form-group has-feedback" style="margin-bottom:0px;">
                      <span class="ion ion-ios-calendar-outline form-control-feedback" style="color:gray;font-size:20px;right:120px;padding-top:4px;"></span>
                      <input type="text" class="form-control pull-right" name="date" id="date" style="height:28px;padding-right:0px;width:150px;border-radius:5px !important; border:0px solid #B8B8B8;background-color:#ecf0f5 !important;text-align:center;" placeholder="{{trans('timesheets.toolbar.date')}}"/>
                    </div>
                </div>

                <div style="float:left;padding-left:10px;padding-top:12px;">
                    <div class="form-group has-feedback timesheet" style="margin-bottom:0px;">
                        <select class="description" style="padding-right:0px;width:250px;border-radius:5px !important; border:0px solid #B8B8B8 !important;background-color:#ecf0f5 !important;text-align:center;">
                            <option></option>
                        </select>
                    </div>
                </div>

                <div style="float:left;padding-left:10px;padding-top:12px;">
                    <div class="form-group has-feedback" style="margin-bottom:0px;">
                      <span class="ion ion-navicon form-control-feedback" style="color:gray;font-size:20px;right:330px;padding-top:4px;"></span>
                      <input type="text" class="form-control" name="comment" id="comment" value="" placeholder="{{trans('timesheets.toolbar.comment')}}" style="height:28px;padding-right:0px;width:360px;border-radius:5px !important; border:0px solid #B8B8B8;background-color:#ecf0f5 !important;text-align:left;padding-left:25px;">
                    </div>
                </div>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav" style="font-size:15px;">
                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="add-time-entry">
                            <i class="ion ion-ios-checkmark-outline"></i></a>
                        </li>
                        <li class="dropdown messages-menu" id="start-timer">
                            <a href="#" class="dropdown-toggle"  style="color:gray !important;" >
                            <i class="ion ion-ios-stopwatch-outline"></i></a>
                        </li>
                        <li class="dropdown messages-menu" id="stop-timer" style="display:none;">
                            <a href="#" class="dropdown-toggle"  style="color:gray !important;" >
                            <i class="ion ion-ios-stopwatch"></i></a>
                        </li>

                        <li class="dropdown messages-menu" id="explain-more">
                            <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="switch-to-report">
                            <i class="ion ion-ios-toggle-outline"></i></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="time-report" style="{{$s2}}">
                <!-- Sidebar toggle button-->
                <form role="form" method="POST" action="{{ url('/') }}" id="refresh-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="event" value="date_range" />
                    <div style="float:left;padding-top:12px;">
                        <div class="form-group has-feedback" style="margin-bottom:0px;">
                          <span class="ion ion-ios-calendar-outline form-control-feedback" style="color:gray;font-size:20px;right:220px;padding-top:4px;"></span>
                          <input type="text" value="{{@$_POST['dater']}}" class="form-control pull-right" name="dater" id="dater" style="height:28px;padding-right:0px;width:250px;border-radius:5px !important; border:0px solid #B8B8B8;background-color:#ecf0f5 !important;text-align:center;" placeholder="{{trans('timesheets.toolbar.date_from')}}"/>
                        </div>
                    </div>

                    <div style="float:left;padding-left:10px;padding-top:12px;">
                        <div class="form-group has-feedback timesheet" style="margin-bottom:0px;">
                            <select class="users" name="user" id="user" style="padding-right:0px;width:250px;border-radius:5px !important; border:0px solid #B8B8B8 !important;background-color:#ecf0f5 !important;text-align:center;">
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div style="float:left;padding-left:10px;padding-top:12px;">
                        <div class="form-group has-feedback timesheet" style="margin-bottom:0px;">
                            <select class="projects" name="project" id="project" style="padding-right:0px;width:350px;border-radius:5px !important; border:0px solid #B8B8B8 !important;background-color:#ecf0f5 !important;text-align:center;">
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav" style="font-size:15px;">
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="reset-report">
                                <i class="ion ion-backspace"></i></a>
                            </li>

                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="refresh-report">
                                <i class="ion ion-refresh"></i></a>
                            </li>

                            <li class="dropdown messages-menu" id="explain-more">
                                <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="switch-to-entry">
                                <i class="ion ion-ios-toggle"></i></a>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </nav>
    </header>
</div>