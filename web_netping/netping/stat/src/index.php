<?php
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

*** src/index.php ***
Funktion:    Eingangsseite, News, verbirgt Verzeichnisstruktur
Aufrufbar:   ja
Eingebunden: nein/ von src/include.php (Link)
*/

require_once('config_default.php');
require_once('../usr/config.php');

// set memory-limit
@ini_set('memory_limit', $config_stat_memory_limit . 'M');

session_start();
if(!$config_stat_lang_fix)
 {
 if(isset($_GET['lang']) && is_file('lang/'.basename($_GET['lang']).'.php'))  $_SESSION['lang']=basename($_GET['lang']);
 elseif(isset($_POST['lang']) && is_file('lang/'.basename($_POST['lang']).'.php'))  $_SESSION['lang']=basename($_POST['lang']);
 elseif(isset($_SESSION['lang']) && is_file('lang/'.basename($_SESSION['lang']).'.php'))  ;
 elseif(isset($_COOKIE['CrazyStat_lang']) && is_file('lang/'.basename($_COOKIE['CrazyStat_lang']).'.php'))  $_SESSION['lang']=basename($_COOKIE['CrazyStat_lang']);
 else $_SESSION['lang']=$config_stat_lang;
 setcookie('CrazyStat_lang',$_SESSION['lang'],time()+3600*90);
 $config_stat_lang=$_SESSION['lang'];
 }
require_once('lang/'.$config_stat_lang.'.php');

$christosoft_url='http://'.($config_stat_lang=='de'?'www':'en').'.christosoft.de';


header('Content-Type: text/html; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="generator" content="CrazyStat" />
  <title>CrazyStat 1.71 RC1</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <link href="style2.css" rel="stylesheet" type="text/css" />
  <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
  <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
 </head>
 <body>
  <div align="center">
   <div id="outer_main">
    <h1 style="font-size:24pt;"><img src="img/crazystat.png" alt="CrazyStat" title="" width="233" height="50" /></h1>
    <div id="panel"><div class="reiter" id="r0_1"><?php echo L_LOGIN_MENU_HOME; ?></div><div class="reiter" id="r1_0" style="border-left: 1px solid black"><a href="show_stat.php"><?php echo L_LOGIN_MENU_LOGIN; ?></a></div><div class="reiter" id="r2_0"><a href="show_stat.php?change_pass"><?php echo L_LOGIN_MENU_CHANGE_PASSWORD; ?></a></div></div>
    <div id="inner_main" align="center">
     <h2><?php echo L_INDEX_WELCOME; ?></h2>
     <p><?php echo L_INDEX_THIS_IS_CRAZYSTAT; ?></p>
     <p><?php echo L_INDEX_INFORMATION; ?><br />
     <a href="<?php echo $christosoft_url; ?>" target="_blank"><?php echo $christosoft_url; ?></a></p>
     <table class="gitter">
      <tr><td><?php echo L_INDEX_INSTALLED_VERSION; ?></td><td style="vertical-align:top"><?php echo L_INDEX_CURRENT_VERSION; ?></td></tr>
      <tr><td width="200">1.71 RC1</td><td style="vertical-align:top; padding:0px; margin:0px;"><iframe src="http://www.christosoft.de/crazystat_version.php?v=171" frameborder="0" height="60" width="200"></iframe></td></tr>
     </table>
     <p>
      <?php echo L_INDEX_NEWS; ?> <a href="<?php echo $christosoft_url; ?>" target="_blank"><?php echo $christosoft_url; ?></a>:<br />
      <iframe src="http://www.christosoft.de/news.php?v=171&amp;lang=<?php echo $config_stat_lang; ?>" frameborder="0" height="200" width="550"></iframe>
     </p>
    </div>
   </div>
   <div id="hotscripts">
    <a href="http://www.hotscripts.com/rate/118512/?RID=N568334" title="Rate our Script at Hot Scripts" target="_blank"><img src="http://cdn.hotscripts.com/img/widgets/btn_88x31-1.gif" alt="Rate our Script at Hot Scripts" style="border: 0;" /></a> <span style="display: block; width: 88px; text-align: center; margin: 2px 0 0; padding: 0; color: #999; font: normal 9px Arial, Helvetica, sans-serif;">Listed in <a target="_blank" href="http://www.hotscripts.com/category/scripts/php/scripts-programs/web-traffic-analysis/?RID=N568334" style="color: #666; font-size: 9px; text-decoration: none;">HotScripts</a></span> 
   </div>
   <p class="copyright">&copy; Copyright 2004-2012 <a href="<?php echo $christosoft_url; ?>" target="_blank">Christopher Kramer</a></p>
  </div>
 </body>
</html>