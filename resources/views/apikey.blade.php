@extends("layouts/master")
@section("content")
<div class="row">
    <div class="col-lg-12">
        <h2>
            <span class="fa-stack fa-lg">
                <i class="fa fa-dot-circle-o fa-stack-2x"></i>
            </span>
            API Keys
            <a class="btn btn-primary pull-right" href="/createkey"><i class="fa fa-plus"></i> Create Key</a>
        </h2> 
        <div class="well" id="api-keys-list">
		<ul class="list-unstyled">
            <?php foreach($keys as $d): ?>
                <li style="padding-bottom: 10px">
                    <p class="pull-left"><strong><?php echo $d->name?></strong> <code><?php echo $d->key?></code></p>
                    <div class="pull-right">
                        <a class="btn btn-danger btn-sm" href="https://dash.geocod.io/apikey/delete?id=1188"><i class="fa fa-times"></i> Delete</a>
                    </div>
                    <div class="clearfix"></div>
                </li>
            <?php endforeach ?>
		</ul>
	    </div>
    </div>
</div>
@stop
