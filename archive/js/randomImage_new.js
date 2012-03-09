function random_imglink(){
  var myimages=new Array()
  //specify random images below
  myimages[1]="images/photo/background14.jpg"
  


  //specify corresponding links below
  



  var ry=Math.floor(Math.random()*myimages.length)

  if (ry==0)
     ry=1
     document.write('<a href='+'"'+imagelinks[ry]+'"'+'><img src="'+myimages[ry]+'" border="0" target="_blank"></a>')
}