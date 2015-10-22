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

 *** src/analyze.php ***
Function:                        analyze logfiles
Meant to be opened in browser:   no
Included:                        by src/show_stat.php, module_out.php (include)
 */

require_once('general_include.php');
require_once('preset.php');

if (isset($_GET['d']) && $_GET['d'])
	$_SESSION['debug'] = true;
elseif (isset($_GET['d']) || !isset($_SESSION['debug']))
	$_SESSION['debug'] = false;

if ($_SESSION['debug']) {
	error_reporting(E_STRICT);
	$ram_init = memory_get_usage();
}

// set max_execution_time
@set_time_limit(150);
$script_complete = false;
// set a shutdown function to catch timeouts
@register_shutdown_function('script_ende');

date_default_timezone_set($config_stat_timezone);

function getmicrotime() {
	list($usec, $sec) = explode(' ', microtime());
	return ($sec . substr($usec, 1, 7));
}
$start = getmicrotime();

// Get Time-Data once
$time['stamp'] = time();
$time['month'] = date('m');
$time['year'] = date('Y');

function clean_directory($dir, $pattern = '/.*/') {
	global $message_error;
	foreach (new DirectoryIterator($dir) as $fileInfo) {
		$file = $fileInfo->getFilename();
		if ($fileInfo->isFile() && $file[0] != '.') {
			if (preg_match($pattern, $file) && @unlink($dir . $file) == false)
				$message_error .= L_ANAYLZE_MSG_ERR_CACHE_NOT_DELETED . ' ('
						. $file . ')<br /><br />';
		}
	}
}

if (!is_dir('../usr/' . $config_logfile_folder)) {
	if (isset($_GET['mkdir']) && $_GET['mkdir']) {
		mkdir('../usr/' . $config_logfile_folder, 0777);
		clean_directory('../usr/cache/',
				'/.*_' . md5($config_logfile_folder) . '\.php(\.gz)?/');
		$message_ok .= L_ANALYZE_MSG_OK_CACHE_EXISTED . '<br />';
	} else
		$message_fatal .= L_ANALYZE_MSG_ERR_LOGFOLDER_INEXISTENT . ' (usr/'
				. $config_logfile_folder
				. ')!<br /><a href="show_stat.php?mkdir=1">'
				. L_ANALYZE_MSG_ERR_CREATE_FOLDER . '</a><br />';
}
$logdatei_name = get_first_log();
if (!is_file($logdatei_name) && !is_file($logdatei_name . '.gz')
		&& !isset($message_fatal))
	$message_fatal .= L_ANALYZE_MSG_ERR_FILE_CREATION_FAILED . ' ('
			. $logdatei_name . '). ' . L_ANAYLZE_MSG_ERR_CHECK_RIGHTS;

function clear_module($modul) {
	global $time, $config_stat_weekdays_sunday_first;
	$_SESSION['module_' . $modul . '_data'] = array();
	$_SESSION['module_' . $modul . '_data_timestamps'] = array();
	$_SESSION['module_' . $modul . '_total'] = 0;
	$_SESSION['module_' . $modul . '_max'] = 0;
	$_SESSION['module_' . $modul . '_max_name'] = '';

	switch ($modul) {
	case 'weekday':
		if ($config_stat_weekdays_sunday_first)
			$_SESSION['module_weekday_data'][0] = 0;
		for ($i = 1; $i <= 6; $i++)
			$_SESSION['module_weekday_data'][$i] = 0;
		if (!$config_stat_weekdays_sunday_first)
			$_SESSION['module_weekday_data'][7] = 0;
		break;
	case 'hit':
		$_SESSION['module_hit_data']['gesamt'] = 0;
		$_SESSION['module_hit_data']['gesamt_ip'] = 0;
		$_SESSION['module_hit_data']['diesen_monat'] = 0;
		$_SESSION['module_hit_data']['letzten_monat'] = 0;
		$_SESSION['module_hit_data']['user_online'] = 0;
		$_SESSION['module_hit_data']['max'] = 0;
		$_SESSION['module_hit_data']['durchschnitt'] = 0;
		break;
	case 'hour':
		for ($i = 0; $i <= 23; $i++)
			$_SESSION['module_hour_data'][$i] = 0;
		break;
	case 'month':
		for ($i = 1; $i <= 12; $i++)
			$_SESSION['module_month_data'][$i] = 0;
		break;
	case 'day':
		if (isset($_SESSION['set_day_time_span'])
				&& $_SESSION['set_day_time_span']
				&& isset($_SESSION['set_day_time_end'])
				&& isset($_SESSION['set_day_time_start'])) {
			// loop day by day through the timespan and create an empty entry for each day
			// as more than 31 entries is not possible (no month has more than 31 days), end if already 31 entries
			for ($test_stamp = $_SESSION['set_day_time_start'], $number_of_days = 0; $number_of_days
					<= 31 && $test_stamp <= $_SESSION['set_day_time_end']; $test_stamp += 86400, $number_of_days++) {
				$_SESSION['module_day_data'][date('j', $test_stamp)] = 0;
			}
			// now that entries are not in the correct order, bring them back in order
			ksort($_SESSION['module_day_data']);
		} else
			for ($i = 1; $i <= 31; $i++)
				$_SESSION['module_day_data'][$i] = 0;
		break;
	}
}

