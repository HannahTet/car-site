<?php

error_reporting(E_ALL ^ E_NOTICE);

/*

Thank you for choosing FormToEmail by FormToEmail.com

Version 2.5 April 16th 2009

COPYRIGHT FormToEmail.com 2003 - 2009

You are not permitted to sell this script, but you can use it, copy it or distribute it, providing that you do not delete this copyright notice, and you do not remove any reference or links to FormToEmail.com

For support, please visit: http://formtoemail.com/support/

You can get the Pro version of this script here: http://formtoemail.com/formtoemail_pro_version.php
---------------------------------------------------------------------------------------------------

FormToEmail-Pro (Pro version) Features:

Check for required fields
Attach file uploads
Upload files to the server
Securimage CAPTCHA support
reCAPTCHA support
textCAPTCHA support
identiPIC photo CAPTCHA
HTML output option
Use email templates
Show date and time submitted
Create Message ID
CSV output to attachment or file
Autoresponder (with file attachment)
Show sender's IP address
Block IP addresses
Block web addresses or rude words
Block gibberish (MldMtrPAgZq etc)
Block gobbledegook characters (ֵ נ ח etc)
Pre-populate the form
Show errors on the form page
Check for a set cookie
Set encoding (utf-8 etc)
Ignore fields
Sort fields
Auto redirect to "Thank You" page
HTML template for "Thank You" page
No branding
Free upgrades for life

---------------------------------------------------------------------------------------------------

Confused by PHP and PERL scripts?  Don't have PHP on your server?  Can't send email from your server?

Try our remotely hosted form service:

http://FormToEmailRemote.com

---------------------------------------------------------------------------------------------------

FormToEmail DESCRIPTION

FormToEmail is a contact-form processing script written in PHP. It allows you to place a form on your website which your visitors can fill out and send to you.  The contents of the form are sent to the email address (or addresses) which you specify below.  The form allows your visitors to enter their name, email address and comments.  The script will not allow a blank form to be sent.

Your visitors (and nasty spambots!) cannot see your email address.  The script cannot be hijacked by spammers.

When the form is sent, your visitor will get a confirmation of this on the screen, and will be given a link to continue to your homepage, or other page if you specify it.

Should you need the facility, you can add additional fields to your form, which this script will also process without making any additional changes to the script.  You can also use it to process other forms.  The script will handle the "POST" or "GET" methods.  It will also handle multiple select inputs and multiple check box inputs.  If using these, you must name the field as an array using square brackets, like so: <select name="fruit[]" multiple>.  The same goes for check boxes if you are using more than one with the same name, like so: <input type="checkbox" name="fruit[]" value="apple">Apple<input type="checkbox" name="fruit[]" value="orange">Orange<input type="checkbox" name="fruit[]" value="banana">Banana

** PLEASE NOTE **  If you are using the script to process your own forms (or older FormToEmail forms) you must ensure that the email field is named correctly in your form, like this for example: <input type="text" name="email">.  Note the lower case "email".  If you don't do this, the visitor's email address will not be available to the script and the script won't be able to check the validity of the email, amongst other things.  If you are using the form code below, you don't need to check for this.

This is a PHP script.  In order for it to run, you must have PHP (version 4.1.0 or later) on your webhosting account, and have the PHP mail() function enabled and working.  If you are not sure about this, please ask your webhost about it.

SETUP INSTRUCTIONS

Step 1: Put the form on your webpage
Step 2: Enter your email address and (optional) continue link below
Step 3: Upload the files to your webspace

Step 1:

To put the form on your webpage, copy the code below as it is, and paste it into your webpage:

<form action="FormToEmail.php" method="post">
<table border="0" style="background:#ececec" cellspacing="5">
<tr align="left"><td>Name</td><td><input type="text" size="30" name="name"></td></tr>
<tr align="left"><td>Email address</td><td><input type="text" size="30" name="email"></td></tr>
<tr align="left"><td valign="top">Comments</td><td><textarea name="comments" rows="6" cols="30"></textarea></td></tr>
<tr align="left"><td>&nbsp;</td><td><input type="submit" value="Send"><font face="arial" size="1">&nbsp;&nbsp;<a href="http://FormToEmail.com">PHP Form</a> by FormToEmail.com</font></td></tr>
</table>
</form>

Step 2:

Enter your email address.

Enter the email address below to send the contents of the form to.  You can enter more than one email address separated by commas, like so: $my_email = "info@example.com"; or $my_email = "bob@example.com,sales@example.co.uk,jane@example.com";

*/

$my_email = "aaa@gmail.com";

