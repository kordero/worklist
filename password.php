<?php
//  vim:ts=4:et

//
//  Copyright (c) 2010, LoveMachine Inc.
//  All Rights Reserved.
//  http://www.lovemachineinc.com
//

ob_start();
include("config.php");
include("class.session_handler.php");
include("check_session.php");
include("functions.php");
include_once("send_email.php");

if($_POST['oldpassword']!="")
{
    $qry="select id from ".USERS." where username='".mysql_real_escape_string($_SESSION['username'])."' and password='".sha1(mysql_real_escape_string($_POST['oldpassword']))."'";
    $rs=mysql_query($qry);
    if(mysql_num_rows($rs) > 0)
    {
        if(($_POST['newpassword']!="")&&($_POST['newpassword']==$_POST['confirmpassword']))
        {
            $qry="update ".USERS." SET password='".sha1(mysql_real_escape_string($_POST['newpassword']))."' where id='".$_SESSION['userid']."'";
            mysql_query($qry);

            $msg="Password updated successfully!";
            $to = $_SESSION['username'];
            $subject = "Password Change";
            $body  = "<p>Congratulations!</p>";
            $body .= "<p>You have successfully updated your password with ".SERVER_NAME.".";
            $body .= "</p><p>Love,<br/>Philip and Ryan</p>";
            sl_send_email($to, $subject, $body);
        } else {
            $msg ="New passwords don't match!";
        }
    } else {
        $msg ="Old password doesn't match!";
    }
}

/*********************************** HTML layout begins here  *************************************/

include("head.html");
?>

<title>Worklist | Change Password</title>

</head>

<body>

<?php include("format.php"); ?>

<!-- ---------------------- BEGIN MAIN CONTENT HERE ---------------------- -->

<h1>Change Password</h1>

<?php if (!empty($msg)) { ?><p class="error"><?php echo $msg ?></p><?php } ?>

<form method="post" action="password.php" name="form_password" onSubmit="return validate();">

    <p><label>Current Password<br />
    <input type="password" name="oldpassword" id="oldpassword" size="35" />
    </label></p>
    <div class="LVspace">
    <p><label>New Password<br />
    <input type="password" name="newpassword" id="newpassword" size="35" />
    </label></p>
    <script type="text/javascript">
        var newpassword = new LiveValidation('newpassword',{ validMessage: "You have an OK password.", onlyOnBlur: true });
            newpassword.add(Validate.Length, { minimum: 5, maximum: 12 } );
    </script>
    </div>

    <div class="LVspace">
    <p><label>Re-enter New Password<br />
    <input type="password" name="confirmpassword" id="confirmpassword" size="35" />
    </label></p>
    <script type="text/javascript">
        var confirmpassword = new LiveValidation('confirmpassword', {validMessage: "Passwords Match."});
            confirmpassword.add(Validate.Confirmation, { match: 'newpassword'} );
    </script>
    </div>

    <input type="submit" value="Change Password" alt="Change Password" name="change-password" />

</form>

<!-- ---------------------- end MAIN CONTENT HERE ---------------------- -->

<script type="text/javascript" src="js/jquery.js"></script>
<?php include("footer.php"); ?>
