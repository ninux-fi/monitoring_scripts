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

 *** src/stat.php ***
Funktion:    speichert Nutzerdaten und bindet Counter ein
Aufrufbar:   (ja)
Eingebunden: von src/include.php (als Bild)

 */

ignore_user_abort(true);

// include config-data
include_once('config_default.php');
include_once('../usr/config.php');
include_once('../usr/config_pass.php'); // load $config_salt_str
require_once('lang/' . $config_stat_lang . '.php');
require_once('log_funcs.php');

function show_error($message2, $message1 = L_MSG_ERR) {
	$message1 = strtoupper($message1);
	header('Content-type: image/png');
	$handle = imagecreate(150, 30);
	$mes_farbe = imagecolorallocate($handle, 255, 255, 255);
	$mes_farbe = imagecolorallocate($handle, 255, 0, 0);
	imagestring($handle, 1, 1, 1, $message1, $mes_farbe);
	imagestring($handle, 1, 1, 15, $message2, $mes_farbe);
	imagepng($handle);
	imagedestroy($handle);
	exit;
}

function do_not_track() {
	// check whether a user enabled do-not-track (DNT)
	if (isset($_SERVER['HTTP_DNT'])) {
		if ($_SERVER['HTTP_DNT'] == 1)
			return true;
	} elseif (function_exists('getallheaders')) {
		foreach (getallheaders() as $k => $v) {
			if (strtolower($k) === "dnt" && $v == 1)
				return true;
		}
	}
	return false;
}

function anonymize_ip($ip, $resolution, $colors) {
	global $config_salt_str;
	$octects = explode('.', $ip);
	// keep the first 2 octets
	$ip_a = $octects[0] . '.' . $octects[1] . '.';
	// calculate user characteristic numbers
	$user_ua = ord_all($_SERVER['HTTP_USER_AGENT']);
	$user_res = ord_all($resolution);
	$user_colors = ord_all($colors);
	$user_salt = ord_all($config_salt_str);
	$user_ip = ord_all(md5($config_salt_str . $ip));
	// 3rd octet: sum up user characteristics
	$ip_a .= ($user_ip + $user_colors) % 256 . '.';
	// 4th octet: sum up user characteristics
	$ip_a .= ($user_ip + $user_ua + $user_res + $user_salt) % 256;
	// add an asterisk to make clear the IP has been anonymized
	$ip_a .= '*'; 
	return $ip_a;
}

function ord_all($string) {
	// calculates cross sum of the ord-value of all characters
	$n = 0;
	for ($i = 0; $i < strlen($string); $i++) {
		$n += ord($string[$i]);
	}
	return $n;
}

// read GET-data for resolution, file and referer
if (empty($_GET['breite']) || empty($_GET['hoehe']))
	$resolution = '?';
else
	$resolution = $_GET['breite'] . ' x ' . $_GET['hoehe'];
if (empty($_GET['colors']))
	$colors = '';
else
	$colors = $_GET['colors'];
if (empty($_GET['datei']))
	$file = '?';
else
	$file = $_GET['datei'];
if (empty($_GET['referer']))
	$referer = '';
else
	$referer = $_GET['referer'];

if ($config_ip_anonymous) {
	$ip = anonymize_ip($_SERVER['REMOTE_ADDR'], $resolution, $colors);
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
}

if (isset($_GET['user'])
		&& isset($user_config[$_GET['user']]['logfile_folder']))
	$config_logfile_folder = $user_config[$_GET['user']]['logfile_folder'];

// overwrite counter_enabled?
if (isset($_GET['set_counter_enabled'])
		&& $_GET['set_counter_enabled'] == 'true')
	$config_counter_enabled = true;
elseif (isset($_GET['set_counter_enabled'])
		&& $_GET['set_counter_enabled'] == 'false')
	$config_counter_enabled = false;

// overwrite counter_file_name?
if (isset($_GET['set_counter_file_name'])
		&& is_file(
				'../usr/' . $config_counter_folder . '/'
						. basename($_GET['set_counter_file_name']))) {
	$config_counter_file_name = basename($_GET['set_counter_file_name']);
}

// Lese counter.log ein
$counterfile_path = '../usr/' . $config_logfile_folder . '/'
		. $config_count_file;
