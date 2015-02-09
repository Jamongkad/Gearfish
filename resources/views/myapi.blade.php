@extends("layouts/master")
@section("content")
<div class="row">
    <div class="col-lg-12">
        <h2>
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-cloud-upload fa-stack-1x"></i>
            </span>
            My API's
            <a class="btn btn-primary pull-right" href="/upload"><i class="fa fa-table"></i> Upload list</a>
        </h2>
        <table class="table table-condensed table-import">
        <tr>
            <th class="col-md-2">Started</th>
            <th class="col-md-3">File</th>
            <th class="col-md-1">Records</th>
            <th class="col-md-3">Status</th>
            <th class="col-md-1">&nbsp;</th>
	    </tr>
        <?php foreach($csv as $d): ?>
            <tr>
                <td><?php echo $d->created_at?></td>
                <td><?php echo $d->name?></td>
                <td class="rows"><?php echo $d->records?></td>
                <td class="message">
                    Completed		
                </td>
                <td>
                    <a class="btn btn-success btn-sm" href="/viewapi/<?php echo $d->id?>"><i class="fa fa-download"></i> View API</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</div>

@stop
