<!DOCTYPE html>
<html lang="en" ng-app="ConfApp">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        -->

        <!-- Optional theme -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  
        <script src="{{ asset('/assets/js/jquery-2.1.3.min.js') }}"></script>
        <!--
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.4/angular.min.js"></script> 
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.3/angular-route.min.js"></script>
        <script src="{{ asset('/assets/app/app.module.js') }}"></script>
        <script src="{{ asset('/assets/app/components/home/homeController.js') }}"></script>
        <script src="{{ asset('/assets/app/shared/widgets/directives.js') }}"></script>
        <script src="{{ asset('/assets/app/shared/services/services.js') }}"></script>
        -->
 
        <script src="{{ asset('/assets/js/plupload.full.min.js') }}"></script>
        <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>

        <!-- Latest compiled and minified JavaScript 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script> 
        <script src="//cdnjs.cloudflare.com/ajax/libs/d3/3.4.13/d3.min.js"></script>
        -->

        <link rel="stylesheet" href="{{ asset('assets/font-awesome-4.1.0/css/font-awesome.min.css') }}">
    </head>
    <body>
        <div class="navbar navbar-default navbar-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo (Auth::check()) ? '/getstarted' : '/'?>">Gearfish</a>
                </div>
                <div id="navbar-main" class="navbar-collapse collapse">
                <?php if(Auth::check()) : ?>
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="/getstarted">
                                <i class="fa fa-reorder"></i>
                                Get Started
                            </a>
                        </li>
                        <li>
                            <a href="/apikey"> 
                                <i class="fa fa-dot-circle-o"></i>
                                API Keys
                            </a>
                        </li>                        
                        <!--
                        <li>
                            <a href="/usage">
                                <i class="fa fa-ioxhost"></i>
                                Usage
                            </a>
                        </li>
                        <li> 
                            <a href="/billing">
                                <i class="fa fa-usd"></i>
                                Billing
                            </a>
                        </li>
                        -->
                        <li> 
                            <a href="/myapi">
                                <i class="fa fa-cloud-upload"></i>
                                My API's
                            </a>
                        </li>
                        <!--
                        <li>
                            <a href="/docs">
                                <i class="fa fa-book"></i>
                                Documentation
                            </a>
                        </li>
                        -->
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/settings"> 
                                <i class="fa fa-gear"></i>
                                Settings
                            </a>
                        </li>
                                
                        <li>
                            <a href="/auth/logout">
                                <i class="fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul> 
                <?php else: ?>
                    <ul class="nav navbar-nav navbar-right">
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
                    </ul> 

                <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="container">
            @yield("content")
        </div>
    </body>
</html>
