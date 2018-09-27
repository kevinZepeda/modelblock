<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title>{{$project->project_name}} {{trans('office.sharepoint.title')}}</title>


	<!-- Include our stylesheet -->
    <link href="{{ asset('/assets/plugins/cutefilebrowser/styles.css') }}" rel="stylesheet">
      <!-- Ionicons -->
      <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />

      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
      <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>

      <script src="{{ asset('/assets/plugins/dropzone/dropzone.js') }}"></script>
 	<script>
 	$(document).ready(function(){
 		Dropzone.options.myAwesomeDropzone = {
            maxFile: 1,
            init: function() {
                this.on("complete", function() {
                    // If all files have been uploaded
                    if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                        var _this = this;
                        // Remove all files
                        _this.removeAllFiles();
                    }
                });
            },
            addedfile: function(file) {
            	$("form").block({
                    message: '<span style="color:gray;font-size:17px;">{{trans('office.sharepoint.please_wait')}}</span><br/><img style="padding-top:10px;" src="{{ asset('/assets/loading.gif') }}"/>',
                    css: {
                        backgroundColor: 'none',
                        opacity: 0.9,
                        border: '0px'
                    },
                    overlayCSS: {
                        backgroundColor: 'white',
                        opacity: 0.9,
                    }
                });
			},
            success: function(file, response){
                if(response.status == 'ok'){
                    location.reload();
                }else{
                    ktNotification('{{trans('office.error')}}',response.message, 2000, false);
                	$("form").unblock();
                }	
            },
            error: function(file, response){
                ktNotification('Upsssss',response.message, 2000, false);
            	$("form").unblock();
            },            
            dragover: function(file, response){
            	$(".overlay").css('display','table');
            	$(".overlay").fadeIn();        
            	$("#textnode ").fadeIn();   	
            },
            dragenter: function(file, response){
            	$(".overlay").css('display','table');
            	$(".overlay").fadeIn();        
            	$("#textnode ").fadeIn();           	
            },
            dragend: function(file, response){
            	$(".overlay").fadeOut();      
            	$("#textnode ").fadeOut();  
            },
            dragleave: function(file, response){
            	$(".overlay").fadeOut();      
            	$("#textnode ").fadeOut();  
            },
            drop: function(file, response){
            	$(".overlay").fadeOut();      
            	$("#textnode ").fadeOut();  
            },
	        clickable: false
        };

 		function ktNotification(title, message, delay, type){
            if(type) {
                var icon_url = '{{asset('/assets/ok.png')}}';
            }else{
                var icon_url = '{{asset('/assets/error.png')}}';
            }

            if($.isArray(message)) {
                var count = 1;
                var final_message = '';
                $.each(message, function (index, message) {
                    final_message = final_message + (count++) + '.' + message + '<br/>';
                });
            }else{
                var final_message = message;
            }

            $.notify({
                icon: icon_url,
                title: title,
                message: final_message
            },{
                placement: {
                    from: "bottom",
                    align: "right"
                },
                type: 'minimalist',
                delay: delay,
                newest_on_top: true,
                allow_dismiss: true,
                z_index: 99999999999,
                animate: {
                    enter: 'animated fadeInUp',
                    exit: 'animated fadeOutUp'
                },
                icon_type: 'image',
                template: '<div data-notify="container"  role="alert">' +
                '<span data-notify="message">{2}</span>' +
                '</div>'
            });
        }



  	});
    </script>

</head>
<body class="board">
<form action="{{ url('/api') }}" class="dropzone" id="my-awesome-dropzone"  method="post" enctype="multipart/form-data">
	<div class="overlay" >
		<div class="center">
			<i class="ion ion-android-upload"></i>&nbsp;Drop to upload&nbsp;<i class="ion ion-android-upload"></i>
		</div>
	</div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" id="event" name="event" value="sharepoint">
    <input type="hidden" id="project_id" name="project_id" value="{{$project->id}}">
    <div class="dz-message"></div>
	<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>

	<div class="filemanager">

		<div class="search">
			<input type="search" placeholder="{{trans('office.sharepoint.find_file')}}" />
		</div>

		<div class="breadcrumbs"></div>

		<ul class="data"></ul>

		<div class="nothingfound">
			<div class="nofiles"></div>
			<span>{{trans('office.sharepoint.no_files_found')}}</span>
		</div>

	</div>

