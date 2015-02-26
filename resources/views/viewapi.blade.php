@extends("layouts/master")
@section("content")
<script>
$(function() {
    $("#apikey-div").hide();
    
    <?php if($chosenApiKey): ?>
        $("#apikey-div").show(); 
        $(".api-key").html("<?php echo $chosenApiKey->key?>");
        $(".api-key-id").html("<?php echo $chosenApiKey->key?>");
        $('select[name=apikey]').val("<?php echo $chosenApiKey->key?>").change();
    <?php endif ?>

    $('select[name=apikey]').change(function() {
        var apikey = $(this).val();
        var apikey_id = $(this).children('option:selected').attr('apikeyid');
        
        if(apikey) {
            $("#apikey-div").show(); 
            $(".api-key").html(apikey);
            $(".api-key-id").html(apikey);
            $.ajax({
                type: 'POST',
                url: '/attach_apikey',
                data: {'apikey_id': apikey_id, 'api_id': <?php echo $upload_data->id?>}
            });
        } else { 
            $("#apikey-div").hide(); 
        }       
    });
});
</script>
<div class="row"> 
    <div class="col-lg-12">
        <h2>
            <i class="fa fa-file-excel-o"></i>
            <span ng-hide="renamingApi" class="ng-binding"><?php echo $upload_data->name?></span>
        </h2>
        <div class="row">
            <div class="col-md-6">
                <table ng-class="bugFixClass" class="table api-details bug-fix">
                    <tbody>
                        <tr>
                            <td>Endpoint</td>
                            <td>
                                <span>
                                    JSON
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Created On</td>
                            <td> 
                                <?php echo $upload_data->created_at?>  
                            </td>
                        </tr>
                        <tr>
                            <td>Number of Records</td>
                            <td> 
                                <?php echo number_format($upload_data->records)?>  
                            </td>
                        </tr>
                        <tr>
                            <td>Number of Pages</td>
                            <td> 
                                <?php echo number_format($upload_data->pages)?>  
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Your API Keys</h3>
                <div> 
                    <?php if($apiKeys): ?>
                        <select class="form-control" name="apikey">
                            <option value="" selected>-- Choose an API key --</option>
                            <?php foreach($apiKeys as $key): ?>
                                <option value="<?php echo $key->key?>" apikeyid="<?php echo $key->id ?>"><?php echo $key->name?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <p class='info'>
                            You cannot use your API without a key! 
                        </p>
                        <a href="/createkey" class="btn btn-danger">Create your API key now</a>
                    <?php endif ?>
                </div>
                <?php if($apiKeys): ?>
                    <div id="apikey-div">
                        <h3>API Key</h3>
                        <div>
                            <pre style="color:#c7254e"><code class="api-key"></code></pre>
                        </div>
                        <h3>Code Sample</h3>
                        <pre><code>curl --include --request GET "http://dev.gearfish.com/api/<?php echo $upload_data->id?>?page=1&apikey=<span class="api-key-id"><?php echo ""; ?></span>"</code></pre>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
@stop
