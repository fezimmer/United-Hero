function random_imglink(){
  var myimages=new Array()
  //specify random images below
  myimages[1]="images/photo/background14.jpg"
  myimages[2]="images/photo/background15.jpg"
  myimages[3]="images/photo/background16.jpg"
  //myimages[4]="images/photo/background17.jpg"
  myimages[5]="images/photo/background18.jpg"
  //myimages[6]="images/photo/background19.jpg"
  myimages[7]="images/photo/background20.jpg"
  myimages[8]="images/photo/background21.jpg"
  myimages[9]="images/photo/background22.jpg"
  myimages[10]="images/photo/background23.jpg"
  //myimages[11]="images/photo/background24.jpg"


  //specify corresponding links below
  var imagelinks=new Array()
  imagelinks[1]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  imagelinks[2]="http://c895worldwide.com/web/default.asp?page=home"
  imagelinks[3]="http://www.ilaunion.org/childrensfund.html"
  //imagelinks[4]="http://www.usfa.dhs.gov/"
  imagelinks[5]="http://www.recovery.gov/"
  //imagelinks[6]="http://info.helmetstohardhats.org/content/infocenter/"
  imagelinks[7]="http://www.boeing.com/"
  imagelinks[8]="http://www.chevrolet.com/pages/open/default/future/volt.do"
  imagelinks[9]="http://www.nasa.gov/"
  imagelinks[10]="http://www.oprah.com/index"
  //imagelinks[11]="http://www.youtube.com/watch?v=IhydyxRjujU"



  var ry=Math.floor(Math.random()*myimages.length)

  if (ry==0)
     ry=1
     document.write('<a href='+'"'+imagelinks[ry]+'"'+'><img src="'+myimages[ry]+'" border="0" target="_blank"></a>')
}