// convert relative time-span settings into absolute ones
foreach ($list_modules as $modul) {
	if (isset($_SESSION['set_' . $modul . '_time_rel'])
			&& $_SESSION['set_' . $modul . '_time_rel']) {
		if (!$_SESSION['set_' . $modul . '_time_span'])
			$changed[$modul] = true;
		else {
			if(isset($_SESSION['set_' . $modul . '_time_start']))
				$old_start = $_SESSION['set_' . $modul . '_time_start'];
			if(isset($_SESSION['set_' . $modul . '_time_end']))
				$old_end = $_SESSION['set_' . $modul . '_time_end'];
		}

		$_SESSION['set_' . $modul . '_time_span'] = true;
		$_SESSION['set_' . $modul . '_time_start'] = parse_str_to_time(
				$_SESSION['set_' . $modul . '_time_rel_start']);
		$_SESSION['set_' . $modul . '_time_end'] = parse_str_to_time(
				$_SESSION['set_' . $modul . '_time_rel_end']);
		$_SESSION['set_' . $modul . '_time_name'] = time_replace_chars(
				$_SESSION['set_' . $modul . '_time_rel_name']);
		if (isset($old_start) && isset($old_end)
				&& ($old_start != $_SESSION['set_' . $modul . '_time_start']
						|| $old_end != $_SESSION['set_' . $modul . '_time_end']))
			$changed[$modul] = true;
		unset($old_start);
		unset($old_end);
	}
}

