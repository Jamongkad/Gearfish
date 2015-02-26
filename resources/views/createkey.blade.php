@extends("layouts/master")
@section("content")
<div class="row">
    <div class="col-lg-12">
        <h2>
            <span class="fa-stack fa-lg">
                <i class="fa fa-dot-circle-o fa-stack-2x"></i>
            </span>
            Create API Key
        </h2>
        <form role="form" accept-charset="UTF-8" action="/createkey" method="POST">
            <div class="form-group">
                <label for="name">Name</label>	
                <input type="text" id="name" name="name" class="form-control">	
                <?php echo $errors->first('name', '<p style="color:#d9534f">:message</p>');?>
                <input type="hidden" name="companyID" value="<?php echo $companyID?>" />	
                <p class="help-block">Enter a name for your API key so you can identify it later.</p>
            </div>
            <input type="submit" value="Create" class="btn btn-primary">
        </form>
    </div>
</div>
@stop
