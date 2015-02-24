@extends("layouts/master")
@section("content")
<div class="row">
    <div class="col-lg-12">
        <h2>
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-reorder fa-stack-1x"></i>
            </span>
            Get Started
        </h2>

        <div class="well bs-component">
            <p class="info">
                Do you want to upload your csv and integrate our REST API in your app? Head on over My API's and we'll take care of the rest!
            </p>
            <div class="text-center">
            <a href="/myapi" class="btn btn-lg btn-info">My API's</a>
            </div>
        </div>
    </div>
</div>
@stop
