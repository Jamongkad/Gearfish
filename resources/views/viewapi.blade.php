@extends("layouts/master")
@section("content")
<script>
$(function() {
});
</script>
<div class="row"> 
    <div class="col-lg-12">
        <h2>
            <span ng-hide="renamingApi" class="ng-binding"><?php echo $upload_data->name?></span>
        </h2>
        <div class="row">
            <div class="col-md-6">
                <table ng-class="bugFixClass" class="table api-details bug-fix">
                    <tbody>
                        <tr>
                            <td>Endpoint</td>
                            <td>
                                <span style="color:#ccc">
                                    <a target="_blank" href="/api/cnaihzr8?apikey=naXXzCxA5eYoLFT5diYMzwH5TvoeqCtU">json</a>
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
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>API Key</h3>
                <div>
                    <pre style="color:#c7254e"><code class="api-key"><?php echo $upload_data->key?></code></pre>
                </div>
                <h3>Code Sample</h3>
                <pre><code>curl --include --request GET "https://www.gearfish.com/api/<?php echo $upload_data->companyID?>?page=0&apikey=<span class="api-key-id"><?php echo $upload_data->key?></span>"</code></pre>
            </div>
        </div>
    </div>
</div>
@stop