if (empty($message_fatal)) {
	// read cache
	$all_cached = true;
	foreach ($list_modules as $modul) {
		if ($config_stat_cache
				&& isset($_SESSION['module_' . $modul . '_data'])
				&& (!isset($changed[$modul]) || !$changed[$modul])
				&& (!isset($_GET['reload']) || $_GET['reload'] != 'true')) {
			$cached[$modul] = true;
		} else {
			if (!isset($_GET['goon']))
				clear_module($modul);
			$cached[$modul] = false;
			$all_cached = false;
		}
	}

	// init modules: Hit, Weekday, hour, month, day
	foreach (array('weekday', 'hit', 'hour', 'month', 'day') as $modul) {
		if (!isset($_SESSION['module_' . $modul . '_data']))
			clear_module($modul);
	}

	// Continue (execution was stopped because of gtime limit)?
	if (isset($_GET['goon']) && $_GET['goon']) {
		$vars_to_load = array('pos', 'logdatei_name', 'hits_user_tag', 'ips',
				'ips_dateien', 'ips_online', 'log_file_number');
		foreach ($vars_to_load as $var)
			$$var = $_SESSION['goon_' . $var];
		unset($vars_to_load);
		$handle = open_logfile($logdatei_name, 'r');
		fseek($handle, $_SESSION['goon_pos']);
	} else
		$handle = open_logfile($logdatei_name, 'r');

	$pfc_all_cached = false;

	// start analyzing
	if (!$all_cached) {
		$_SESSION['module_hit_data']['visit_time_total'] = 0;
		$user_online_check_val = $time['stamp'] - 60 * $config_stat_user_online; // Value to check againt to block IPs
		// Read Robots-File
		$robots = file('../usr/keywords/robots.txt');
		foreach ($robots as $i => $robot) {
			$robots[$i] = trim($robot);
		}
		// Read Browser-File
		$browsers = file('../usr/keywords/browser.txt');
		foreach ($browsers as $browser_line) {
			$browser_line = trim($browser_line);
			$trenner = strpos($browser_line, '=');
			if ($trenner !== false)
				$browser_strings[substr($browser_line, 0, $trenner)] = substr(
						$browser_line, $trenner + 1);
			else
				$browser_strings[$browser_line] = $browser_line;
		}
		unset($browsers, $browser_line);
		// Read Systems-File
		$systems = file('../usr/keywords/os.txt');
		foreach ($systems as $system_line) {
			$system_line = trim($system_line);
			$trenner = strpos($system_line, '=');
			if ($trenner !== false)
				$system_strings[substr($system_line, 0, $trenner)] = substr(
						$system_line, $trenner + 1);
			else
				$system_strings[$system_line] = $system_line;
		}
		unset($systems, $system_line);
		// Read Query-Regex-File (for keywords-module)
		$queryregex = file('../usr/keywords/queryregex.txt');
		foreach ($queryregex as $regex) {
			$regex = trim($regex);
			$trenner = strpos($regex, "\t");
			// to support UTF8, pre_replace was replaced by mb_ereg, so we need
			// to remove / at the beginning and end
			$queryregex_str[trim(substr($regex, 0, $trenner),'/')] = substr($regex,
					$trenner + 1);
		}
		unset($trenner, $regex);

		$done = false;

		$diesen_monat_start = mktime(0, 0, 0, $time['month'], 1, $time['year']);
		$diesen_monat_ende = mktime(0, 0, 0, $time['month'] + 1, 1,
				$time['year']) - 1;
		$letzten_monat_start = mktime(0, 0, 0, $time['month'] - 1, 1,
				$time['year']);
		$letzten_monat_ende = mktime(0, 0, 0, $time['month'], 1, $time['year'])
				- 1;

		// Preset-File-Cache
		if ($config_stat_preset_file_cache && !isset($_GET['goon'])) {
			// check which preset is active (if any)
			$active_preset = check_which_preset();
			if ($active_preset !== false) {
				$pfc_compress = (in_array('compress.zlib',
						stream_get_wrappers()) && $config_stat_pfc_zlib);

				$pfc_filename = '../usr/cache/' . $active_preset . '_'
						. md5($config_logfile_folder) . '.php'
						. ($pfc_compress ? '.gz' : '');
			}
		}
		// include Cache
		if ($config_stat_preset_file_cache && $active_preset !== false
				&& is_file($pfc_filename)
				&& (!isset($_GET['clearcache'])
						|| (!$config_stat_password_protect
								&& !$config_stat_guest_cache_delete
								&& !isset($_SESSION['error_including_pfc']))
						|| (!$config_stat_user_cache_delete
								&& !isset($_SESSION['error_including_pfc'])))) {
			if (isset($_GET['clearcache']))
				$message_error .= L_ANALYZE_MSG_ERR_GUEST_CLEAR_CACHE;
			$error_including_pfc = true;
			@include_once(($pfc_compress ? 'compress.zlib://' : '')
					. $pfc_filename);
			$error_including_pfc = false;
			if (isset($save_timestamp)) {
				$save_monat = date('m', $save_timestamp);
				$save_jahr = date('Y', $save_timestamp);
			}
			// correct_time_overflow
			preset($active_preset, false, false, true);
			// correct diesen_monat and letzten_monat in Hits-Module
			if (isset($save_monat) && isset($save_jahr)
					&& ($save_monat != $time['month']
							|| $save_jahr != $time['year'])) { // new month
				if (($save_monat + 0 == $time['month'] - 1
						&& $save_jahr == $time['year'])
						|| ($save_monat == '12' && $time['month'] == '01'
								&& $save_jahr + 0 == $time['year'] - 1))
					$_SESSION['module_hit_data']['letzten_monat'] = $_SESSION['module_hit_data']['diesen_monat'];
				else
					$_SESSION['module_hit_data']['letzten_monat'] = 0;
				$_SESSION['module_hit_data']['diesen_monat'] = 0;
			}
			// user still online?
			if (isset($usr_on_stamp)) {
				foreach ($usr_on_stamp as $ip => $stamp) {
					if ($stamp < $user_online_check_val)
						unset($usr_on_stamp[$ip]);
					else
						$ips_online[$ip] = floor(
								$stamp / 60 / $config_stat_user_online);
				}
				$_SESSION['module_hit_data']['user_online'] = count(
						$usr_on_stamp);
			} else
				$_SESSION['module_hit_data']['user_online'] = 0;
			if (!is_file($logdatei_name) && !is_file($logdatei_name . '.gz')) {
				$message_error .= L_ANALYZE_MSG_ERR_FILE_NOT_FOUND . ' ('
						. $logdatei_name
						. ')<br /><a href="show_stat.php?clearcache=true&amp;reload=true">'
						. L_SHOWSTAT_CLEAR_CACHE . '</a><br /><br />';
				$pfc_all_cached = true;
				$done = true;
			} else {
				$handle = open_logfile($logdatei_name, 'r');
				if (isset($pos))
					fseek($handle, $pos);
				if (feof($handle)) { // everything cached? TODO: IS this needed at all?
					$name = split_log_name();
					$ext = $name['ext'];
					$name = $name['name'];
					$i = $log_file_number + 1;
					if (is_file($name . $i . $ext)
							|| is_file($name . $i . $ext . '.gz')) {
						$log_file_number = $i;
						$logdatei_name = $name . $i . $ext;
						$handle = open_logfile($name . $i . $ext, 'r');
						$log_line = trim(fgets($handle));
					} else {
						$done = true;
						$pfc_all_cached = true;
					}
				}
			}
		} elseif (isset($_GET['clearcache']) || !$cached['keyword']) {
			if (isset($_GET['clearcache'])
					&& ($config_stat_password_protect
							|| $config_stat_guest_cache_delete)
					&& $config_stat_user_cache_delete) {
				clean_directory('../usr/cache/',
						'/.*_' . md5($config_logfile_folder) . '\.php(\.gz)?/');
			}
			unset($_SESSION['module_keyword_orig']);
		}
		unset($_SESSION['error_including_pfc']);

		// global IP-blocking active (i.e. all modules use IP-blocking)?
		$ip_sperre_global = true;
		foreach ($list_modules as $modul)
			if (isset($_SESSION['set_' . $modul . '_ip']) && !$_SESSION['set_' . $modul . '_ip'])
				$ip_sperre_global = false;

		// Start Analyzing
		while (!$done) {

			if (!$config_logfile_maxsize)
			// for performance reasons, do not use the generic function if no spliiting used 
				$log_line = fgets($handle);
			else
				@$log_line = get_next_line();

			if ($log_line === false) {
				// we are at the end of all log files, so stop.
				break;
			}

			$daten = explode('#', $log_line);
			unset($log_line);
			$col_number = count($daten);
			if (!is_array($daten) || $col_number < 5 || $col_number > 8) {
				// skip corrupt or empty lines
				continue;
			}

			foreach ($daten as $data_i => $data) {
				// unescape separation-character #
				$daten[$data_i] = str_replace('&num;', '#', $data);
			}

			foreach ($robots as $robot) {
				if (strpos($daten[4], $robot) !== false) {
					// robot detected. Skip this line.
					continue 2;
				}
			}
			$daten[1] = intval($daten[1]);
			$timecontrol = floor($daten[1] / 60 / $config_ip_block_time);

			// totoal hits
			if (!$cached['hit'])
				$_SESSION['module_hit_data']['gesamt']++;

			// file
			if (!$cached['file']) {
				if (isset($config_stat_files_delete)
						&& is_array($config_stat_files_delete))
					foreach ($config_stat_files_delete as $delete_this)
						$daten[3] = str_replace($delete_this, '', $daten[3]);
				if (isset($config_stat_files_replace)
						&& is_array($config_stat_files_replace))
					foreach ($config_stat_files_replace as $from => $to)
						$daten[3] = str_replace($from, $to, $daten[3]);
				if (isset($config_stat_files_preg_replace)
						&& is_array($config_stat_files_preg_replace))
					foreach ($config_stat_files_preg_replace as $from => $to)
						$daten[3] = preg_replace($from, $to, $daten[3]);

				if ((!$_SESSION['set_file_ip']
						|| (!isset($ips_dateien[$daten[2] . '#' . $daten[3]])
								|| $ips_dateien[$daten[2] . '#' . $daten[3]]
										!= $timecontrol))
						&& (!$_SESSION['set_file_time_span']
								|| ($_SESSION['set_file_time_start']
										<= $daten[1]
										&& $_SESSION['set_file_time_end']
												> $daten[1]))) {
					if ($daten[3] == '?')
						$daten[3] = '/' . L_ANALYZE_UNKNOWN_FILE;
					@$_SESSION['module_file_data'][$daten[3]]++;
					if ($_SESSION['set_file_time_rel'])
						$_SESSION['module_file_data_timestamps'][$daten[3]][] = $daten[1];
				}
				@$ips_dateien[$daten[2] . '#' . $daten[3]] = $timecontrol;
			}
			// User online
			if (!$cached['hit'] && $daten[1] >= $user_online_check_val) {
				$online_zeitpunkt = floor(
						$daten[1] / 60 / $config_stat_user_online);
				if (!isset($ips_online[$daten[2]])
						|| $ips_online[$daten[2]] != $online_zeitpunkt) {
					$_SESSION['module_hit_data']['user_online']++;
					// for Preset-Cache
					$usr_on_stamp[$daten[2]] = $daten[1];
				}
				@$ips_online[$daten[2]] = $online_zeitpunkt;
				unset($online_zeitpunkt);
			}

			// avg visiting time
			if (!isset($last_seen[$daten[2]])) {
				// not seen yet. So first seen...
				$first_seen[$daten[1]][] = $daten[2];
			}
			$last_seen[$daten[2]] = $daten[1];

			foreach ($first_seen as $first_time => $first_ips) {
				if ($first_time < $daten[1] - ($config_ip_block_time * 60)) {
					foreach ($first_ips as $first_ip) {
						// we assume after the last hit, the visitor stayed another 10 seconds on the site
						$visiting_time = $last_seen[$first_ip] - $first_time
								+ 10;
						$_SESSION['module_hit_data']['visit_time_total'] += $visiting_time;
						unset($last_seen[$first_ip]);
					}
					unset($first_seen[$first_time]);
				}
			}

			// Global IP-blocking: if all modules use IP-blocking (default) -> improve performance
			if (isset($ips[$daten[2]]) && $ips[$daten[2]] == $timecontrol)
				$reload = true;
			else
				$reload = false;
			@$ips[$daten[2]] = $timecontrol;
			if (!$reload || !$ip_sperre_global) {
				// Hit
				if (!$cached['hit']) {
					// total hits with ip-blocking
					if (!$reload)
						$_SESSION['module_hit_data']['gesamt_ip']++;
					// hits this month with ip-blocking
					if (!$reload && $diesen_monat_start <= $daten[1]
							&& $diesen_monat_ende > $daten[1])
						$_SESSION['module_hit_data']['diesen_monat']++;
					// hits last month with ip-blocking
					if (!$reload && $letzten_monat_start <= $daten[1]
							&& $letzten_monat_ende > $daten[1])
						$_SESSION['module_hit_data']['letzten_monat']++;
				}
				$datum = getdate($daten[1]);
				$daten['w'] = $datum['wday'];
				$daten['H'] = $datum['hours'];
				$daten['d'] = $datum['mday'];
				$daten['m'] = $datum['mon'];
				$daten['Y'] = $datum['year'];
				unset($datum);

				// user / day for max & average
				if (!$reload) {
					@$hits_user_tag[$daten['d'] . '.' . $daten['m'] . '.'
							. $daten['Y']]++;
				}
				// Weekday
				if (!$cached['weekday']) {
					if ((!$reload || !$_SESSION['set_weekday_ip'])
							&& (!$_SESSION['set_weekday_time_span']
									|| ($_SESSION['set_weekday_time_start']
											<= $daten[1]
											&& $_SESSION['set_weekday_time_end']
													> $daten[1]))) {
						@$_SESSION['module_weekday_data'][$daten['w']]++;
						if ($_SESSION['set_weekday_time_rel'])
							$_SESSION['module_weekday_data_timestamps'][$daten['w']][] = $daten[1];
					}
				}
				// hour
				if (!$cached['hour']) {
					if ((!$reload || !$_SESSION['set_hour_ip'])
							&& (!$_SESSION['set_hour_time_span']
									|| ($_SESSION['set_hour_time_start']
											<= $daten[1]
											&& $_SESSION['set_hour_time_end']
													> $daten[1]))) {
						@$_SESSION['module_hour_data'][intval($daten['H'])]++;
						if ($_SESSION['set_hour_time_rel'])
							$_SESSION['module_hour_data_timestamps'][intval(
									$daten['H'])][] = $daten[1];
					}
				}
				// month
				if (!$cached['month']) {
					if ((!$reload || !$_SESSION['set_month_ip'])
							&& (!$_SESSION['set_month_time_span']
									|| ($_SESSION['set_month_time_start']
											<= $daten[1]
											&& $_SESSION['set_month_time_end']
													> $daten[1]))) {
						@$_SESSION['module_month_data'][intval($daten['m'])]++;
						if ($_SESSION['set_month_time_rel'])
							$_SESSION['module_month_data_timestamps'][intval(
									$daten['m'])][] = $daten[1];
					}
				}
				// day
				if (!$cached['day']) {
					if ((!$reload || !$_SESSION['set_day_ip'])
							&& (!$_SESSION['set_day_time_span']
									|| ($_SESSION['set_day_time_start']
											<= $daten[1]
											&& $_SESSION['set_day_time_end']
													> $daten[1]))) {
						@$_SESSION['module_day_data'][intval($daten['d'])]++;
						if ($_SESSION['set_day_time_rel'])
							$_SESSION['module_day_data_timestamps'][intval(
									$daten['d'])][] = $daten[1];
					}
				}
				// Operating-System
				if (!$cached['system']) {
					if ((!$reload || !$_SESSION['set_system_ip'])
							&& (!$_SESSION['set_system_time_span']
									|| ($_SESSION['set_system_time_start']
											<= $daten[1]
											&& $_SESSION['set_system_time_end']
													> $daten[1]))) {
						$match = false;
						foreach ($system_strings as $system_string => $system_name) { // teste alle Systeme durch bis gefunden
							if (strpos($daten[4], $system_string) !== false) {
								@$_SESSION['module_system_data'][$system_name]++;
								if ($_SESSION['set_system_time_rel'])
									$_SESSION['module_system_data_timestamps'][$system_name][] = $daten[1];
								$match = true;
								break;
							}
						}
						if (!$match) {
							@$_SESSION['module_system_data'][L_ANALYZE_UNKNOWN_SYSTEM]++;
							if ($_SESSION['set_system_time_rel'])
								$_SESSION['module_system_data_timestamps'][L_ANALYZE_UNKNOWN_SYSTEM][] = $daten[1];
							if ($_SESSION['debug'])
								@$_SESSION['unknown_system'][$daten[4]]++;
						}
					}
				}
				// Browser
				if (!$cached['browser']) {
					if ((!$reload || !$_SESSION['set_browser_ip'])
							&& (!$_SESSION['set_browser_time_span']
									|| ($_SESSION['set_browser_time_start']
											<= $daten[1]
											&& $_SESSION['set_browser_time_end']
													> $daten[1]))) {
						$match = false;
						foreach ($browser_strings as $browser_string => $browser_name) { // teste alle Browser durch bis gefunden
							if (strpos($daten[4], $browser_string) !== false) {
								@$_SESSION['module_browser_data'][$browser_name]++;
								if ($_SESSION['set_browser_time_rel'])
									$_SESSION['module_browser_data_timestamps'][$browser_name][] = $daten[1];
								$match = true;
								break;
							}
						}

						if (!$match) {
							@$_SESSION['module_browser_data'][L_ANALYZE_UNKNOWN_BROWSER]++;
							if ($_SESSION['set_browser_time_rel'])
								$_SESSION['module_browser_data_timestamps'][L_ANALYZE_UNKNOWN_BROWSER][] = $daten[1];
							if ($_SESSION['debug'])
								@$_SESSION['unknown_browser'][$daten[4]]++;
						}
					}
				}
				// Resolution
				if (!$cached['resolution']) {
					if ((!$reload || !$_SESSION['set_resolution_ip'])
							&& (!$_SESSION['set_resolution_time_span']
									|| ($_SESSION['set_resolution_time_start']
											<= $daten[1]
											&& $_SESSION['set_resolution_time_end']
													> $daten[1]))) {
						if($daten[5]  == 'Sonstige / kein JS') $daten[5]='?'; // for logs from old CrazyStat versions
						elseif($daten[5] == 'undefined x undefined') $daten[5]=''; // old browsers (JS < 1.2)
						@$_SESSION['module_resolution_data'][$daten[5]]++;
						if ($_SESSION['set_resolution_time_rel'])
							$_SESSION['module_resolution_data_timestamps'][$daten[5]][] = $daten[1];
					}
				}
				// Color Depth
				if (!$cached['colordepth'] && isset($daten[7])) {
					if ((!$reload || !$_SESSION['set_colordepth_ip'])
							&& (!$_SESSION['set_colordepth_time_span']
									|| ($_SESSION['set_colordepth_time_start']
											<= $daten[1]
											&& $_SESSION['set_colordepth_time_end']
													> $daten[1]))) {
						if (empty($daten[7]) || !is_numeric($daten[7])
								|| $daten[7] >= 100 || $daten[7] < 0) {
							if ($config_stat_colordepth_unsaved) {
								@$_SESSION['module_colordepth_data'][L_ANALYZE_UNSAVED]++;
								if ($_SESSION['set_colordepth_time_rel'])
									$_SESSION['module_colordepth_data_timestamps'][L_ANALYZE_UNSAVED][] = $daten[1];
							}
						} else {
							if ($daten[7] == 32)
								$daten[7] = 24;
							@$_SESSION['module_colordepth_data'][$daten[7]]++;
							if ($_SESSION['set_colordepth_time_rel'])
								$_SESSION['module_colordepth_data_timestamps'][$daten[7]][] = $daten[1];
						}
					}
				}
				// Referer
				if (!$cached['referer']) {
					if (isset($config_stat_referer_replace)
							&& is_array($config_stat_referer_replace))
						foreach ($config_stat_referer_replace as $from => $to)
							@$daten[6] = str_replace($from, $to, $daten[6]);

					if ($config_stat_limit['referer'] != 0
							&& (!$reload || !$_SESSION['set_referer_ip'])
							&& (!$_SESSION['set_referer_time_span']
									|| ($_SESSION['set_referer_time_start']
											<= $daten[1]
											&& $_SESSION['set_referer_time_end']
													> $daten[1]))) {
						if (!isset($daten[6]))
							$daten[6] = '';
						if (!empty($daten[6]) || $config_stat_referer_empty) {
							// ignore referer?
							$ignore = false;
							if (is_array($config_stat_referer_ignore)) {
								foreach ($config_stat_referer_ignore as $page)
									if (strpos($daten[6], $page) === 0)
										$ignore = true;
							} elseif (is_string($config_stat_referer_ignore))
								if (strpos($daten[6], $page) === 0)
									$ignore = true;
							if (!$ignore) {
								@$_SESSION['module_referer_data'][$daten[6]]++;
								if ($_SESSION['set_referer_time_rel'])
									$_SESSION['module_referer_data_timestamps'][$daten[6]][] = $daten[1];
							}
						}
					}
				}
				// Keywords
				if (!$cached['keyword']) {
					if ($config_stat_limit['keyword'] != 0
							&& (!$reload || !$_SESSION['set_keyword_ip'])
							&& (!$_SESSION['set_keyword_time_span']
									|| ($_SESSION['set_keyword_time_start']
											<= $daten[1]
											&& $_SESSION['set_keyword_time_end']
													> $daten[1]))) {
						if (!isset($daten[6]))
							$daten[6] = '';
						else {
							foreach ($queryregex_str as $regex => $key) {
								if (mb_ereg($regex, $daten[6], $keyword)) {
									$keyword[$key] = trim(urldecode($keyword[$key]));
									if(function_exists('mb_check_encoding') && !mb_check_encoding($keyword[$key])) {
									 /* the utf8-string is somehow corrupted.
									  Decode to keep at least the ANSI-characters...
									  */
                                     $keyword[$key] = utf8_decode($keyword[$key]);
                                    }
									$orig_key = $keyword[$key];
									// sort keywords
									$keyword[$key] = explode(' ',
											mb_strtolower($keyword[$key]));
									sort($keyword[$key]);
									$keyword[$key] = implode(' ',
											$keyword[$key]);
									// shorten
									if (mb_strlen($keyword[$key]) > 35) {
										$keyword[$key] = '<span title="'
												. htmlentities($keyword[$key], ENT_COMPAT, "UTF-8")
												. '">'
												. htmlentities(
														mb_substr($keyword[$key],
																0, 30), ENT_COMPAT, "UTF-8")
												. '[...]</span>';
										$orig_key = '<span title="'
												. htmlentities($orig_key, ENT_COMPAT, "UTF-8")
												. '">'
												. htmlentities(
														mb_substr($orig_key, 0, 30), ENT_COMPAT, "UTF-8")
												. '[...]</span>';
									} else {
										$keyword[$key] = htmlentities(
												$keyword[$key], ENT_COMPAT, "UTF-8");
										$orig_key = htmlentities($orig_key, ENT_COMPAT, "UTF-8");
									}
									@$_SESSION['module_keyword_data'][$keyword[$key]]++;
									if (isset($_SESSION['set_keywords_time_rel']) && $_SESSION['set_keywords_time_rel'])
										$_SESSION['module_keyword_data_timestamps'][$keyword[$key]][] = $daten[1];
									if ($keyword[$key] != mb_strtolower($orig_key)
											&& !isset(
													$_SESSION['module_keyword_orig'][$keyword[$key]]))
										$_SESSION['module_keyword_orig'][$keyword[$key]] = $orig_key;
									unset($keyword);
									break;
								}
							}
						}
					}
				}

			}
		}

		// Update counter.log
		$counterfile_path = '../usr/' . $config_logfile_folder . '/'
				. $config_count_file;
		@$counterfile_handle = fopen($counterfile_path, 'r+');
		if ($counterfile_handle === false) {
			// error opening counter-file
			$message_error .= L_ANALYZE_MSG_ERR_COUNTERFILE_INEXISTENT
					. '<br />';
			@$counterfile_handle = fopen($counterfile_path, 'w');
			if ($counterfile_handle === false)
				$message_error .= ' '
						. L_ANALYZE_MSG_ERR_COUNTERFILE_CREATION_FAILED
						. '<br />';
			else {
				fwrite($counterfile_handle,
						"0\n" . $_SESSION['module_hit_data']['gesamt_ip']
								. "\n" . $_SESSION['module_hit_data']['gesamt']);
				fclose($counterfile_handle);
				$message_ok .= L_ANALYZE_MSG_OK_COUNTERILE_CREATED . ' <br />';
			}
		} else {
			// skip first line (readpos)
			fgets($counterfile_handle);
			// update total hits in counter-file
			fwrite($counterfile_handle,
					$_SESSION['module_hit_data']['gesamt_ip'] . "\n"
							. $_SESSION['module_hit_data']['gesamt']);
			fclose($counterfile_handle);
		}
	}

	// Hits max
	if (isset($hits_user_tag)) {
		arsort($hits_user_tag);
		$_SESSION['module_hit_data']['max'] = reset($hits_user_tag);
		$_SESSION['module_hit_data']['max_tag'] = key($hits_user_tag);
	}

	// average User/day
	if (isset($hits_user_tag)) {
		$hits_user_anz_tage = count($hits_user_tag);
		if($hits_user_anz_tage>0) {
			$_SESSION['module_hit_data']['durchschnitt'] = $_SESSION['module_hit_data']['gesamt_ip']
					/ $hits_user_anz_tage;
		} else $_SESSION['module_hit_data']['durchschnitt'] = 0; 
	}

	// Hits per User
	@$_SESSION['module_hit_data']['proUser'] = $_SESSION['module_hit_data']['gesamt']
			/ $_SESSION['module_hit_data']['gesamt_ip'];

	// Average visiting time
	if($_SESSION['module_hit_data']['gesamt_ip']>0) {
		$_SESSION['module_hit_data']['visit_time_avg'] = round(
				$_SESSION['module_hit_data']['visit_time_total']
						/ $_SESSION['module_hit_data']['gesamt_ip'] / 60, 2);
	} else $_SESSION['module_hit_data']['visit_time_avg']=0;

	// Maximal-values and total values
	foreach ($list_modules as $modul) {
		if (isset($_SESSION['module_' . $modul . '_data'])) {
			$_SESSION['module_' . $modul . '_total'] = 0;
			$_SESSION['module_' . $modul . '_max'] = 0;
			$_SESSION['module_' . $modul . '_max_name'] = '';
			foreach ($_SESSION['module_' . $modul . '_data'] as $eintrag => $wert) {
				$_SESSION['module_' . $modul . '_total'] += $wert;
				if (!isset($_SESSION['module_' . $modul . '_max'])
						|| $_SESSION['module_' . $modul . '_max'] < $wert) {
					$_SESSION['module_' . $modul . '_max'] = $wert;
					if (!in_array($modul, $list_modules_limit))
						$_SESSION['module_' . $modul . '_max_name'] = $eintrag;
				}
			}
		}
	}

	// End of analyzing
}