/*

Optional.  Enter a From: email address.  Only do this if you know you need to.  By default, the email you get from the script will show the visitor's email address as the From: address.  In most cases this is desirable.  On the majority of setups this won't be a problem but a minority of hosts insist that the From: address must be from a domain on the server.  For example, if you have the domain example.com hosted on your server, then the From: email address must be something@example.com (See your host for confirmation).  This means that your visitor's email address will not show as the From: address, and if you hit "Reply" to the email from the script, you will not be replying to your visitor.  You can get around this by hard-coding a From: address into the script using the configuration option below.  Enabling this option means that the visitor's email address goes into a Reply-To: header, which means you can hit "Reply" to respond to the visitor in the conventional way.  (You can also use this option if your form does not collect an email address from the visitor, such as a survey, for example, and a From: address is required by your email server.)  The default value is: $from_email = "";  Enter the desired email address between the quotes, like this example: $from_email = "contact@example.com";  In these cases, it is not uncommon for the From: ($from_email) address to be the same as the To: ($my_email) address, which on the face of it appears somewhat goofy, but that's what some hosts require.

*/

$from_email = "autoreview@gmail.com";

/*

Optional.  Enter the continue link to offer the user after the form is sent.  If you do not change this, your visitor will be given a continue link to your homepage.

If you do change it, remove the "/" symbol below and replace with the name of the page to link to, eg: "mypage.htm" or "http://www.elsewhere.com/page.htm"

*/

$continue = "/";

/*

Step 3:

Save this file (FormToEmail.php) and upload it together with your webpage containing the form to your webspace.  IMPORTANT - The file name is case sensitive!  You must save it exactly as it is named above!

THAT'S IT, FINISHED!

You do not need to make any changes below this line.

*/

$errors = array();

// Remove $_COOKIE elements from $_REQUEST.

if(count($_COOKIE)){foreach(array_keys($_COOKIE) as $value){unset($_REQUEST[$value]);}}

// Validate email field.

if(isset($_REQUEST['email']) && !empty($_REQUEST['email']))
{

$_REQUEST['email'] = trim($_REQUEST['email']);

if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ") || stristr($_REQUEST['email'],"\\") || stristr($_REQUEST['email'],":")){$errors[] = "Email address is invalid";}else{$exploded_email = explode("@",$_REQUEST['email']);if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){$errors[] = "Email address is invalid";}else{if(substr_count($exploded_email[1],".") == 0){$errors[] = "Email address is invalid";}else{$exploded_domain = explode(".",$exploded_email[1]);if(in_array("",$exploded_domain)){$errors[] = "Email address is invalid";}else{foreach($exploded_domain as $value){if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){$errors[] = "Email address is invalid"; break;}}}}}}

}

// Check referrer is from same site.

if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}

// Check for a blank form.

function recursive_array_check_blank($element_value)
{

global $set;

if(!is_array($element_value)){if(!empty($element_value)){$set = 1;}}
else
{

foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}

}

}

recursive_array_check_blank($_REQUEST);

if(!$set){$errors[] = "You cannot send a blank form";}

unset($set);

// Display any errors and exit if errors exist.

if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}

if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}

// Build message.

function build_message($request_input){if(!isset($message_output)){$message_output ="";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!empty($value)){if(!is_numeric($key)){$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;}else{$message_output .= build_message($value).", ";}}}}return rtrim($message_output,", ");}

$message = build_message($_REQUEST);

$message = "This form send from:\n\n". $message;

$message = stripslashes($message);

//Your site name

$subject = "Auto Review";

$subject = stripslashes($subject);

if($from_email)
{

$headers = "From: " . $from_email;
$headers .= PHP_EOL;
$headers .= "Reply-To: " . $_REQUEST['email'];

}
else
{

$from_name = "";

if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){$from_name = stripslashes($_REQUEST['name']);}

$headers = "From: {$from_name} <{$_REQUEST['email']}>";

}

