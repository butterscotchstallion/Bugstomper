<!doctype html>
	<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
	<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
	<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
	<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
	<!--[if gt IE 8]><!-->
		<html class="no-js" lang="en"> 
	<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Bugstomper</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Bug Tracker">
	<meta name="author" content="PrgmrBill">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="/assets/css/style.css">
	<link rel="stylesheet" href="/assets/css/jquery.qtip.min.css">
	<link rel="stylesheet" href="/assets/css/mint-choc/jquery-ui-1.8.16.custom.css">
</head>
<?php flush();?>
<body>
  <div id="container">
    <header>
		<a href="/issues" title="Issue List">Bugstomper</a>
		<br>
		<a href="/pretty-graphs-and-stuff">Pretty graphs and stuff</a>
		
		<div id="topUserInfoArea">
		<?php if( self::Get('userLogin') ):?>
			Signed in as <strong><?php echo self::Get('userLogin');?></strong>
			[<a href="/user/sign-out" title="Sign Out">Sign Out</a>]
		<?php else:?>
			<a href="/user/sign-in" title="Sign in">Sign In</a>
		<?php endif;?>
		</div>
    </header>
	
	<?php 
	if( self::Get('issues') ):
		require '../view/Issue/IssueListingFilterBar.template.php';
	endif;
	?>
    
	<div id="main" role="main">
		
    