if (is_file($counterfile_path)) {
	$counterfile_data = file($counterfile_path);
	for ($i = 0; $i <= 2; $i++) {
		if (!isset($counterfile_data[$i]) || $counterfile_data[$i] == '') {
			// Keine Daten. Eventuell greift gerade ein anderer Prozess auf die Datei zu, um sie zu updaten und hat die Datei vor dem
			// Schreiben geleert.
			$versuche = 0;
			while ((!isset($counterfile_data[$i])
					|| $counterfile_data[$i] == '') && $versuche <= 10) {
				sleep(1); // warte 1 Sekunde, um dem andern Prozess Zeit zu geben
				$counterfile_data = file($counterfile_path);
				$versuche++;
			}
			if ((!isset($counterfile_data[$i]) || $counterfile_data[$i] == '')
					&& $versuche >= 10)
				show_error(
						$config_count_file . ' ' . L_STAT_MSG_ERR_COUNTER_FILE);
		} else
			$counterfile_data[$i] = trim($counterfile_data[$i]);
	}
} else {
	$counterfile_data = array(0, 0, 0);
}

$neue_datei = false;
$logfile_path = get_last_log();
if ($neue_datei)
	$counterfile_data[0] = 0;
// Wenn eine neue Datei angelegt wurde muss am Anfang der Datei angefangen werden zu lesen.
$readpos = $counterfile_data[0]; // Ab diesem Byte wird angefangen die (letzte) Logdatei auszuwerten

$logfile_handle = fopen($logfile_path, 'r');
if ($logfile_handle === false)
	show_error(L_STAT_MSG_ERR_READ_ERROR . ' F1');
fseek($logfile_handle, $readpos); // Starte nicht am Anfang, sondern da, wo das letzte Mal die neuen Zugriffe begonnen haben
$block = time() - $config_ip_block_time * 60;// Alle Zugriffe nach $block fallen unter die IP-Sperre
$counter_reload = false;

while (!feof($logfile_handle)) {
	$zeile = fgets($logfile_handle);
	if (empty($zeile))
		continue;

	$daten = explode('#', $zeile);

	if ($daten[1] >= $block && !isset($writepos)) {
		$writepos = ftell($logfile_handle) - strlen($zeile);
		/* Die aktuelle Zeile ist die erste, an der "neue" Zugriffe anfangen. D.h. diese Zugriffe liegen innerhalb der letzten
		   $config_ip_block_time Minuten. Beim naechsten Mal wird am Anfang dieser Zeile angefangen zu lesen.                  */
	}
	if ($daten[1] >= $block && $daten[2] == $ip) {
		$counter_reload = true;
		break; // Reaload wurde erkannt.
	}
}
if (!isset($writepos))
	$writepos = ftell($logfile_handle);
// Keine Zugriffe in den letzten (Sperr-)Minuten=>Starte das naechste Mal bei diesem Zugriff

fclose($logfile_handle);

$anzahl_hits_ip = $counterfile_data[1];
$anzahl_hits = $counterfile_data[2];

// Robot-Block
$robots = file('../usr/keywords/robots.txt');
$is_robot = false;
foreach ($robots as $robot) {
	$robot = trim($robot);
	if (!empty($robot) && strpos($_SERVER['HTTP_USER_AGENT'], $robot) !== false) {
		$is_robot = true;
		break;
	}
}
if (!$counter_reload && !$is_robot) {
	$anzahl_hits_ip++;
}
if (!$is_robot) {
	$anzahl_hits++;
}

// fertig mit Lesen, fange an zu schreiben