mail($my_email,$subject,$message,$headers);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AUTO Review</title>
    <!-- Bootstrap -->
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet" type="text/css">
	

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
  </head>
  <body>
	<header class="container mycontainer">
	  <nav class="navbar navbar-default mynav">
	    <div class="container-fluid">
	      <!-- Brand and toggle get grouped for better mobile display -->
	      <div class="navbar-header">
	        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#defaultNavbar1" aria-expanded="false"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
	        <a class="navbar-brand my_logo" href="index.html"><img src="images/logo1.png" width="130" height="60" alt="Logo"></a></div>
	      <!-- Collect the nav links, forms, and other content for toggling -->
         <div class="collapse navbar-collapse" id="defaultNavbar1">
	          <ul class="nav navbar-nav navbar-right mylinks">
				  <li><a href="index.html">Home</a></li>
				  <li><a href="models.html">Our models</a></li>
				  <li><a href="test_drive.html">Test drive</a></li>
				  <li><a href="manuals.html">Manuals</a></li>
				  <li><a href="contacts.html">Contacts</a></li>
	          </ul>
          </div>
       </div>
	   	    <!-- /.container-fluid -->
      </nav>
    </header>
  <ol class="breadcrumb">
    <li class="active">Home</li>
  </ol>
	<div class="container-fluid mymobslider"><img src="images/honda_civic.jpg" width="1920" height="600" alt="Mobile Slider"/></div>
  		<div id="carousel1" class="carousel slide" data-ride="carousel">
    	  <ol class="carousel-indicators">
			  <li data-target="#carousel1" data-slide-to="0" class="active"></li>
			  <li data-target="#carousel1" data-slide-to="1"></li>
			  <li data-target="#carousel1" data-slide-to="2"></li>
			  <li data-target="#carousel1" data-slide-to="3"></li>
			  <li data-target="#carousel1" data-slide-to="4"></li>
		  </ol>
	    <div class="carousel-inner" role="listbox">
	      <div class="item active"><img src="images/honda_civic.jpg" alt="Honda civic image" class="center-block">
	        <div class="carousel-caption">
	   			<h1>The World of Hatchback</h1>
	          	<h4>The best advice for buying a car</h4>
            </div>
          </div>
	      <div class="item"><img src="images/chevrolet_sonic.jpg" alt="Chevrolet sonic image" class="center-block">
	        <div class="carousel-caption">
	       		<h1>The World of Hatchback</h1>
	         	<h4>The best advice for buying a car</h4>
            </div>
          </div>
	      <div class="item"><img src="images/opel-astra.jpg" alt="Opel astra image" class="center-block">
	        <div class="carousel-caption">
	          <h1>The World of Hatchback</h1>
	        	<h4>The best advice for buying a car</h4>
            </div>
          </div>
          <div class="item"><img src="images/-subaru-impreza1.jpg" alt="Subaru impreza image" class="center-block">
	        <div class="carousel-caption">
	         <h1>The World of Hatchback</h1>
	      	<h4>The best advice for buying a car</h4>
            </div>
          </div>
          <div class="item"><img src="images/MY17_civic.jpg" alt="MY17 civic image" class="center-block">
	        <div class="carousel-caption">
	          <h1>The World of Hatchback</h1>
	       	<h4>The best advice for buying a car</h4>
            </div>
          </div>
        </div>
       <a class="left carousel-control" href="#carousel1" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#carousel1" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span></a></div>
  <main class="container">
   	<h1>What model of hatchback worth buying?</h1>
    <p>Many people ask this question when they decide to buy a hatchback.Today car producing plants offer a big variety of    		hatchbacks,and for an ordinary driver (especially for woman) it is quite difficult to make such a choice.
        In our site we made a review of most popular models in this  type of cars.We hope this information will help you to understand all the parameters while choosing a hatchback, and afterwards you'll  be able to buy the car of your dream...&nbsp;&nbsp;&nbsp;
        <a href="hatchbacks.html">Read more</a></p>
  </main>
	<footer class="container">
       <div class="row">
          <div class="col-lg-6">
            <div class="jumbotron mybox">
           		<p class="glyphicon glyphicon-road myicon" aria-hidden="true"></p>
            	<h3>Test drive</h3>
            	<p class="myboxp">If you don't have an opportunity
            	 to test the hatchback you'd like to buy,
            	  you can watch some videos</p>
            	<p><a href="test_drive.html">Read more</a></p>
          	   </div>
            </div>
      	  <div class="col-lg-6">
             <div class="jumbotron mybox">
           	  <p class="glyphicon glyphicon-save myicon" aria-hidden="true"></p>
         	  <h3>Manuals</h3>
              <p class="myboxp">For those who want to know some specific information about our models of hatchback, we offer you guide books.</p>
              <p><a href="manuals.html">Read more</a></p>
             </div>
          </div>
           <div class="container-fluid">
      <div class="container">&copy; Hannah Teteriatnyk &nbsp;&nbsp;&nbsp; All Rights Reserved</div>
    </div>
      </div>
      </footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
	<script src="js/jquery-1.11.3.min.js"></script>

	<!-- Include all compiled plugins (below), or include individual files as needed --> 
	<script src="js/bootstrap.js"></script>
  </body>
</html>