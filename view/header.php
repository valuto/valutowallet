<!DOCTYPE HTML>

<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <link href="//netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/wallet.css?v=5" rel="stylesheet">
        <link href="/assets/css/disclaimer.css?v=3" rel="stylesheet">
		<link href="/assets/css/languages.min.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.6.0/moment.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/layout.js?v=4"></script>
        <!-- End Bootstrap include stuff-->
        <title><?=config('app', 'fullname')?>wallet</title>
		<link rel="icon" href="/assets/img/favicon.ico.png">
        <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>
    </head>
    
    
    <body>

    	<header>
	    		
    		<nav role="headerbar">

				<div class="container-fluid">
					<div>
						<a class="navbar-brand" href="index.php"><img src="/assets/img/valutowalletlogo.png" width="180" /></a>
					</div>
					<div class="dropdown langselect navbar-right">
						<button class="btn btn-default navbar-btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							Language
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="language-selector">
							<li data-value="en">
								<span class="lang-sm lang-lbl" lang="en"></span>
							</li>
                            <li data-value="es">
                                <span class="lang-sm lang-lbl" lang="es"></span>
                            </li>
                            <li data-value="de">
                                <span class="lang-sm lang-lbl" lang="de"></span>
                            </li>
                            <li data-value="da">
                                <span class="lang-sm lang-lbl" lang="da"></span>
                            </li><!--
							<li data-value="grc">
								<span class="lang-sm lang-lbl" lang="el"></span>
							</li>
							<li data-value="zho">
								<span class="lang-sm lang-lbl" lang="zh"></span>
							</li>
							<li data-value="ita">
								<span class="lang-sm lang-lbl" lang="it"></span>
							</li>
							<li data-value="por">
								<span class="lang-sm lang-lbl" lang="pt"></span>
							</li>
							<li data-value="hin">
								<span class="lang-sm lang-lbl" lang="hi"></span>
							</li>
							<li data-value="tgl">
								<span class="lang-sm"></span>Tagalog
							</li>
							<li data-value="rus">
								<span class="lang-sm lang-lbl" lang="ru"></span>
							</li>
							<li data-value="nld">
								<span class="lang-sm lang-lbl" lang="nl"></span>
							</li>
							<li data-value="fra">
								<span class="lang-sm lang-lbl" lang="fr"></span>
							</li>
							<li data-value="tur">
								<span class="lang-sm lang-lbl" lang="tr"></span>
							</li>
							<li data-value="vie">
								<span class="lang-sm lang-lbl" lang="vi"></span>
							</li>
							<li data-value="jpn">
								<span class="lang-sm lang-lbl" lang="ja"></span>
							</li>
							<li data-value="kor">
								<span class="lang-sm lang-lbl" lang="ko"></span>
							</li>
							<li data-value="ara">
								<span class="lang-sm lang-lbl" lang="ar"></span>
							</li>-->
						</ul>
					</div>
				</div>

			</nav>

	        <nav class="navbar navbar-default" role="navigation" id="navigation">
				<div class="container-fluid">

						<ul class="nav navbar-nav" v-on:click.prevent>
							<?php if(isset($_SESSION['user_session']) && !empty($_SESSION['user_session'])): ?>
								<li class="active">
									<a href="#" id="menuWalletItem" v-on:click="makeActive('wallet', $event)" title="<?php echo lang('MASTER_MENU_WALLET'); ?>"><?php echo lang('MASTER_MENU_WALLET'); ?></a>
								</li>
								<li><a href="#" id="menuAccountItem" v-on:click="makeActive('account', $event)" title="<?php echo lang('MASTER_MENU_ACCOUNT'); ?>"><?php echo lang('MASTER_MENU_ACCOUNT'); ?></a></li>
								<li>
									<form id="logoutForm" action="/auth/logout" method="post">
									</form>
									<a href="#" onclick="document.getElementById('logoutForm').submit();" title="<?php echo lang('MASTER_MENU_LOGOUT'); ?>" id="logoutBtn"><?php echo lang('MASTER_MENU_LOGOUT'); ?></a>
								</li>
							<?php else: ?>
								<li class="active"><a href="/#loginSection" title="<?php echo lang('MASTER_MENU_LOGIN'); ?>"><?php echo lang('MASTER_MENU_LOGIN'); ?></a></li>
								<li><a href="#signupSection" title="<?php echo lang('MASTER_MENU_SIGNUP'); ?>"><?php echo lang('MASTER_MENU_SIGNUP'); ?></a></li>
								<li><a href="#" title="<?php echo lang('MASTER_MENU_ABOUT'); ?>"><?php echo lang('MASTER_MENU_ABOUT'); ?></a></li>
								<li><a href="#" title="<?php echo lang('MASTER_MENU_HELP'); ?>"><?php echo lang('MASTER_MENU_HELP'); ?></a></li>
							<?php endif; ?>
						</ul>

					<div class="nav navbar-nav navbar-right">
						
					</div>
				</div><!-- /.container-fluid -->
	        </nav>
	        
	    </header>

	    <main id="mainblock">

			<?php if (\Models\Flash::has('showdisclaimer')) {
				
				include __DIR__ . '/parts/disclaimer.php';
			} ?>

        	<div class="container-fluid">
