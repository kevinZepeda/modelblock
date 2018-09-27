<div style="width:100%;text-align:center;background-color:#DEE2E7   !important;"  id="top-menu-bar">
    <header class="main-header" style="max-width: 1100px;margin: 0 auto;text-align:left;background-color:#DEE2E7   !important;z-index:500;">
        <nav class="navbar navbar-static-top" role="navigation" style="background-color:#DEE2E7   !important;margin-left:0px;">
            <!-- Sidebar toggle button-->

            @if(Auth::user()->isAdmin() || Auth::user()->canManage())
                @if(is_object($board))
                    <div style="float:left;padding-top:12px;" id="board-search">
                            <select id="boards-list" style="width:{{(empty($board->child_board))?700:740}}px;height:100px;">
                                <option></option>
                            </select>
                    </div>
                    <div style="float:left;padding-top:12px;display:none;" id="user-search">
                        <select id="search-user-select" style="width:{{(empty($board->child_board))?700:740}}px@else;height:100px;">
                            <option></option>
                        </select>
                    </div>
                @else
                    <div style="float:left;padding-top:12px;width:91%;" id="board-search">
                        <select id="boards-list" style="width:100%;height:100px;">
                            <option></option>
                        </select>
                    </div>
                @endif
                <div class="navbar-custom-menu toolbar-menu">
                    <ul class="nav navbar-nav" style="font-size:15px;">
                        @if(is_object($board))
                            <li class="dropdown messages-menu" id="edit-board">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="edit" name="">
                                    <i class="ion-edit"></i></a>
                            </li>                         
                            <li class="dropdown tasks-menu user user-menu" id="user-filter" style="display:none;">
                                <a href="#"  class="dropdown-toggle search" data-toggle="dropdown" style="color:gray !important;width:43px;text-align:center;">
                                    <i class="ion-ios-arrow-forward"></i></a>
                            </li>
                            <li class="dropdown tasks-menu user user-menu" id="board-filter">
                                <a href="#"  class="dropdown-toggle search" data-toggle="dropdown"  style="color:gray !important;width:43px;text-align:center;">
                                    <i class="ion-android-search"></i></a>
                            </li>
                        @endif

                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="add-new-board">
                                <i class="ion-easel"></i></a>
                        </li>
                        @if(is_object($board))
                            @if(empty($board->child_board))
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="add-child-board">
                                    <i class="ion-fork-repo"></i></a>
                            </li>
                            @endif
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="new-task">
                                    <i class="ion-ios-browsers-outline"></i></a>
                            </li>

                            @if($board->default == 1)
                                <li class="dropdown messages-menu" id="default-board" >
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="default"  name="{{$board->id}}">
                                        <i class="ion-android-star"></i></a>
                                </li>
                                <li class="dropdown messages-menu" style="display:none;" id="undefault-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="undefault"  name="{{$board->id}}">
                                        <i class="ion-android-star-outline"></i></a>
                                </li>
                            @else
                                    <li class="dropdown messages-menu" style="display:none;" id="default-board">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="default"  name="{{$board->id}}">
                                            <i class="ion-android-star"></i></a>
                                    </li>
                                    <li class="dropdown messages-menu" id="undefault-board">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="undefault"  name="{{$board->id}}">
                                            <i class="ion-android-star-outline"></i></a>
                                    </li>
                            @endif

                            @if($board->public == 0)
                               <!--  <li class="dropdown messages-menu" id="public-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="make-public" name="{{$board->id}}">
                                        <i class="ion-eye-disabled"></i></a>
                                </li>
                                <li class="dropdown messages-menu" style="display:none;" id="unpublic-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="unpublish" name="{{$board->id}}">
                                        <i class="ion-eye"></i></a>
                                </li> -->
                            @else