if ((!$config_respect_dnt || !do_not_track())
		&& (!isset(
				$_COOKIE['CrazyStat_DoNotCount_'
						. md5($config_logfile_folder . $config_salt_str)])
				|| $_COOKIE['CrazyStat_DoNotCount_'
						. md5($config_logfile_folder . $config_salt_str)]
						!= 'true')) // wenn sperr-cookie nicht gesetzt, schreibe neuen Logeintrag
 {
	// Aktualisiere counter.log
	$counterfile_handle = fopen($counterfile_path, 'w');
	@fwrite($counterfile_handle,
			$writepos . "\n" . $anzahl_hits_ip . "\n" . $anzahl_hits);
	@fclose($counterfile_handle);
	if ($counterfile_handle === false)
		show_error(L_STAT_MSG_ERR_WRITE_ERROR . ' F2');

	// Es wird die neue Zeile zusammengesetzt
	$zeile = '';
	unset($daten);
	$daten[0] = $anzahl_hits;
	$daten[1] = time();
	$daten[2] = $ip;
	$daten[3] = $file;
	$daten[4] = $_SERVER['HTTP_USER_AGENT'];
	$daten[5] = $resolution;
	$daten[6] = $referer;
	$daten[7] = $colors;
	foreach ($daten as $id => $data) {
		$data = str_replace('#', '&num;', $data);
		$zeile .= ($id != 0 ? '#' : '') . $data;
	}
	$zeile .= "\n";

	@$logfile_handle = fopen($logfile_path, 'a');
	if ($logfile_handle === false)
		show_error(L_STAT_MSG_ERR_WRITE_ERROR . ' F1');
	fwrite($logfile_handle, $zeile);
	fclose($logfile_handle);
} elseif ($config_respect_dnt && do_not_track()) {
	// do not track
	if($config_counter_dnt_text !== false) {
		$config_counter_reload_show_text = $config_counter_dnt_text;
		$config_counter_reload = true;
		$config_counter_reload_show = true;
		$counter_reload = true;
	}
} elseif ($config_counter_cookie_text !== false) // Sperr-Cookie existiert=>"Cookie"-Text
 {
	// sperr-hinweis zeigen und Link setzen
	$config_counter_reload_show_text = $config_counter_cookie_text;
	$config_counter_reload = true;
	$config_counter_reload_show = true;
	$counter_reload = true;
}

// Counter bzw. Ersatzbild wird ausgegeben
if ($config_counter_enabled == false) {
	if ($config_counter_alternative === false) {
		// directly output a blank gif without HTTP-redirect
		header('Content-Type: image/gif');
		header('Content-Length: 807');
		// now output a blank 1x1 gif
		echo base64_decode(
				'R0lGODlhAQABAPcAAAAAAAAAMwAAZgAAmQAAzAAA/wAzAAAzMwAzZgAzmQAzzAAz/wBmAABmMwBmZgBmmQBmzABm/wCZAACZMwCZZgCZmQCZzACZ/wDMAADMMwDMZgDMmQDMzADM/wD/AAD/MwD/ZgD/mQD/zAD//zMAADMAMzMAZjMAmTMAzDMA/zMzADMzMzMzZjMzmTMzzDMz/zNmADNmMzNmZjNmmTNmzDNm/zOZADOZMzOZZjOZmTOZzDOZ/zPMADPMMzPMZjPMmTPMzDPM/zP/ADP/MzP/ZjP/mTP/zDP//2YAAGYAM2YAZmYAmWYAzGYA/2YzAGYzM2YzZmYzmWYzzGYz/2ZmAGZmM2ZmZmZmmWZmzGZm/2aZAGaZM2aZZmaZmWaZzGaZ/2bMAGbMM2bMZmbMmWbMzGbM/2b/AGb/M2b/Zmb/mWb/zGb//5kAAJkAM5kAZpkAmZkAzJkA/5kzAJkzM5kzZpkzmZkzzJkz/5lmAJlmM5lmZplmmZlmzJlm/5mZAJmZM5mZZpmZmZmZzJmZ/5nMAJnMM5nMZpnMmZnMzJnM/5n/AJn/M5n/Zpn/mZn/zJn//8wAAMwAM8wAZswAmcwAzMwA/8wzAMwzM8wzZswzmcwzzMwz/8xmAMxmM8xmZsxmmcxmzMxm/8yZAMyZM8yZZsyZmcyZzMyZ/8zMAMzMM8zMZszMmczMzMzM/8z/AMz/M8z/Zsz/mcz/zMz///8AAP8AM/8AZv8Amf8AzP8A//8zAP8zM/8zZv8zmf8zzP8z//9mAP9mM/9mZv9mmf9mzP9m//+ZAP+ZM/+ZZv+Zmf+ZzP+Z///MAP/MM//MZv/Mmf/MzP/M////AP//M///Zv//mf//zP///wAAAA0NDRoaGigoKDU1NUNDQ1BQUF1dXWtra3h4eIaGhpOTk6Ghoa6urru7u8nJydbW1uTk5PHx8f///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAkAANcALAAAAAABAAEAAAgEAK8FBAA7');
	} else {
		header('Location: ' . $config_counter_alternative);
	}
} else {
	include('create_counter_image.php');
}
?>
