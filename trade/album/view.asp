<!--#include file="function.asp"-->

<%


checkLogin()


%>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="style.css" rel="stylesheet" type="text/css">
<title>Photo</title>
</head>

<body>

<textarea  name="textarea" cols="65" rows="3">http://<%= request.ServerVariables("HTTP_HOST")&request.QueryString("p")%></textarea>

<br>

<img src="<%=request.QueryString("p")%>" style=border:0;/>


</body>

</html>