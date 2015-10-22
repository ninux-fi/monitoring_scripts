/*

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

*** module_refresh.js ***
Funktion:    Ajax-Funktion zum Neuladen von Modulen
Aufrufbar:   nein
Eingebunden: von show_stat.php
*/


var zeit;
var mod_count=0;
var all=false;
var list_modules= new Array('hit','weekday','month','day','hour','browser','file','resolution','colordepth','system','referer','keyword');
var mk=false;
var ajaxTree=true;
var modul='';

function refresh(lmodul,parameter)  // Refresh module data via ajax
 {
 waitmessage();
 if(!use_ajax) return true;
 zeit=new Date();          // time will be appended to Request to avoid browser caching
 ajaxObj_Refresh=ajax();    // XMLHttpRequest-Object
 if(lmodul=='ALL')
  {
  modul=list_modules[mod_count];
  mod_count ++;
  if(list_modules[mod_count]) all=true;
  else all=false;
  }
 else
  {
  modul=lmodul;
  all=false;
  }
 ajaxObj_Refresh.open('get','module_out.php?'+sid+'&modul='+(modul)+'&'+parameter+'&stamp='+zeit.getTime(),true);
 switch(parameter)
  {
  case 'mode=referer_tree_mk':
   mk=true;
   ajaxTree=false;
   break;
  case 'mode=referer_tree0':
   mk=false;
   ajaxTree=false;
   break;
  case 'mode=referer_tree_ajax':
   mk=false;
   ajaxTree=true;
   break;
  }
 ajaxObj_Refresh.onreadystatechange=replaceData;
 ajaxObj_Refresh.send(null);
 if(lmodul=='referer' && ajaxTree) refreshJSdata();
 return false;
 }

function replaceData()  // got new data from server, replace HTML with net data
 {
 if(ajaxObj_Refresh.readyState==4)
  {
  if(ajaxObj_Refresh.responseText.length<200 && ajaxObj_Refresh.responseText.search('<!-- NOT_LOGGED_IN -->')==0)
   {
   messagebox('<p>'+ajaxObj_Refresh.responseText+'</p>');
   }
  else
   {
   document.getElementById('module_'+modul).innerHTML=ajaxObj_Refresh.responseText;
   hide_waitbox();
   }
  if(modul=='referer' && mk) convertTrees();
  ajaxObj_Refresh=null;
  if(all) refresh('ALL','mode=refresh');
  if(sortabletable && modul!='hit' && (modul!='referer' || (!mk && !ajaxTree))) t=new SortableTable(document.getElementById('daten_'+modul), 100);
  if(lytebox)
   {
   myLytebox=null;
   initLytebox();
   } 
  }
 }


function refreshJSdata() // Refresh data array "js_data" with new values via AJAX (JSON)
 {
 ajaxObj_Refresh_js=ajax();    // XMLHttpRequest-Object
 ajaxObj_Refresh_js.open('get','module_out.php?'+sid+'&modul=referer&js_data=1&stamp='+zeit.getTime(),true);
 ajaxObj_Refresh_js.onreadystatechange=replaceJSData;
 ajaxObj_Refresh_js.send(null);
 }
 
function replaceJSData()  // new data for "js_data" is there - replace data with new data
 {
 if(ajaxObj_Refresh_js.readyState==4)
  {
  js_data=JSON.parse(ajaxObj_Refresh_js.responseText);
  }
 }
