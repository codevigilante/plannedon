
    <div class="container">        
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="text-center">Waste Less Time, Get Shit Done</h1>
                    <h2 class="text-center">Daily Planning For Smart People With Important Crap To Do</h2>
                    <hr />
                    <p class="text-center">Planned On is a linear calendar app that allows you to plan activities, events, and todos without worrying about times.</p>
                    <p class="text-center">It's simple, useful, shareable, and super easy to use.</p>
                </div>

                <div class="col-md-4 well">
                    <h3 class="text-center">Get Started</h3>
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

        <h1 class="text-center" style="margin-bottom:30px;">Six Mega-Cool Reasons To Use Planned On...</h1>
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-2">
                    <h1 class="text-center">1</h1>
                </div>
                <div class="col-md-10">          
                    <p><b>Do you have trouble remembering to do things?</b> I do. That's one of the reasons I created this thing, so I could remember all the things I care about doing and all the stupid little things I have to do in order to survive in the modern world. Like go to the dentist, or buy groceries and shit. <em>Planned On will help you remember too, without worrying about hard time deadlines</em>.</p>
                </div>
            </div>
        </div>
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-2">
                    <h1 class="text-center">2</h1>
                </div>
                <div class="col-md-10">          
                    <p><b>Do you wish you were more organized?</b> My life used to be a series of bad decisions and impulses. Since I started planning my days and weeks, I'm way more organized and get way more shit done. It feels good feeling in control, even if it's really an illusion. <em>Planned On can help you get and stay organized, easily</em>.</p>
                </div>
            </div>
        </div>
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-2">
                    <h1 class="text-center">3</h1>
                </div>
                <div class="col-md-10">          
                    <p><b>Would you like to procrastinate less?</b> I say "less" because there's no way to eliminate it completely. There's just some things that we don't really want to do, like taking out the trash or listening to our significant other bitch about what a slob we've become. <em>Planned On will help you limit extreme procrastination, and might even cure mild procrastination</em>.</p>
                </div>
            </div>
        </div>
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-2">
                    <h1 class="text-center">4</h1>
                </div>
                <div class="col-md-10">          
                    <p><b>Do you have things you want to do but have a hard time finding the time?</b> I believe that people make time for the things they truly care about. But sometimes, it's hard because modern day life can be so fucking demanding at times. So if someone keeps blowing you off claiming they're too "busy" to make time for you, then, well, they really don't give a shit about you too much. <em>Planned On will help you not be that person that is so fucking "busy" they can't return a simple text message</em>. And Hell, you might even find yourself with some extra time to kill. Yeah!</p>
                </div>
            </div>
        </div>

        <div class="jumbotron">
            <div class="row">
                <div class="col-md-2">
                    <h1 class="text-center">5</h1>
                </div>
                <div class="col-md-10">          
                    <p><b>Are you smart?</b> This is a dumb question because of course you are. You wouldn't be here otherwise. <em>Planned On is only for smart people who have shit to do that matters</em>. If you're dumb and ain't got shit you care about, then what the fuck are you doing here? Go watch Star Trek re-runs while gulping down a gallon of ice cream.</p>
                </div>
            </div>
        </div>
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-2">
                    <h1 class="text-center">6</h1>
                </div>
                <div class="col-md-10">          
                    <p><b>Do you have a hard time using traditional calendar apps?</b> Because they're stupid and cumbersome and full of features you don't need. And everything feels like a boring meeting you have to attend with a bunch of people you hate, and if you miss the meeting and do something fun instead, you feel guilty. <em>Planned On frees you from this prison</em>.</p>
                </div>
            </div>
        </div>
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-2">
                    <h2 class="text-center">BONUS!</h2>
                </div>
                <div class="col-md-10">          
                    <p><b>Do you like free things?</b> Because, fuck yeah you like FREE shit! <em>Planned On is always FREE forever, and soon there'll be an iPhone app</em>. And if you don't have an iPhone, then what the fuck is wrong with you?</p>
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