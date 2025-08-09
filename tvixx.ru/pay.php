<script>
<!--


YOffset=20; // no quotes!!
staticYOffset=20; // no quotes!!
staticMode="smooth";
slideSpeed=20 // no quotes!!
waitTime=500; // no quotes!! this sets the time the menu stays out for after the mouse goes off it.
hdrFontFamily="Verdana";
hdrFontSize="3";
hdrFontColor="white";
hdrBGColor="#170088";
hdrAlign="right";
hdrVAlign="center";
hdrHeight="20";
linkFontFamily="Verdana";
linkFontSize="2";
linkBGColor="white";
linkOverBGColor="#DDDDDD";
linkTarget="_top";
linkAlign="left";
menuBGColor="black";
menuIsStatic="yes";
menuHeader="Menu&nbsp;";
menuWidth=110; // Must be a multiple of 10! no quotes!!
barBGColor="#000000";
barFontFamily="Verdana";
barFontSize="2";
barFontColor="white";
barText="DYNAMIC FX";
barVAlign="top";
barWidth=20; // no quotes!!

IE = (document.all)
NS = (navigator.appName=="Netscape" && navigator.appVersion >= "4")

function moveOut() {
if (window.cancel) {clearTimeout(cancel);}
if (window.moving2) {clearTimeout(moving2);}
if ((IE && ssm2.style.pixelLeft<0)||(NS && document.ssm2.left<0)) {
if (IE) {ssm2.style.pixelLeft += 10;}
if (NS) {document.ssm2.left += 10;}
moving1 = setTimeout('moveOut()', slideSpeed)}
else {clearTimeout(moving1)}};
function moveBack() {
cancel = setTimeout('moveBack1()', waitTime)}
function moveBack1() {
if (window.moving1) {clearTimeout(moving1)}
if ((IE && ssm2.style.pixelLeft>(-menuWidth))||(NS && document.ssm2.left>(-menuWidth))) {
if (IE) {ssm2.style.pixelLeft -= 10;}
if (NS) {document.ssm2.left -= 10;}
moving2 = setTimeout('moveBack1()', slideSpeed)}
else {clearTimeout(moving2)}};

lastY = 0;
function makeStatic(mode) {
if (IE) {winY = document.body.scrollTop;var NM=ssm2.style}
if (NS) {winY = window.pageYOffset;var NM=document.ssm2}
if (mode=="smooth") {
if ((IE||NS) && winY!=lastY) {
smooth = .2 * (winY - lastY);
if(smooth > 0) smooth = Math.ceil(smooth);
else smooth = Math.floor(smooth);
if (IE) NM.pixelTop+=smooth;
if (NS) NM.top+=smooth;
lastY = lastY+smooth;}
setTimeout('makeStatic("smooth")', 1)}
else if (mode=="advanced") {
if ((IE||NS) && winY>YOffset-staticYOffset) {
if (IE) {NM.pixelTop=winY+staticYOffset}
if (NS) {NM.top=winY+staticYOffset}}
else {
if (IE) {NM.pixelTop=YOffset}
if (NS) {NM.top=YOffset-7}}
setTimeout('makeStatic("advanced")', 1)}}

function initSlide() {
if (IE) {
ssm2.style.pixelLeft = -menuWidth;
ssm2.style.visibility = "visible"}
else if (NS) {
document.ssm2.left = -menuWidth;
document.ssm2.visibility = "show"}
else {alert('Choose either the "smooth" or "advanced" static modes!')}}

