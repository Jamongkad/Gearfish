@extends("layouts/master")
@section("content")

<div class="row">
    <div class="col-lg-12">
        <h2>
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-table fa-stack-1x"></i>
            </span>
            Upload List
        </h2>
        <div class="well bs-component">
            <p class="info">
                We accept lists formatted as a CSV file. 
                The filesize can be up to 1 GB. For fastest processing time, we suggest that you remove unrelated columns.
            </p>
            <div id="current-container" style="float:left">
                <button type="button" id="current-browse-button" class="btn btn-primary">Browse...</button>
                <button type="button" id="current-start-upload" class="btn btn-success">Upload</button>
            </div>
            <div id="file_name" style="float:left;padding-left:10px;padding-top:7px"></div>
            <br/>
            <br/>
            <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var uploader = new plupload.Uploader({
            "runtimes":"html5",
            "browse_button":"current-browse-button",
            "max_retries": 3,
            "container":"current-container",
            "url":"/plupload", 
            filters : {
                mime_types: [
                    {title : "CSV files", extensions : "csv"},
                ]
            },
            "headers":{ "Accept": "application/json" },
            "chunk_size":"100kb"
        });

    uploader.init();
   
    uploader.bind('Error', function(up, file) { 
        alert(file.message);
    })
    
    uploader.bind('FilesAdded', function(up, file) {
        $('#file_name').text("CSV File: " + file[0].name);
    });

    uploader.bind('UploadProgress', function(up, file) {
        $('.progress-bar').show()
                          .css('width', file.percent+'%')
                          .text(file.percent+'%');
    });

    uploader.bind('UploadComplete', function(up, file) {

        console.log(file);
        console.log(up);

        var file = file;
 
        $('.progress-bar').fadeOut(2000, function() {
            var filename = file[0].name;

            $(this).hide().removeAttr('style').text('');
            $('#file_name').text(file[0].name + " successfully uploaded!").fadeOut(2000);
            //window.location = '/upload_success';     
            window.location = '/myapi';
        });

    });

    document.getElementById('current-start-upload').onclick = function() {
        uploader.start();
    };
</script>
@stop