// Preset-FileCache: cache results
if ($config_stat_preset_file_cache && !$all_cached && $active_preset !== false) // && !$preset_all_cached
	save_results('pfc');

function save_results($why) {
	global $handle, $time, $list_modules, $ips, $ips_dateien, $ips_online, $config_ip_block_time, $message_error, $config_stat_pfc_zlib, $config_logfile_folder;

	// Delete ip-data that is not needed anymore
	$timecontrol = floor($time['stamp'] / 60 / $config_ip_block_time);
	if (!empty($ips))
		foreach ($ips as $ip => $check_timecontrol)
			if ($check_timecontrol != $timecontrol)
				unset($ips[$ip]);
	if (!empty($ips_dateien))
		foreach ($ips_dateien as $ipfile => $check_timecontrol)
			if ($check_timecontrol != $timecontrol)
				unset($ips_dateien[$ipfile]);
	if (!empty($ips_online))
		foreach ($ips_online as $ip => $check_timecontrol)
			if ($check_timecontrol != $timecontrol)
				unset($ips_online[$ip]);

	$vars_to_save = array('pos' => ($handle ? ftell($handle) : false),
			'logdatei_name', 'hits_user_tag', 'ips', 'ips_dateien',
			'ips_online', 'log_file_number', 'last_seen', 'first_seen');
	if ($why == 'pfc') {
		$active_preset = check_which_preset();
		if ($active_preset == false)
			return false;
		$pfc_compress = (in_array('compress.zlib', stream_get_wrappers())
				&& $config_stat_pfc_zlib);
		$pfc_filename = '../usr/cache/' . $active_preset . '_'
				. md5($config_logfile_folder) . '.php'
				. ($pfc_compress ? '.gz' : '');

		$vars_to_save['save_timestamp'] = $time['stamp'];

		$vars_to_save[] = 'usr_on_stamp';
		if(isset($_SESSION['module_keyword_orig'])) $vars_to_save['_SESSION["module_keyword_orig"]'] = $_SESSION['module_keyword_orig'];
		foreach ($list_modules as $modul) {
			$vars_to_save['_SESSION["module_' . $modul . '_data"]'] = $_SESSION['module_'
					. $modul . '_data'];
			$vars_to_save['_SESSION["module_' . $modul . '_data_timestamps"]'] = $_SESSION['module_'
					. $modul . '_data_timestamps'];
		}
		$pfcache = '<?php'
				. "\n//This is a temporary file which has been automatically created by CrazyStat. You can delete it. Then CrazyStat will be slower the next time (and recreate the file).\n";
	}
	foreach ($vars_to_save as $var => $val) {
		if (is_int($var)) {
			global $$val;
			$var = $val;
			$val = $$var;
		}
		if ($why == 'goon')
			$_SESSION['goon_' . $var] = $val;
		else
			$pfcache .= "\n" . '$' . $var . '=' . var_export($val, true) . ';';
	}
	unset($vars_to_save);
	if ($why == 'pfc') {
		$pfcache .= "\n?>"; //<?php
		@$pfcache_handle = fopen(
				($pfc_compress ? 'compress.zlib://' : '') . $pfc_filename, 'w');
		if (!$pfcache_handle)
			$message_error .= $pfc_filename . ' '
					. L_ANALYZE_MSG_ERR_CACHE_SAVE_FAILED . "<br />";
		else {
			fwrite($pfcache_handle, $pfcache);
			fclose($pfcache_handle);
		}
	}
}