function startMenu() {
if (IE) {document.write('<DIV ID="ssm2" style="visibility:hidden;Position : Absolute ;Left : 0px ;Top : '+YOffset+'px ;Z-Index : 20;width:1px" onmouseover="moveOut()" onmouseout="moveBack()">')}
if (NS) {document.write('<LAYER visibility="hide" top="'+YOffset+'" name="ssm2" bgcolor="'+menuBGColor+'" left="0" onmouseover="moveOut()" onmouseout="moveBack()">')}
tempBar=""
for (i=0;i<barText.length;i++) {
tempBar+=barText.substring(i, i+1)+"<BR>"}
document.write('<table border="0" cellpadding="0" cellspacing="1" width="'+(menuWidth+barWidth+2)+'" bgcolor="'+menuBGColor+'"><tr><td bgcolor="'+hdrBGColor+'" WIDTH="'+(menuWidth-1)+'" HEIGHT="'+hdrHeight+'" ALIGN="'+hdrAlign+'" VALIGN="'+hdrVAlign+'">&nbsp;<font face="'+hdrFontFamily+'" Size="'+hdrFontSize+'" COLOR="'+hdrFontColor+'"><b>'+menuHeader+'</b></font></td><td align="center" rowspan="100" width="'+barWidth+'" bgcolor="'+barBGColor+'" valign="'+barVAlign+'"><p align="center"><font face="'+barFontFamily+'" Size="'+barFontSize+'" COLOR="'+barFontColor+'"><B>'+tempBar+'</B></font></p></TD></tr>')}

function addItem(text, link, target) {
if (!target) {target=linkTarget}
document.write('<TR><TD BGCOLOR="'+linkBGColor+'" onmouseover="bgColor=\''+linkOverBGColor+'\'" onmouseout="bgColor=\''+linkBGColor+'\'" ALIGN="'+linkAlign+'" WIDTH="'+(menuWidth-1)+'"><ILAYER><LAYER onmouseover="bgColor=\''+linkOverBGColor+'\'" onmouseout="bgColor=\''+linkBGColor+'\'" WIDTH="100%" ALIGN="'+linkAlign+'"><FONT face="'+linkFontFamily+'" Size="'+linkFontSize+'">&nbsp;<A HREF="'+link+'" target="'+target+'" CLASS="ssm2Items">'+text+'</LAYER></ILAYER></TD></TR>')}

function addHdr(text) {
document.write('<tr><td bgcolor="'+hdrBGColor+'" HEIGHT="'+hdrHeight+'" ALIGN="'+hdrAlign+'" VALIGN="'+hdrVAlign+'">&nbsp;<font face="'+hdrFontFamily+'" Size="'+hdrFontSize+'" COLOR="'+hdrFontColor+'"><b>'+text+'</b></font></td></tr>')}

function endMenu() {
document.write('<tr><td bgcolor="'+hdrBGColor+'"><font size="0" face="Arial">&nbsp;</font></td></TR></table>')
if (IE) {document.write('</DIV>')}
if (NS) {document.write('</LAYER>')}
if ((IE||NS) && (menuIsStatic=="yes"&&staticMode)) {makeStatic(staticMode);}
}

// Insert Generated Text Below

/*
Configure menu styles below
NOTE: To edit the link colors, go to the STYLE tags and edit the ssm2Items colors
*/
YOffset=100; // no quotes!!
staticYOffset=100; // no quotes!!
staticMode="advanced";
slideSpeed=20 // no quotes!!
waitTime=500; // no quotes!! this sets the time the menu stays out for after the mouse goes off it.
menuBGColor="black";
menuIsStatic="yes";
menuHeader="Навигация:";
menuWidth=110; // Must be a multiple of 10! no quotes!!
hdrFontFamily="Arial";
hdrFontSize="2";
hdrFontColor="#000000";
hdrBGColor="#FEB834";
hdrAlign="left";
hdrVAlign="center";
hdrHeight="20";
linkFontFamily="Arial";
linkFontSize="2";
linkBGColor="#FEB834";
linkOverBGColor="orange";
linkTarget="_top";
linkAlign="Left";
barBGColor="orange";
barFontFamily="Verdana";
barFontSize="3";
barFontColor="#000000";
barText=" НАВИГАЦИЯ ";
barVAlign="center";
barWidth=20; // no quotes!!

startMenu()
addItem('Главная', '', '');
addItem('CGI скрипты', '', '');
addItem('PHP скрипты', '', '');
addItem('JavaScript', '', '');
addItem('Java апплеты', '', '');
addItem('Документация', '', '');
addItem('Программы', '', '');
endMenu()

window.onload=initSlide

//-->
</script>
