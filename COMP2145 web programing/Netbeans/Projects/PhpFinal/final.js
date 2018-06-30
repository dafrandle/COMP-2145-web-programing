/* 
    Created on : Oct 11, 2017, 5:49:08 PM
    Author     : Dustin Frandle
*/
$(document).ready(function()
{
   console.log("jQuery 3.X is working. CDN source: https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js");
   //All jQuery Code:
   $(function(){
       $("#loginBtn").click(function(e){
           e.preventDefault();
           loginPage();
       });
   });
   $(function(){
       $("#regBtn").click(function(e){
           e.preventDefault();
           registerPage();
       });
   });
});

function doAlert(alertText) {
    alert(alertText);
}
function doLog(logText, isError) {
    if (isError == true) {
        console.error(logText);
    } else {
        console.log(logText);
    }
}

function loginPage() {
    document.getElementById("loginIFrame").srcdoc = '<html><head><title>Login/Register</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script><link rel="stylesheet" type="text/css" href="final.css" /><script src="final.js"></script></head><body><div id="loginModal" class="container-fluid text-center"><form action="index.php" method="post" name="login" target="_top"><label class="pull-left loginLable">Username:</label><input type="text" class="loginTxt pull-right" name="loginUsername" id="loginUsername" value="" /><br /><br /><label class="pull-left loginLable">Password:</label><input type="text" class="loginTxt pull-right" name="loginPass" id="loginPass" value=""/><br /><br /><input type="hidden" name="submitFlag" value="1"><input type="submit" name="loginBtn" class="btn btn-default" value="Login"></form></div></body></html>';
    document.getElementById("loginBtn").className = "active loginOptBtn";
    document.getElementById("regBtn").className = "loginOptBtn";
}

function registerPage() {
    document.getElementById("loginIFrame").srcdoc = '<html><head><title>Login/Register</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script><link rel="stylesheet" type="text/css" href="final.css" /><script src="final.js"></script></head><body><div id="loginModal" class="container-fluid text-center"><form action="index.php" method="post" name="login" target="_top"><label class="pull-left loginLable">Username:</label><input type="text" class="loginTxt pull-right" name="loginNewUsername" id="loginNewUsername" value="" /><br /><br /><label class="pull-left loginLable">Email:</label><input type="text" class="loginTxt pull-right" name="loginNewEmail" id="loginNewEmail" value=""/><br /><br /><label class="pull-left loginLable">Password:</label><input type="text" class="loginTxt pull-right" name="loginNewPass" id="loginNewPass" value=""/><br /><br /><input type="hidden" name="submitFlag" value="2"><input type="submit" name="regBtn" class="btn btn-default" value="Register"></form></div></body></html>';
    document.getElementById("loginBtn").className = "loginOptBtn";
    document.getElementById("regBtn").className = "active loginOptBtn";
}