if ($_SESSION['debug'] == true) {
	$ende = getmicrotime();
	$zeit = $ende - $start;
	$message_ok .= 'Parsing Time: ' . $zeit . 's<br />';
	$ram_peak = memory_get_peak_usage();
	$ram = memory_get_usage();
	$message_ok .= 'RAM Init: ' . round($ram_init / 1024 / 1024, 4)
			. 'MB<br />';
	$message_ok .= 'RAM Peak: ' . round($ram_peak / 1024 / 1024, 4)
			. 'MB<br />';
	$message_ok .= 'RAM End: ' . round($ram / 1024 / 1024, 4) . 'MB<br />';
	$message_ok .= 'RAM (Peak-Init): '
			. round(($ram_peak - $ram_init) / 1024 / 1024, 4) . 'MB<br />';
}

$script_complete = true;

function script_ende() // is being called when the script stops (finishs, timeout, ...)
 {
	global $script_complete, $handle, $logdatei_name, $error_including_pfc, $pfc_filename;
	if (!$script_complete) { // Script did not finish (max_execution_time?)
		if ($error_including_pfc) {
			chdir(dirname(__FILE__));
			@unlink($pfc_filename);
			$_SESSION['error_including_pfc'] = true;
			die(
					'<p class="meldung_fehler">'
							. L_ANALYZE_MSG_ERR_CACHE_BROKEN
							. ' <a href="show_stat.php?clearcache=true&reload=true">'
							. L_SHOWSTAT_CLEAR_CACHE . '</a></p></body></html>');
		}
		@$c = fgetc($handle);
		while ($c != "\n" && $c !== false) { // skip current line
			$c = fgetc($handle);
		}
		save_results('goon'); // save the current state (variables)

		// print a message with a link to go on analyzing
		echo '<p class="meldung_fehler">';
		echo L_ANALYZE_MSG_ERR_INCOMPLETE . ' ';
		if ($_SESSION['goon_pos'] !== false) {
			echo L_ANALYZE_MSG_ERR_MEMORY_LIMIT . '<br />';
			echo L_ANALYZE_MSG_ERR_TIMEOUT . '<br />';
		} else
			echo L_ANALYZE_MSG_ERR_UNKNOWN_ERROR;
		echo '<br />' . L_ANALYZE_MSG_ERR_CURRENT_POSITION . ': Byte: '
				. $_SESSION['goon_pos'] . '  - ' . $logdatei_name . '<br />';
		echo '<a href="show_stat.php?' . SIDX . '&amp;goon=1';
		foreach ($_GET as $key => $val)
			echo '&amp;' . $key . '=' . $val;
		echo '">' . L_MSG_ERR_CONTINUE . '</a></p></body></html>';
	}
}

?>
