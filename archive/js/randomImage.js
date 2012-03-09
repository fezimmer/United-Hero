function random_imglink(){
  var myimages=new Array()
  //specify random images below
  myimages[1]="images/photo/background14.jpg"
  myimages[2]="images/photo/background14.jpg"
  //myimages[3]="images/photo/background16.jpg"
  //myimages[4]="images/photo/background17.jpg"
  myimages[3]="images/photo/background14.jpg"
  //myimages[6]="images/photo/background19.jpg"
  myimages[4]="images/photo/background14.jpg"
  myimages[5]="images/photo/background14.jpg"
  myimages[6]="images/photo/background14.jpg"
  myimages[7]="images/photo/background14.jpg"
  //myimages[11]="images/photo/background24.jpg"


  //specify corresponding links below
  var imagelinks=new Array()
  imagelinks[1]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  imagelinks[2]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  //imagelinks[3]="http://www.ilaunion.org/childrensfund.html"
  //imagelinks[4]="http://www.usfa.dhs.gov/"
  imagelinks[3]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  //imagelinks[6]="http://info.helmetstohardhats.org/content/infocenter/"
  imagelinks[4]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  imagelinks[5]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  imagelinks[6]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  imagelinks[7]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  //imagelinks[11]="http://www.youtube.com/watch?v=IhydyxRjujU"



  var ry=Math.floor(Math.random()*myimages.length)

  if (ry==0)
     ry=1
     document.write('<a href='+'"'+imagelinks[ry]+'"'+'><img src="'+myimages[ry]+'" border="0" target="_blank"></a>')
}