<!--#include file="function.asp"-->
<%

function filesMenu(path,s)'遍历文件夹

set fso=server.CreateObject("scripting.filesystemobject")
'on error resume next
set objFolder=fso.GetFolder(Server.MapPath(defaultPath&path))
set objSubFolders=objFolder.Subfolders

ps=""

for each objSubFolder in objSubFolders

nowpath=objSubFolder.name

if path<>"/" then

ps=path&"/"&nowpath

else

ps=path&nowpath

end if
'<img src='tb.jpg' style=width:40px;height:40px;border:0; />
Response.Write s&"<a href=default.asp?path="&ps&" target=main>"&nowpath&"</a></br>"

'response.write ps

filesMenu ps,"&nbsp;&nbsp;"&s&"-"

next

set objSubFolders=nothing

set fso=nothing

end function

%>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="style.css" rel="stylesheet" type="text/css">
<title>Login Page</title>

<style>

 html
 {
  font-size:12px;
 }


</style>


</head>

<body>

<%


filesMenu "/","|-"

'filesMenu("/test")

'filesMenu("/test/123")


%>

</body>

</html>