<!--                                 <li class="dropdown messages-menu" style="display:none;" id="public-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="make-public" name="{{$board->id}}">
                                        <i class="ion-eye-disabled"></i></a>
                                </li>
                                <li class="dropdown messages-menu" id="unpublic-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="unpublish" name="{{$board->id}}">
                                        <i class="ion-eye"></i></a>
                                </li> -->
                            @endif
                            @if($board->lock == 0)
                                <li class="dropdown messages-menu" id="lock-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="lock"  name="{{$board->id}}">
                                        <i class="ion-unlocked"></i></a>
                                </li>
                                <li class="dropdown messages-menu" style="display:none;" id="unlock-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="unlock"  name="{{$board->id}}">
                                        <i class="ion-locked"></i></a>
                                </li>
                            @else
                                <li class="dropdown messages-menu" style="display:none;" id="lock-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="lock"  name="{{$board->id}}">
                                        <i class="ion-unlocked"></i></a>
                                </li>
                                <li class="dropdown messages-menu" id="unlock-board">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:gray !important;" id="unlock"  name="{{$board->id}}">
                                        <i class="ion-locked"></i></a>
                                </li>
                            @endif

                            @if(isset($select) && empty($select) && $select != Auth::id())
                                <li class="dropdown messages-menu user-tasks">
                                    <a href="{{ url("/board/".Auth::id()."$hash") }}" class="dropdown-toggle" style="color:gray !important;width:45px;text-align:center;" id="user-tasks">
                                        <i class="ion-ios-person"></i></a>
                                </li>
                            @else
                                <li class="dropdown messages-menu all-tasks">
                                    <a href="{{ url("/board$hash") }}" class="dropdown-toggle" style="color:gray !important;width:45px;text-align:center;" id="all-tasks">
                                        <i class="ion-ios-people"></i></a>
                                </li>
                            @endif

                            <li class="dropdown messages-menu" id="delete-board">
                                <a href="#" class="dropdown-toggle"  style="color:gray !important;">
                                    <i class="ion-ios-trash"></i></a>
                            </li>

                        @endif
                    </ul>
                </div>
            @elseif(!Auth::user()->isClient())
                <div style="float:left;padding-top:@if(is_object($board)) 12px @else 6px;padding-bottom:6px @endif;width:@if(is_object($board)) 90% @else 100% @endif;" id="board-search">
                    <select id="boards-list" style="width:100%;height:100px;">
                        <option></option>
                    </select>
                </div>
                <div style="display:none;float:left;padding-top:@if(is_object($board)) 12px @else 6px;padding-bottom:6px @endif;width:@if(is_object($board)) 90% @else 100% @endif;" id="user-search">
                    <select id="search-user-select" style="width:100%;height:100px;">
                        <option></option>
                    </select>
                </div>

                @if(is_object($board))
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav" style="font-size:15px;">

                            <li class="dropdown tasks-menu user user-menu" id="user-filter" style="display:none;">
                                <a href="#"  class="dropdown-toggle search" data-toggle="dropdown" style="color:gray !important;width:43px;text-align:center;">
                                    <i class="ion-ios-arrow-forward"></i></a>
                            </li>
                            <li class="dropdown tasks-menu user user-menu" id="board-filter">
                                <a href="#"  class="dropdown-toggle search" data-toggle="dropdown"  style="color:gray !important;width:43px;text-align:center;">
                                    <i class="ion-android-search"></i></a>
                            </li>

                            @if(isset($select) && empty($select) && $select != Auth::id())
                                <li class="dropdown messages-menu all-tasks">
                                    <a href="{{ url("/board/team$hash") }}" class="dropdown-toggle" style="color:gray !important;width:45px;text-align:center;" id="all-tasks">
                                        <i class="ion-ios-people"></i></a>
                                </li>
                            @else
                                <li class="dropdown messages-menu user-tasks">
                                    <a href="{{ url("/board$hash") }}" class="dropdown-toggle" style="color:gray !important;width:45px;text-align:center;" id="user-tasks">
                                        <i class="ion-ios-person"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            @else
                <div style="float:left;padding-top: 8px;padding-bottom:8px;width:@if(is_object($board)) 100% @else 100% @endif;" id="board-search">
                    <select id="boards-list" style="width:100%;height:100px;">
                        <option></option>
                    </select>
                </div>
            @endif
        </nav>
    </header>
</div>