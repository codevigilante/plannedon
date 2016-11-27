
    <div class="container">        
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="text-center">Flexible, Simple Daily Planning</h1>
                    <hr />
                    <p class="text-center">Planned On is a linear calendar app that allows you to plan upcoming activities and events without worrying about times.</p>
                    <p class="text-center">It's simple, useful, and super easy to use. Oh, and it's also <em><b><u>100% FREE</u></b></em>!</p>
                </div>

                <div class="col-md-4 well">
                    <h3 class="text-center">Get Started - It's Free!</h3>
                    <hr/>
                    <?php if (isset($form_errors) && $form_errors == TRUE) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php 
                                echo validation_errors();
                            ?>
                        </div>
                    <?php elseif (isset($duplicate) && $duplicate == TRUE) : ?>
                        <div class="alert alert-danger" role="alert">
                            Hmmm, that email already exists. <a href="/login">Login</a>, maybe?                            
                        </div>
                    <?php endif; ?>
                    <form role="form" id="start-form" data-parsley-validate data-parsely-ui-enabled="true" method="post" action="/home/getstarted">
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
                        <!--
                        <div class="form-group">
                            <label class="sr-only" for="inputPassword">Password</label>
                            <div class="input-group input-group-lg" id="inputPassword">
                                <input type="password" class="form-control" name="password" data-parsley-trigger="change" data-parsley-errors-messages-disabled data-parsley-class-handler="#inputPassword" placeholder="Password" required>
                                <div class="input-group-addon"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></div>
                            </div>
                        </div>
                        -->
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