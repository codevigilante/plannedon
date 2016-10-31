<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Activity Planner v0.1</title>

    <!-- Bootstrap -->
    <link href="<?=base_url();?>assets/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Activity Planner v0.1</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <!--
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                    </li>
                </ul>
                <form class="navbar-form navbar-left">
                    <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                -->
                <ul class="nav navbar-nav navbar-right">
                    <li><button type="button" class="btn btn-default navbar-btn">Already planning? Login</button></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <div class="container">        
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="text-center">Flexible, Simple Daily Planning</h1>
                    <hr />
                    <p class="text-center">Planned On is a linear calendar app that allows you to plan upcoming and future activities/events without worrying about times.</p>
                    <p class="text-center">It's simple, useful, and super easy to use. Oh, and it's also <em><b><u>100% FREE</u></b></em>!</p>
                </div>

                <div class="col-md-4 well">
                    <h3 class="text-center">Get Started - It's Free!</h3>
                    <hr/>
                    <?php 
                        echo validation_errors(); 
                    ?>
                    <form role="form" id="start-form" data-parsley-validate data-parsely-ui-enabled="true" method="post" action="./home/getstarted">
                        <div class="form-group">
                            <label class="sr-only" for="inputName">Name</label>
                            <div class="input-group input-group-lg" id="inputName">
                                <input type="text" class="form-control" name="firstname" data-parsley-trigger="change" data-parsley-errors-messages-disabled data-parsley-class-handler="#inputName" placeholder="Name" required>
                                <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="inputEmail">Email</label>
                            <div class="input-group input-group-lg" id="inputEmail">
                                <input type="email" class="form-control" name="email" data-parsley-trigger="change" data-parsley-errors-messages-disabled data-parsley-class-handler="#inputEmail" placeholder="Email" required>
                                <div class="input-group-addon help-block with-errors"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="inputPassword">Password</label>
                            <div class="input-group input-group-lg" id="inputPassword">
                                <input type="password" class="form-control" name="password" data-parsley-trigger="change" data-parsley-errors-messages-disabled data-parsley-class-handler="#inputPassword" placeholder="Password" required>
                                <div class="input-group-addon"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Start Planning</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?=base_url();?>assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="<?=base_url();?>assets/js/node_modules/parsleyjs/dist/parsley.js"></script>
    <script>
        Parsley.options.errorClass = "has-error";
        Parsley.options.successClass = "has-success";
    </script>
  </body>
</html>