</form>


	<!-- Include our script files -->
    <script src="{{ asset('/assets/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/bootstrap-growl/growl.js') }}" type="text/javascript"></script>
    <!-- block ui -->
    <script src="{{ asset('/assets/plugins/jquery-blockui/jquery.blockUI.js') }}" type="text/javascript"></script>

	<script>
            $(function(){

                var filemanager = $('.filemanager'),
                    breadcrumbs = $('.breadcrumbs'),
                    fileList = filemanager.find('.data');

                // Start by fetching the file data from scan.php with an AJAX request
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'files',
                    "project_id": '{{$project->id}}'
                };

                $.post(
                    '{{ url('/api') }}', 
                    post_data,
                    function(data) {

						var response = [data],
							currentPath = '',
							breadcrumbsUrls = [];

						var files = [];

						// This event listener monitors changes on the URL. We use it to
						// capture back/forward navigation in the browser.

						$(window).on('hashchange', function(){

							goto(window.location.hash);

							// We are triggering the event. This will execute 
							// this function on page load, so that we show the correct folder:

						}).trigger('hashchange');


						// Hiding and showing the search box

						filemanager.find('.search').click(function(){

							var search = $(this);

							//search.find('span').hide();
							search.find('input[type=search]').show().focus();

						});


						// Listening for keyboard input on the search field.
						// We are using the "input" event which detects cut and paste
						// in addition to keyboard input.

						filemanager.find('input').on('input', function(e){

							folders = [];
							files = [];

							var value = this.value.trim();

							if(value.length) {

								filemanager.addClass('searching');
								// Update the hash on every key stroke
								window.location.hash = 'search=' + value.trim();

							} else {
								filemanager.removeClass('searching');
								window.location.hash = ''
							}

						}).on('keyup', function(e){

							// Clicking 'ESC' button triggers focusout and cancels the search

							var search = $(this);

							if(e.keyCode == 27) {

								search.trigger('focusout');

							}

						}).focusout(function(e){

							// Cancel the search

							var search = $(this);

							if(!search.val().trim().length) {
								window.location.hash = '';
								//search.hide();
								search.parent().find('span').show();
							}

						});

						// Navigates to the given hash (path)

						function goto(hash) {

							hash = decodeURIComponent(hash).slice(1).split('=');

							if (hash.length) {
								var rendered = '';

								// if hash has search in it

								if (hash[0] === 'search') {

									filemanager.addClass('searching');
									rendered = searchData(response, hash[1].toLowerCase());

									if (rendered.length) {
										currentPath = hash[0];
										render(rendered);
									}
									else {
										render(rendered);
									}

								} else {
									filemanager.addClass('searching');
									currentPath = data.path;
									breadcrumbsUrls.push(data.path);
									render(searchByPath('/'));
								}
							}
						}

						// Splits a file path and turns it into clickable breadcrumbs

						function generateBreadcrumbs(nextDir){
							var path = nextDir.split('/').slice(0);
							for(var i=1;i<path.length;i++){
								path[i] = path[i-1]+ '/' +path[i];
							}
							return path;
						}

						// Locates a file by path

						function searchByPath(dir) {
							var path = dir.split('/'),
								demo = response,
								flag = 0;

							if(dir == '/'){
								demo = demo[0].items;
								return demo;
							}

							for(var i=0;i<path.length;i++){
								for(var j=0;j<demo.length;j++){
									if(demo[j].name === path[i]){
										flag = 1;
										demo = demo[j].items;
										break;
									}
								}
							}

							demo = flag ? demo : [];
							return demo;
						}


						// Recursively search through the file tree

						function searchData(data, searchTerms) {

							data.forEach(function(d){
								if(d.type === 'folder') {
									searchData(d.items,searchTerms);
								}
								else if(d.type === 'file') {
									if(d.name.toLowerCase().match(searchTerms)) {
										files.push(d);
									}
								}
							});
							return {files: files};
						}


						// Render the HTML for the file manager

						function render(data) {

							var scannedFiles = [];

							if(Array.isArray(data)) {

								data.forEach(function (d) {

									if (d.type === 'file') {
										scannedFiles.push(d);
									}

								});

							}
							else if(typeof data === 'object') {

								scannedFiles = data.files;

							}


							// Empty the old result and make the new one

							fileList.empty().hide();

							if(!scannedFiles.length) {
								filemanager.find('.nothingfound').show();
							}
							else {
								filemanager.find('.nothingfound').hide();
							}

							if(scannedFiles.length) {

								scannedFiles.forEach(function(f) {

									var fileSize = bytesToSize(f.size),
										name = escapeHTML(f.name),
										fileType = name.split('.'),
										icon = '<span class="icon file"></span>';

									fileType = fileType[fileType.length-1];

									icon = '<span class="icon file f-'+fileType+'">.'+fileType+'</span>';

									var file = $('<li class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize
										+'</span><span class="commands"><a href="{{url('/office/project/file/download/'.$project->id.'?file=')}}'+f.name+'" id="'+f.name+'" title="{{trans('office.sharepoint.download_file')}}" class="files dofile" style="color:gray;padding-left:20px;float:right;" download>'
										+'<i class="ion ion-android-download"></i></a>&nbsp;<a href="#" id="'+f.name+'" title="{{trans('office.sharepoint.delete_file')}}" class="files defile" style="color:gray;padding-left:20px;float:right;">'
										+'<i class="ion ion-trash-a"></i></a></span></li>');
									file.appendTo(fileList);
								});

							}

							// Generate the breadcrumbs

							var url = '';

							if(filemanager.hasClass('searching')){

								url = '<span><a href="{{url('/office/project/'.$project->id)}}" style="color:gray;" title="{{trans('office.sharepoint.back_to_project')}}"><i class="ion ion-arrow-return-left">&nbsp;</i></a> {{$project->project_name}} </span>';
								fileList.removeClass('animated');

							}
							else {

								fileList.addClass('animated');

								breadcrumbsUrls.forEach(function (u, i) {

									var name = u.split('/');

									if (i !== breadcrumbsUrls.length - 1) {
										url += '<a href="'+u+'"><span class="folderName">' + name[name.length-1] + '</span></a> <span class="arrow">→</span> ';
									}
									else {
										url += '<span class="folderName">' + name[name.length-1] + '</span>';
									}

								});

							}

							breadcrumbs.text('').append(url);
							fileList.show();
							// Show the generated elements

							fileList.animate({'display':'inline-block'});

						}


						// This function escapes special html characters in names

						function escapeHTML(text) {
							return text.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;');
						}

						// Convert file sizes from bytes to human readable units

						function bytesToSize(bytes) {
							var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
							if (bytes == 0) return '0 Bytes';
							var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
							return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
						}

                });

				$(document).on('click','.defile', function(){
					if(confirm("{{trans('office.sharepoint.delete_file_message')}}")){
						file = $(this).attr('id');

	                    var post_data = {
	                        "_token": '{{ csrf_token() }}',
	                        "event": 'share_delete',
	                        "project_id": {{$project->id}},
	                        "file": file                
	                    };
	                    $.post(
	                            '{{ url('/api') }}',
	                            post_data,
	                            function( data ) {
	                                console.log(data);
	                                if(data.status == 'ok' && data.action == true){
	                                    location.reload();
	                                }else{
	                                    ktNotification('{{trans('office.sharepoint.hm')}}}', '{{trans('office.sharepoint.hm_message')}}}', 2000, true)
	                                }
	                            },
	                            'json'
	                    );
					}
				});

            });

        </script>

</body>
</html>