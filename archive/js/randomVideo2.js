function random_vidlink(){
  var myimages=new Array()
  //specify random images below
 myimages[1]="player/auto_play_var_player.swf"
  myimages[2]="player2/auto_play_var_player.swf"
  myimages[3]="player3/auto_play_var_player.swf"
  myimages[4]="player7/auto_play_var_player.swf"
 myimages[5]="player17/auto_play_var_player.swf"
  myimages[6]="player17/auto_play_var_player.swf"
  myimages[7]="player11/auto_play_var_player.swf"
  myimages[8]="player11/auto_play_var_player.swf"
  myimages[9]="player18/auto_play_var_player.swf"
  myimages[10]="player13/auto_play_var_player.swf"
  myimages[11]="player14/auto_play_var_player.swf"
  myimages[12]="player15/auto_play_var_player.swf"
  myimages[13]="player16/auto_play_var_player.swf"
 
  
  //myimages[5]="images/photo/background21.jpg"
 // myimages[6]="images/photo/background22.jpg"
 // myimages[7]="images/photo/background23.jpg"
  //myimages[11]="images/photo/background24.jpg"


  //specify corresponding links below
  //var imagelinks=new Array()
  //imagelinks[1]="http://www.feedthechildren.org/site/PageServer?pagename=dotorg_homepage"
  //imagelinks[2]="http://c895worldwide.com/web/default.asp?page=home"
  //imagelinks[3]="http://www.ilaunion.org/childrensfund.html"
  //imagelinks[4]="http://www.usfa.dhs.gov/"
  //imagelinks[3]="http://www.recovery.gov/"
  //imagelinks[6]="http://info.helmetstohardhats.org/content/infocenter/"
  //imagelinks[4]="http://www.boeing.com/"
  //imagelinks[5]="http://www.chevrolet.com/pages/open/default/future/volt.do"
  //imagelinks[6]="http://www.nasa.gov/"
  //imagelinks[7]="http://www.oprah.com/index"
  //imagelinks[11]="http://www.youtube.com/watch?v=IhydyxRjujU"



  var ry=Math.floor(Math.random()*myimages.length)

 if (ry==0)
     ry=1
     document.write('<embed src="'+myimages[ry]+'" width="691" height="449" loop="False" wmode="transparent" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>')
}