<%


const defaultPath="/album"

sub checkLogin

if session("adm")="" then

response.Write("<table width='400' align='center'><tr><td><img src=../images/Delete.gif></td><td><a style='font-family: arial;' href=loginFSO.asp target=_parent>【Permission is time out, please re-login.】</a></td></tr></table><hr><p style='font-size:11px; font-family:Arial, Helvetica, sans-serif; color:#999999;' align='center'>Original Source Trade Co., Ltd</p>")
response.End()

end if

end sub

Function IsObjInstalled(strClassString)  '判断控件是否可用
On Error Resume Next  
IsObjInstalled = False  
Err = 0  

Dim xTestObj  
Set xTestObj = Server.CreateObject(strClassString)  
If 0 = Err Then IsObjInstalled = True  
Set xTestObj = Nothing  
Err = 0  
End Function  


function files(path)'遍历文件夹

set fso=server.CreateObject("scripting.filesystemobject")
'on error resume next
set objFolder=fso.GetFolder(Server.MapPath(defaultPath&path))
set objSubFolders=objFolder.Subfolders

if path<>"/" then

dim paths

paths=split(path,"\")(ubound(split(path,"\")))

end if

for each objSubFolder in objSubFolders

nowpath=objSubFolder.name

Response.Write "<div class=filediv><a href=default.asp?path="&paths&"/"&nowpath&"><img src='tb.jpg' style=border:0; /><br/>"&nowpath&"</a><br/><input type=button onclick=""javascript:delok('default.asp?del=okfiles&p="&paths&"/"&nowpath&"');"" value=Delete/></div>"

next

set objSubFolders=nothing

set fso=nothing

end function


function file(path)'遍历图片

set fso=server.CreateObject("scripting.filesystemobject")

'on error resume next

'response.write path

set objFolder=fso.GetFolder(Server.MapPath(defaultPath&path))

set objFiles=objFolder.Files

for each objFile in objFiles

'for i=1 to objFiles.count

'response.Write objFiles(i).name

 f1= LCase(right(objFile.name,3))
 
 if f1="jpg" or f1="png" or f1="gif" or f1="bmp" then

  Response.Write "<div class=img><a href='view.asp?p="&defaultPath&path&"/"&objFile.name&"' target=_blank><img src='"&defaultPath&path&"/"&objFile.name&"' style=border:0;/><br><span class=span123>"&objFile.name&"</span></a><br/><input type=button onclick=""javascript:delok('default.asp?del=ok&p="&defaultPath&path&"/"&objFile.name&"');"" value=Delete></div>"

 end if
 
next

set objFiles=nothing
set objFolder=nothing
set fso=nothing

end function

function fileDel(path,url)'删除文件

Dim objFSO 
Set objFSO = Server.CreateObject("Scripting.FileSystemObject")
 
if objFSO.FileExists(Server.MapPath(path)) then
 
objFSO.DeleteFile Server.MapPath(path),True 

end if

Set objFSO = Nothing

end function

function filesDel(path)'删除文件夹

dim fso
set fso=server.createobject("Scripting.FileSystemObject")
if fso.folderexists(Server.MapPath(defaultPath&path))  then
    fso.deleteFolder(Server.MapPath(defaultPath&path))
end if

set fso=nothing

response.write("<script>window.parent.frames['left'].location.reload();</script>")
  
end function

function filesAdd(path)'生成文件夹

dim fso
set fso=server.createobject("Scripting.FileSystemObject")
fso.CreateFolder(Server.MapPath(defaultPath&path))
set fso=nothing
response.write("<script>window.parent.frames['left'].location.reload();</script>")
end function

%>