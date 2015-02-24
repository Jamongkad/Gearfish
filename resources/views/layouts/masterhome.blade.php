<!DOCTYPE html>
<html lang="en" ng-app="ConfApp">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Optional theme -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  
        <script src="{{ asset('/assets/js/jquery-2.1.3.min.js') }}"></script>
 
        <script src="{{ asset('/assets/js/plupload.full.min.js') }}"></script>
        <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>

        <link rel="stylesheet" href="{{ asset('assets/font-awesome-4.1.0/css/font-awesome.min.css') }}">
    </head>
    <body>
        <div class="navbar navbar-default navbar-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo (Auth::check()) ? '/getstarted' : '/'?>">Gearfish</a>
                </div>
                <div id="navbar-main" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/"> 
                                Home
                            </a>
                        </li>  
                        <li>
                            <a href="/auth/login"> 
                                Login
                            </a>
                        </li>
                                
                        <li>
                            <a href="/auth/register">
                                Register
                            </a>
                        </li>
                        <li>
                            <a href="/contact">
                                Contact
                            </a>
                        </li>
                    </ul> 
                </div>
            </div>
        </div>
        <div class="container">
            @yield("content")
        </div>
    </body>
</html>
