
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
                <a class="navbar-brand" href="/">Planned On <?=APP_VERSION?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php if (isset($authed) && $authed == TRUE) : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome, <?=$user_name;?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/calendar">Goto My Calendar</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/login/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php elseif (isset($show_login) && $show_login == TRUE) : ?>
                        <li><a href="/login">Login</a></li>
                    <?php endif; ?>
                </ul>
                <!--
                <form role="form" id="start-form" data-parsley-validate data-parsely-ui-enabled="true" method="post" action="/login/validate" class="navbar-form navbar-left">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Quick Login</button>
                </form>
                -->
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>