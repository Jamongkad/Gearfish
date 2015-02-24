@extends("layouts/masterhome")
@section("content")
<div class="row"> 

    <section id="banner">
        <h1 style="color:#000;font-weight:bold">Convert <span style="color:#d43f3a">CSV's</span> into actionable data easily.</h1>
    </section>

  
    <div class="col-md-12">
        <div class="text-center">
            <h1>Ridiculously easy CSV to JSON data conversion.</h1>
            <p>Need to convert that large csv file for marketing this week?</p>
            <p>Client has a 250,000 row file that needs to be encoded in your RDBMS?</p>
            <p>Don't waste your time coding up yet another CSV reader in your app.</p>
            <p><a href="/auth/register" class="btn btn-lg btn-primary">Get Started</a></p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="text-center">
            <h2>How it Works</h2>
            <p>Upload a csv file and we'll take care of the rest! To get your data use our simple REST API.</p>
            <p>
                <a href="/auth/login" class="btn btn-info btn-lg">Upload CSV</a>
                <a href="/myapi" class="btn btn-info btn-lg">Use our REST API</a>
            </p>
        </div>
    </div>
    <div class="col-md-12">
        <pre class="terminal col-center-block">$ curl "https://dev.gearfish.com/api/9?page=1&apikey=1030946d60ecc6d634dee00171c9"
{"total_pages":17,
 "paging":{
     "prev_page":null,
     "next_page":2
 },
 "data":[
     ["abercda01","1871","1","TRO","NA","SS","1","","","1","3","2","0","","","","",""],
     ["addybo01","1871","1","RC1","NA","2B","22","","","67","72","42","5","","","","",""],
     ["addybo01","1871","1","RC1","NA","SS","3","","","8","14","7","0","","","","",""],
     ["allisar01","1871","1","CL1","NA","2B","2","","","1","4","0","0","","","","",""],
     ["allisar01","1871","1","CL1","NA","OF","29","","","51","3","7","1","","","","",""],
     ["allisdo01","1871","1","WS3","NA","C","27","","","68","15","20","4","0","","","",""]
 ]   
}</pre>
    </div>

</div>
@stop
