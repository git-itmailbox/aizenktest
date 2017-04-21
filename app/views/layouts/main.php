<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAIN LAYOUT</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/jquery-ui.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<nav role="navigation" class="navbar navbar-default">

    <!-- Brand and toggle get grouped for better mobile display -->

    <div class="navbar-header">

        <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">

            <span class="sr-only">Toggle navigation</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

        </button>

        <a href="/" class="navbar-brand">Тест Айзенка</a>

    </div>

    <!-- Collection of nav links and other content for toggling -->

    <div id="navbarCollapse" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li><a href="#">#some menu item</a></li>
        </ul>
        <div class="col-md-3 pull-right">
            <div class="input-group ">

                <input type="hidden" id="user_id" name="user_id">
                <input type="text" class="form-control" id="search" placeholder="Найти тест юзера ...">
                <span class="input-group-btn">
                <button class="btn btn-primary" id="btnSearch" type="button">Go!</button>
            </span>

            </div>

        </div>
    </div>
</nav>


<?= $content ?>

<script src="/js/jquery.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<!--<script>jQuery.noConflict()</script>-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/js/main.js"></script>
</body>
</html>