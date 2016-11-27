    
    <div class="container">
        <div class="row">  
            <div class="col-md-4"></div>    
            <div class="col-md-4 well">
                <h3 class="text-center">Recover Password</h3>
                <hr/>
                <ol class="list-group">
                   <li class="list-group-item">Enter your email address</li>
                   <li class="list-group-item">We'll make a new, temporary password for you and send it to your email</li>
                   <li class="list-group-item">When you login using that temporary password, we'll prompt you to create a new one</li> 
                </ol>
                <?php if (isset($form_errors) && $form_errors == TRUE) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php 
                            echo validation_errors();
                        ?>
                    </div>
                <?php elseif (isset($unfound) && $unfound == TRUE) : ?>
                    <div class="alert alert-danger" role="alert">
                        Either you don't exist or you're being naughty...
                    </div>
                <?php endif; ?>
                <form role="form" id="start-form" data-parsley-validate data-parsely-ui-enabled="true" method="post" action="/login/recover">
                    <div class="form-group">
                        <label class="sr-only" for="inputEmail">Email</label>
                        <div class="input-group input-group-lg" id="inputEmail">
                            <input type="email" class="form-control" name="email" data-parsley-trigger="change" data-parsley-errors-messages-disabled data-parsley-class-handler="#inputEmail" placeholder="Email" required>
                            <div class="input-group-addon help-block with-errors"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Recover</button>
                </form>
            </div>
            <div class="col-md-4"></div>
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