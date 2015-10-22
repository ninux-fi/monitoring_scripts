/*
Diese Datei ist Teil einer Extension für:

>>>>>> CrazyStat <<<<<<
A convenient, comprehensive and free PHP statistic-Script with optional counter.

Copyright (C) 2004-2012  Christopher Kramer

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

E-Mail: webmaster AT christosoft DOT de
Web: http://www.christosoft.de
Version: 1.71

*** ajaxTree.js ***
Funktion:    JavaScript-part of ajaxTree
Aufrufbar:   no
Eingebunden: by show_stat.php
*/

function prozent(teil, gesamt)  // percentage-function
 {
 if(gesamt<=0) return 0;
 return Math.round(teil/gesamt*100);
 }

var ajaxObj=ajax();    // XMLHttpRequest-Object
var id;                // ID (of the parent node that should be opened/closed).
var expand=false;      // are we currebntly expanding the whole tree?
var js_data;           // some values transferred from PHP via JSON
var zeit=new Date();   // time is appened to request to avoid browser caching

// js_data['parents']: Array, which contains status of parent nodes (0/undefined=collapsed & nor loaded, 1=collapsed & loaded, 2=expanded and loaded)



function menuclick(id) // when clicking on a perent node
 {
 var nodeimage, i; // nodeimage: Which plus/minus-image is used? i: counter
 if(id==1 && js_data['host_anz']==1 && js_data['parents'][id]==2) nodeimage=5;
 else if(id==1) nodeimage=1;
 else if(id==js_data['host_anz'] && js_data['parents'][id]==2) nodeimage=3;
 else nodeimage=2;

 var row;

 if(!expand && js_data['parents'][id]==2) // loaded and expanded => collapse
  {
  document.getElementById('pic'+id).src='extensions/ajaxTree/plus'+nodeimage+'.gif';
  js_data['parents'][id]=1;
  for(i=1; i<=js_data['childs'][id]; i++) if(row=document.getElementById('row'+id+'_'+i)) row.style.display='none';
  }
 else if(js_data['parents'][id]==1) // loaded and collapsed => expand
  {
  document.getElementById('pic'+id).src='extensions/ajaxTree/minus'+nodeimage+'.gif';
  js_data['parents'][id]=2;
  for(i=1; i<=js_data['childs'][id]; i++) document.getElementById('row'+id+'_'+i).style.display='';
  if(expand && id<js_data['host_anz']) menuclick(id+1);
  else
   {
   expand=false;
   hide_waitbox();
   }
  }
 else if(js_data['parents'][id]!=2) // not loaded => load data
  {
  js_data['parents'][id]=2;
  document.getElementById('pic'+id).src='extensions/ajaxTree/minus'+nodeimage+'.gif';
  nachladen(id);
  }
 }

function nachladen(pid)  // load data of child nodes
 {
 if(!expand) 
  {
  waitmessage();
  }
 ajaxObj.open('get','extensions/ajaxTree/refpages.php?'+js_data['sid']+'&id='+(pid-1)+'&stamp='+zeit.getTime(),true);
 id=pid; // pass id via global variable to addRows
 ajaxObj.onreadystatechange=addRows;
 ajaxObj.send(null);
 }


function addRows()  // got loaded data, add new rows to the table for the childs
 {
 if(ajaxObj.readyState==4)
  {
  if(ajaxObj.responseText.length<200 && ajaxObj.responseText.search('<!-- NOT_LOGGED_IN -->')==0)
   {
   messagebox('<p>'+ajaxObj.responseText+'</p>');
   }
  else
   {
   var child,zeile,spalte1,spalte2,spalte3,spalte4,bild1,link2,gekuerzt,text2,text3,bild4b,bild4b,text4,proz,proz2;
   var children=ajaxObj.responseText.split("\n"); // split string into individual childs
   
   if(id==js_data['host_anz']) var ziel_zeile=document.getElementById('row'+id).nextSibling;
   else var ziel_zeile=document.getElementById('row'+(id+1));
 
   for(var i=1; i<=js_data['childs'][id]; i++)
    {
    child=children[i-1].split('@'); // split data of this child
    
    // generate new HTML elements for the new row
    zeile=document.createElement('tr');
    spalte1=document.createElement('td');
    spalte2=document.createElement('td');
    spalte3=document.createElement('td');
    spalte4=document.createElement('td');
    bild1=document.createElement('img');
    link2=document.createElement('a');
    if(child[0].length>100) gekuertzt=child[0].substring(0,100)+'[...]';
    else  gekuertzt=child[0];
    text2=document.createTextNode(gekuertzt);
    text3=document.createTextNode(child[1]);
    bild4a=document.createElement('img');
    bild4b=document.createElement('img');
    
    text4=document.createTextNode(prozent(child[1],js_data['gesamt']));
    zeile.id='row'+id+'_'+i;
    if(((js_data['childs'][id]-i+id)%2)==1) zeile.style.backgroundColor='#DFE2FF';
    // Create Contents for columns
    if(i==1 && id==js_data['host_anz']) bild1.src='extensions/ajaxTree/line2.gif';
    else bild1.src='extensions/ajaxTree/line.gif';
    link2.href='anonymous_redirect.php?go_anonym=http://'+js_data["hosts"][id]+child[0].replace(/&/g,'%26');
    link2.target='_blank';
    link2.className='ajaxTreeLink';
    link2.appendChild(text2);
    proz=prozent(child[2],js_data['gesamt']);
    proz2=Math.round(prozent(child[1],js_data['gesamt2'])/100*js_data['laenge']);
    bild4a.width=proz2;
    bild4a.height=10;
    bild4a.title=proz+'%';
    bild4b.width=js_data['laenge']-proz2;
    bild4b.height=10;
    bild4b.title=proz+'%';
    bild4a.src='img/bar1.gif';   
    bild4b.src='img/bar0.gif';
    // put content of columns inside the columns
    spalte1.appendChild(bild1);
    spalte2.appendChild(link2);
    spalte3.appendChild(text3);
    spalte4.appendChild(bild4a);
    spalte4.appendChild(bild4b);
    // put the columns into the row
    zeile.appendChild(spalte1);
    zeile.appendChild(spalte2);
    zeile.appendChild(spalte3);
    zeile.appendChild(spalte4);
    // put the row into the table
    document.getElementById('tabelle').insertBefore(zeile,ziel_zeile);
    }

   if(expand && id<js_data['host_anz']) menuclick(id+1);
   else
    {
    expand=false;
    hide_waitbox();
    }
   } 
  }
 }

function expandTree(id)
 {
 if(js_data['host_anz']<20 || confirm('Loading all referers can take a lot of time depending on the number of referers and your browser.\nContinue?'))
  {
  waitmessage();
  expand=true;
  menuclick(1);
  } 
 }
 
function collapseTree()
 {
 var id=1;
 while(id<=js_data['host_anz'])
  {
  if(js_data['parents'][id]==2)
   {
   menuclick(id);
   }
  id++;
  }
 }
