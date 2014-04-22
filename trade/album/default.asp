<!--#include file="function.asp"-->

<%

checkLogin()

if request.QueryString("del")="ok" then

 fileDel request.QueryString("p"),request.QueryString("path")
 
end if

if request.QueryString("del")="okfiles" then

 filesDel request.QueryString("p")
 
end if

if request.QueryString("files")="ok" then

 filesAdd request.QueryString("p")
 
end if

if request.QueryString("up")="ok" then

 Set Upload = Server.CreateObject("Persits.Upload")
 Count = Upload.SaveVirtual(defaultPath&request.QueryString("path"))
 Response.Write Count & " photos have been uploaded!"
 
end if


%>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="style.css" rel="stylesheet" type="text/css">
<title>Photo Management</title>

<style>

 html
 {
  font-size:12px;
  font-family:Verdana, Arial, Helvetica, sans-serif;
 }
 .img
 {
   width:130px;
   float:left;
   padding:4px;
   text-align:center;
   height:90px;
   word-break:break-all;
 }
 
.span123
 {
  width:70px;
  height:42px;
  white-space:normal;
 
 } 
 .img img
 {
  width:90%;   
 }

 .img span
 {
  width:90%;
  overflow:hidden;
 }
 
 .filediv
 {
  width::57px;
  margin:4px; 
  text-align:center; 
  float:left;
 }


.STYLE1 {
	font-size: 14px;
	font-weight: bold;
}
</style>

<script>

 function delok(url)
 {
  var stat=confirm('Confirm to Delete?');
  if(stat)
  {
   window.location.href=url+'&path=<%=request.QueryString("path")%>';
  }
 }
 
 function files(url)
 {
  var f=document.getElementById('filesName').value;
  if(f!='')
  {
  window.location.href=url+'&p=<%=request.QueryString("path")%>/'+f+'&path=<%=request.QueryString("path")%>';
  }
 }

</script>

</head>

<body>

<div>

<fieldset>

 <legend><span class="STYLE1">Action</span><a href="loginFSO.asp?exit=1">【Sign out】</a></legend>
 
 <br>
 
 <%
' if request.QueryString("path")<>"" and request.QueryString("path")<>"/" then
 %>
  
 <strong>Create new file：</strong>
 <input type="text" id="filesName" name="filesName" /><a href="#" onClick="javascript:files('default.asp?files=ok')">【OK】</a>

 <br>
 
 <FORM METHOD="POST" ENCTYPE="multipart/form-data" ACTION="default.asp?up=ok&path=<%=request.QueryString("path")%>"> 
	<strong> Upload Photo:</strong><br>
    <INPUT TYPE="FILE" SIZE="40" NAME="FILE1"><br>
    <INPUT TYPE="FILE" SIZE="40" NAME="FILE2"><br>
    <INPUT TYPE="FILE" SIZE="40" NAME="FILE3"><br>
    <INPUT TYPE="FILE" SIZE="40" NAME="FILE4"><br>
    <INPUT TYPE="FILE" SIZE="40" NAME="FILE5"><br>
    
	<INPUT TYPE=SUBMIT VALUE="Upload!">
</FORM>

 <%
' end if
 %>

</fieldset>

<br/>

<fieldset>

 <legend>File</legend>
 
 <%
 
 dim filepath,paths,pathMenu
  
 if request.QueryString("path")<>"" and request.QueryString("path")<>"/" then
  
  filepath=request.QueryString("path")
  
  pss=split(filepath,"/")
  
  pst=""
  ps=""
  pathMenu=""
  
  for i=1 to ubound(pss)
  
   if i<ubound(pss) then
    ps=ps&"/"&pss(i)
   end if
   
   pst=pst&"/"&pss(i)
   
   pathMenu=pathMenu&"<a href=default.asp?path="&pst&">/"&pss(i)&"</a>"
     
  next
  
  'response.Write(pathMenu)
    
  Response.Write "<div class=filediv><a href=default.asp?path="&ps&"><img src='tb.jpg' style=border:0; /><br/>Parent Directory</a></div>"
 
 else
 
  filepath="/"
 
 end if
 
 files(filepath)  
 
 %>

</fieldset>
<br/>
<fieldset>

 <legend>Photo<%=pathMenu%></legend>
 
 <% 
  
 if request.QueryString("path")<>"" and request.QueryString("path")<>"/" then
  
  file(request.QueryString("path")) 
 
 end if
 
 %>


</fieldset>

<div>

</body>

</html>