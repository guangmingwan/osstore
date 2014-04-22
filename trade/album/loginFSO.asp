<!--#include file="function.asp"-->

<%

if request.QueryString("dl")<>"" and request.Form("pwd")="king123456" then

 session("adm")="123"
 session.Timeout=60
 response.redirect("main.htm")

end if

if request.QueryString("exit")<>"" then

 session("adm")=""
 
end if




if IsObjInstalled("Scripting.FileSystemObject")=true then 
   response.write "<table align='center' width='380'><tr><td><img src=../images/lock.gif></td><td><span style='color:000; font-size:13px; font-weight:bold;'>Original Source Trade Photo Upload System</span></td></tr></table>" 
else
   response.write "No supporting FSO" 
end if


%>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<title>Login</title>
</head>

<body>

<form style="font-family:Arial, Helvetica, sans-serif;" method="POST" action="loginFSO.asp?dl=ok">

  <div align="center">
  <input type="password" id="pwd" name="pwd" size="25">
    
  <input type="submit" value=" Sign in ">
    
  </div>
</form>
<hr align="center">
<p align="center">Original Source Trade Co., Ltd</p>

</body>

</html>