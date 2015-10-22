<?php
// Danish lanuage file for CrazyStat version 1.71 by Liza Overgaard (thanks!)

// password_protect.php
define('L_PASSWORD_MES_ERR_NEW_INVALID','Ny adgangskode ikke angivet eller den er ikke identisk med gentagelsen.');
define('L_PASSWORD_MES_ERR_NOT_CHANGED','Adgangskoden blev ikke skiftet!');
define('L_PASSWORD_MES_ERR_RETRY','Pr&oslash;v igen');
define('L_PASSWORD_MES_ERR_WRITING_FAILED','Adgangskodefilen er ikke skrivbar. Tjek venligst filens attributter (CHMOD eller skrivebeskyttelse).');
define('L_PASSWORD_MES_ERR_SEE_README','Velkommen! Det ser ikke ud til, at du har installeret CrazyStat endnu. Se venligst <a href="../doc/README_da.html" target="_blank">Readme</a> for at få vejledning i, hvordan CrazyStat skal installeres.');
define('L_PASSWORD_MES_OK_CHANGED','Adgangskoden er nu blevet &aelig;ndret!');
define('L_PASSWORD_MES_ERR_NOT_LOGGED_IN','Sessionen udl&oslash;b eller du er ikke logget ind.');
define('L_PASSWORD_MES_ERR_RELOGIN','Log ind igen');

define('L_PASSWORD_PLEASE_LOGIN','Log venligst ind');
define('L_PASSWORD_USERNAME','Brugernavn');
define('L_PASSWORD_PASSWORD','Adgangskode');
define('L_PASSWORD_DO_NOT_COUNT','Medt&aelig;l ikke hits fra denne computer');
define('L_PASSWORD_PLEASE_WAIT','Vent venligst...');
define('L_PASSWORD_MES_ERR_WRONG_DATA','Brugernavnet eller adgangskoden var forkert. Adgang n&aelig;gtet.');
define('L_PASSWORD_MES_ERR_WRONG_DATA_SEE_FAQ','Se <a href="../doc/faq_en.html#faq5">FAQ</a> hvis du har glemt din adgangskode og har brug for at nulstille den.');
define('L_PASSWORD_MES_OK_THANK_YOU','Tak fordi du bruger CrazyStat!');
define('L_PASSWORD_MES_SUPPORT_CRAZYSTAT','<a href="http://en.christosoft.de/Support">St&oslash;t venligst CrazyStat!</a>');
define('L_PASSWORD_NO_JAVASCRIPT','OBS! Adgangskoden skal overf&oslash;res uden kryptering, fordi din browser ikke underst&oslash;tter JavaScript!');
define('L_PASSWORD_CHANGE_PASSWORD','Skift adgangskode');
define('L_PASSWORD_OLD_PASSWORD','Gammel adgangskode');
define('L_PASSWORD_NEW_PASSWORD','Ny adgangskode');
define('L_PASSWORD_REPEAT_PASSWORD','Gentag adgangskode');
define('L_PASSWORD_CHANGE_AND_LOGIN','Skift og log ind');
define('L_PASSWORD_MSG_ERR_NOMD5','OBS! Adgangskoden skal overf&oslash;res uden kryptering, fordi $config_stat_password_md5 er sat til false.<br />Det anbefales at s&aelig;tte den til true, s&aring; adgangskoden bliver gemt krypteret (se FAQ).');
define('L_PASSWORD_MSG_ERR_NO_MD5JS','OBS! Adgangskoden skal overf&oslash;res uden kryptering, fordi filen src/extensions/md5.js mangler. Det anbefales ikke at slette denne udvidelse. Du kan finde den manglende fil under <a href="http://en.christosoft.de/_download/crazystat_current_version" target="_blank">CrazyStat download-file</a>.');

// index.php (some used by about.php as well)
define('L_INDEX_WELCOME','Velkommen!');
define('L_INDEX_THIS_IS_CRAZYSTAT','Dette er CrazyStat - et brugervenligt, omfattende og gratis statistik-script med valgfri t&aelig;ller.');
define('L_INDEX_INFORMATION','Information og opdateringer findes her:');
define('L_INDEX_INSTALLED_VERSION','Installeret version:');
define('L_INDEX_CURRENT_VERSION','Aktuel version:');
define('L_INDEX_NEWS','Nyheder p&aring;');

// about.php
define('L_ABOUT_PLEASE_SEE','Se venligst');
define('L_ABOUT_README','Readme');
define('L_ABOUT_FAQ','FAQ');
define('L_ABOUT_FOR_HELP','hvis du har brug for hj&aelig;lp.');

// password_protect.php and index.php
define('L_LOGIN_MENU_HOME','Hjem');
define('L_LOGIN_MENU_LOGIN','Log ind');
define('L_LOGIN_MENU_CHANGE_PASSWORD','Skift adgangskode');

// calendar.php
define('L_CALENDAR_TITLE','Defin&eacute;r tidsrum');
define('L_CALENDAR_MSG_ERR_YEAR_INVALID','&Aring;rstallet er ugyldigt, brug venligst kun tal.');
define('L_CALENDAR_MSG_ERR_MONTH_ONLY','Fejl: V&aelig;lg venligst <i>b&aring;de</i> m&aring;ned <i>og</i> &aring;r.');
define('L_CALENDAR_MSG_ERR_NO_JS','Da du anvender en browser, som ikke underst&oslash;tter JavaScript eller da du har sl&aring;et JavaScript fra, skal du klikke for at forts&aelig;tte:');
define('L_CALENDAR_LIMIT_YEAR','Begr&aelig;ns til &aring;r');
define('L_CALENDAR_LIMIT_MONTH','Begr&aelig;ns til m&aring;ned');
define('L_CALENDAR_LIMIT_PERIOD','Begr&aelig;ns til tidsrum');
define('L_CALENDAR_MONTH_ABR','Jan Feb Mar Apr Maj Jun Jul Aug Sep Okt Nov Dec');
define('L_CALENDAR_MONTH_NAMES','Januar Februar Marts April Maj Juni Juli August September Oktober November December');
define('L_CALENDAR_WEEKDAYS_ABR','S M T O T F L');
define('L_CALENDAR_WEEKDAYS','S&oslash;ndag Mandag Tirsdag Onsdag Torsdag Fredag L&oslash;rdag');
define('L_CALENDAR_TODAY','I dag');
define('L_CALENDAR_WEEK_START_DAY',0);
define('L_CALENDAR_START','Start');
define('L_CALENDAR_END','Slut');
define('L_CALENDAR_RELATIVE','Relativ');
define('L_CALENDAR_ABSOLUTE','Absolut');
define('L_CALENDAR_RELATIVE_PRESET','Forudindstillet');
define('L_CALENDAR_RELATIVE_CUSTOM','Brugerdefineret');
define('L_CALENDAR_RELATIVE_THIS_WEEK','Denne uge');
define('L_CALENDAR_RELATIVE_THIS_MONTH','Denne m&aring;ned');
define('L_CALENDAR_RELATIVE_THIS_YEAR','Dette &aring;r');
define('L_CALENDAR_RELATIVE_CHECK','Tjek');
define('L_CALENDAR_RELATIVE_HELP','Se venligst hj&aelig;lpefilen ang&aring;ende, hvordan dette fungerer.');

// create_counter_image.php
define('L_COUNTER_FILE_NOT_FOUND','T&aelig;ller-typografien blev ikke fundet');
define('L_COUNTER_GIF_NOT_SUPPORTED','gif-filer underst&oslash;ttes ikke');
define('L_COUNTER_TYPE_NOT_SUPPORTED','filtypen underst&oslash;ttes ikke');

// nojs.php
define('L_NOJS_TITLE','Ingen JavaScript');
define('L_NOJS_TEXT',
' <p>Desv&aelig;rre underst&oslash;tter din browser ikke JavaScript eller ogs&aring; har du sl&aring;et det fra. Det anbefales at bruge en JavaScript-underst&oslash;ttet browser, f.eks. <a href="http://www.mozilla.com/">Firefox</a>, eller at aktivere JavaScript i din browser, s&aring;fremt du har sl&aring;et det fra.</p>
  <p>I nogle tilf&aelig;lde kommer denne meddelelse frem, hvis der var en fejl i et JavaScript. Hvis din browser underst&oslash;tter JavaScript og det er sl&aring;et til, s&aring; g&aring; venligst tilbage og pr&oslash;v igen.</p>
  <p>
  Denne besked kan ogs&aring; dukke op, hvis et JavaScript afvikles, inden hjemmesiden er blevet l&aelig;st helt ind.<br />
  I s&aring;danne tilf&aelig;lde bedes du g&aring; tilbage og vente til hjemmesiden er helt l&aelig;st ind, f&oslash;r du igen klikker p&aring; det ikon, som du netop klikkede p&aring;.
  </p>');
  
// stat.php
define('L_STAT_MSG_ERR_COUNTER_FILE','korrupt?');
define('L_STAT_MSG_ERR_READ_ERROR','L&aelig;sefejl (attributter?)');
define('L_STAT_MSG_ERR_WRITE_ERROR','Skrivefejl (attributter?)');

// analyze.php
define('L_ANALYZE_MSG_OK_CACHE_EXISTED','Der l&aring; en cache-fil for loggene i den netop oprettede mappe. Denne cache-fil blev slettet.');
define('L_ANALYZE_MSG_ERR_LOGFOLDER_INEXISTENT','Den mappe som logfilerne skal gemmes i findes ikke');
define('L_ANALYZE_MSG_ERR_CREATE_FOLDER','Opret mappe');
define('L_ANALYZE_MSG_ERR_FILE_CREATION_FAILED','Filen kunne ikke oprettes.');
define('L_ANALYZE_MSG_ERR_FILE_NOT_FOUND','Log-filen blev ikke fundet. Pr&oslash;v venligst at t&oslash;mme cachen.');
define('L_ANAYLZE_MSG_ERR_CHECK_RIGHTS','Tjek venligst "usr/config.php" og attributterne for den mappe, som logfilerne skal gemmes i.');
define('L_ANAYLZE_MSG_ERR_CACHE_NOT_DELETED','En cache-fil kunne ikke slettes. Tjek venligst attributter.');
define('L_ANALYZE_UNKNOWN_FILE','Ukendt');
define('L_ANALYZE_UNKNOWN_SYSTEM','Andre');
define('L_ANALYZE_UNKNOWN_BROWSER','Andre');
define('L_ANALYZE_UNKNOWN_RESOLUTION','Ingen JavaScript');
define('L_ANALYZE_UNSAVED','Ikke gemt');
define('L_ANALYZE_MSG_ERR_COUNTERFILE_INEXISTENT','T&aelig;ller-fil ikke l&aelig;sbar. Fors&oslash;ger at oprette en ny.');
define('L_ANALYZE_MSG_ERR_COUNTERFILE_CREATION_FAILED','Oprettelse mislykkedes (attributter?). Ingen data gik tabt, men CrazyStat fungerer ikke optimalt. Se FAQ.');
define('L_ANALYZE_MSG_OK_COUNTERILE_CREATED','T&aelig;ller-fil oprettet. Dette er ikke et problem, da ingen data er g&aring;et tabt.');
define('L_ANALYZE_MSG_ERR_CACHE_SAVE_FAILED','var ikke skrivbar. Tjek venligst attributterne (CHMOD - se <a href="../doc/README_en.html" target="_blank">README_en.html</a>).');
define('L_ANALYZE_MSG_ERR_INCOMPLETE','Statistikken kunne ikke genereres til fulde.');
define('L_ANALYZE_MSG_ERR_TIMEOUT','Dette skyldes sandsynligvis, at den maksimale k&oslash;rselstid for scriptet blev overskredet ("Fatal fejl: Maksimal k&oslash;rselstid for ... overskredet med ... sekunder"). Dette skyldes en begr&aelig;nsning ved din server. I s&aring;danne tilf&aelig;lde kan CrazyStat godt forts&aelig;tte med at generere statistik. M&aring;ske er du dog n&oslash;dt til at klikke \'Forts&aelig;t\' mere end &eacute;n gang.');
define('L_ANALYZE_MSG_ERR_MEMORY_LIMIT','Dette skyldes sandsynligvis, at hukommelsesgr&aelig;nsen blev overskredet ("Tilladt hukommelsesforbrug for ... overskredet"). I s&aring;danne tilf&aelig;lde skal du pr&oslash;ve at for&oslash;ge <a href="../doc/config_settings_en.html#config_stat_memory_limit">$config_stat_memory_limit</a>.');
define('L_ANALYZE_MSG_ERR_UNKNOWN_ERROR','Der skete h&oslash;jst sandsynligt en fejl (nogen fejlmeddelelser ovenfor?). M&aring;ske (men usandsynligt) blev den maksimale k&oslash;rselstid eller hukommelsesgr&aelig;nse overskredet. CrazyStat kan fors&oslash;ge at forts&aelig;tte med at generere statistikken.');
define('L_ANALYZE_MSG_ERR_CURRENT_POSITION','Aktuel position');
define('L_ANALYZE_MSG_ERR_GUEST_CLEAR_CACHE','Du har ikke tilladelse til at t&oslash;mme cachen. Se <a href="../doc/config_settings_en.html#config_stat_guest_cache_delete" target="_blank">$config_stat_guest_cache_delete</a> og <a href="../doc/config_settings_en.html#config_stat_user_cache_delete" target="_blank">$config_stat_user_cache_delete</a>.');
define('L_ANALYZE_MSG_ERR_CACHE_BROKEN','Cache-filen ser ud til at v&aelig;re fejlbeh&aelig;ftet. Foretag venligst f&oslash;lgende for at rette problemet:');

// show_log.php
define('L_SHOWLOG_TITLE','Log');
define('L_SHOWLOG_ALLLOGS','Alle logfiler');
define('L_SHOWLOG_MSG_ERR_INVALID_ID','Der findes ingen logfil med dette id.');
define('L_SHOWLOG_MSG_ERR_LOGFILE_NOTFOUND','Den logfil, som skal vises, findes ikke. Tjek venligst om f&oslash;lgende fil eksisterer:');
define('L_SHOWLOG_END_OF_LOGFILE','Slut p&aring; logfil');
define('L_SHOWLOG_NEXT_LOGFILE','N&aelig;ste logfil');
define('L_SHOWLOG_PREV_LOGFILE','Forrige logfil');
define('L_SHOWLOG_PREV_PAGE','Forrige side');
define('L_SHOWLOG_NEXT_PAGE','N&aelig;ste side');
define('L_SHOWLOG_PAGE','Side');
define('L_SHOWLOG_FORWARD','Frem');
define('L_SHOWLOG_BACKWARD','Tilbage');
define('L_SHOWLOG_TEXT','Tekst');
define('L_SHOWLOG_TABLE','Tabel');
define('L_SHOWLOG_FILTERED','filtreret');
define('L_SHOWLOG_ROWS_FOUND','r&aelig;kke(r) fundet');

// logs.php
define('L_LOGS_SEARCH_CONTAINS','indeholder');
define('L_LOGS_SEARCH_CONTAINS_NOT','indeholder ikke');
define('L_LOGS_SEARCH_UNEQUAL','forskellig fra');
define('L_LOGS_VALUE','V&aelig;rdi');
define('L_LOGS_TITLE','Log-filer');
define('L_LOGS_BACKUP','Backup');
define('L_LOGS_VIEW','Vis');
define('L_LOGS_FILTER_TITLE','S&oslash;g i log (filter)');
define('L_LOGS_ADD_CONDITION','Tilf&oslash;j betingelse');
define('L_LOGS_SEARCH_SUBMIT','S&oslash;g');
define('L_LOGS_SEARCH_WAIT','S&oslash;ger i loggen... Vent venligst.');
define('L_LOGS_MSG_ERR_NOFILE','Ingen fil valgt.');
define('L_LOGS_MSG_CONFIM_DELETE','Vil du virkelig slette f&oslash;lgende filer permanent?');
define('L_LOGS_SEARCH','S&oslash;g');
define('L_LOGS_SELECT_ALL','V&aelig;lg alle');
define('L_LOGS_SELECT_NONE','Frav&aelig;lg alle');
define('L_LOGS_SELECTED','valgt');
define('L_LOGS_MSG_ERR_GUEST_DOWNLOAD','Du har ikke tilladelse til at downloade logfiler. Se <a href="../doc/config_settings_en.html#config_stat_guest_log_download" target="_blank">$config_stat_guest_log_download</a> og <a href="../doc/config_settings_en.html#config_stat_user_log_download" target="_blank">$config_stat_user_log_download</a>.');
define('L_LOGS_MSG_ERR_GUEST_DELETE','Du har ikke tilladelse til at slette logfiler. Se <a href="../doc/config_settings_en.html#config_stat_guest_log_delete" target="_blank">$config_stat_guest_log_delete</a> og <a href="../doc/config_settings_en.html#config_stat_user_log_delete" target="_blank">$config_stat_user_log_delete</a>.');

// show_stat.php
define('L_SHOWSTAT_PRESETS','Forudindstillinger');
define('L_SHOWSTAT_PRESETS_DEFAULT','Standard');
define('L_SHOWSTAT_PRESETS_DEFAULT_OLD','Gammel standard');
define('L_SHOWSTAT_PRESETS_IP1','Med blokererede IPere');
define('L_SHOWSTAT_PRESETS_IP0','Uden blokererede IPere');
define('L_SHOWSTAT_PRESETS_PIE_CHARTS','Cirkeldiagram');
define('L_SHOWSTAT_PRESETS_BAR_CHARTS','S&oslash;jlediagram');
define('L_SHOWSTAT_PRESETS_THIS_YEAR','Dette &aring;r');
define('L_SHOWSTAT_PRESETS_THIS_MONTH','Denne m&aring;ned');
define('L_SHOWSTAT_PRESETS_ALL','Vis alle (all)');
define('L_SHOWSTAT_PRESETS_LIMIT','Vis kun de f&oslash;rste (limit)');
define('L_SHOWSTAT_PRESETS_SCROLL1','Vis modul-scrollbarer');
define('L_SHOWSTAT_PRESETS_SCROLL0','Vis ikke modul-scrollbarer');
define('L_SHOWSTAT_PRESETS_TOTAL_TIME','Total tid');
define('L_SHOWSTAT_PRESETS_CURRENT_PERIOD','Aktuelt tidsrum');
define('L_SHOWSTAT_PRESETS_MANAGE','H&aring;ndtering af forudindstillinger');
define('L_SHOWSTAT_CLEAR_CACHE','T&oslash;m cache');
define('L_SHOWSTAT_REFRESH_ALL','Opdat&eacute;r alle');
define('L_SHOWSTAT_LOGS','Logs');
define('L_SHOWSTAT_MSG_OK_WAIT','Den &oslash;nskede handling tager nogle &oslash;jeblikke. Sandsynligvis skal statistikken genereres igen, hvilken godt kan tage lidt tid.');

// preset-specific
define('L_PRESET_DEFAULT_YEAR','seneste 12 m&aring;neder');
define('L_PRESET_DEFAULT_MONTH','en m&aring;nedsperiode');
define('L_PRESET_DEFAULT_WEEK','seneste 7 dage');
define('L_PRESET_DEFAULT_DAY','seneste 24 timer');

// module_out.php
define('L_MODULEOUT_HITS_PI','Sete sider i alt');
define('L_MODULEOUT_HITS_VISITS','Bes&oslash;g i alt');
define('L_MODULEOUT_HITS_THIS_MONTH','Bes&oslash;g i denne m&aring;ned');
define('L_MODULEOUT_HITS_LAST_MONTH','Bes&oslash;g i forrige m&aring;ned');
define('L_MODULEOUT_HITS_USER_ONLINE','Brugere online');
define('L_MODULEOUT_HITS_MAX_DAY','Dato for flest bes&oslash;g');
define('L_MODULEOUT_HITS_AV_PER_DAY','Bes&oslash;gssnit pr. dag');
define('L_MODULEOUT_HITS_HITS_PER_USER','Sete sider i snit pr. bes&oslash;gende');
define('L_MODULEOUT_HITS_VISIT_TIME_AVG','Gennemsnitlig tid brugt');
define('L_MODULEOUT_HITS_VISIT_TIME_TOTAL','Tid brugt i alt');
define('L_MODULEOUT_IP0','Sidevisninger (uden blokerede IPere)');
define('L_MODULEOUT_IP1','Bes&oslash;g (med blokerede IPere)');
define('L_MODULEOUT_NO_DATA','ingen data');
define('L_MODULEOUT_TOTAL_TIME','tid i alt');
define('L_MODULEOUT_PIE_CHART','Cirkeldiagram');
define('L_MODULEOUT_BAR_CHART','S&oslash;jlediagram');
define('L_MODULEOUT_SORT_BY','Klik for sortering');
define('L_MODULEOUT_SORT_BY_NUM','Klik for sortering p&aring; antal');
define('L_MODULEOUT_SORT_BY_PER','Klik for sortering p&aring; procent');
define('L_MODULEOUT_SORT_BY_RATIO','Klik for sortering p&aring; fordeling');
define('L_MODULEOUT_NUM','antal');
define('L_MODULEOUT_NUM_ABR','antal');
define('L_MODULEOUT_PER','pct.');
define('L_MODULEOUT_TOTAL','i alt');
define('L_MODULEOUT_RATIO','fordeling');
define('L_MODULEOUT_DOMAINS','Dom&aelig;ner');
define('L_MODULEOUT_PAGES','Sider');
define('L_MODULEOUT_COLORS','farver');
define('L_MODULEOUT_CONSOLE_REFERSH','Opdat&eacute;r');
define('L_MODULEOUT_CONSOLE_REFERSH_ALT','Opdat&eacute;r');
define('L_MODULEOUT_CONSOLE_PERIOD','Tidsrum');
define('L_MODULEOUT_CONSOLE_PERIOD_DEFINE','Defin&eacute;r tidsrum');
define('L_MODULEOUT_CLICK_IP1','Klik for bes&oslash;gende (med blokerede IPere).');
define('L_MODULEOUT_CLICK_IP0','Klik for sidevisninger (uden blokerede IPere).');
define('L_MODULEOUT_CONSOLE_ALL','Vis alle');
define('L_MODULEOUT_CONSOLE_ALL_ABR','Alle');
define('L_MODULEOUT_CONSOLE_SHOW_ONLY','Vis kun');
define('L_MODULEOUT_CONSOLE_TREE_ABR','Vis tr&aelig;');
define('L_MODULEOUT_CONSOLE_TREE','Vis tr&aelig; og sorter henvisningerne p&aring; v&aelig;rt');
define('L_MODULEOUT_CONSOLE_TREE_OFF','Vis ikke tr&aelig;, list alle henvisninger');
define('L_MODULEOUT_CONSOLE_TREE_OFF_ABR','Vis ikke tr&aelig;');
define('L_MODULEOUT_CONSOLE_TREE_OTHER','Skift tr&aelig;-udvidelse');
define('L_MODULEOUT_CONSOLE_TREE_COLLAPSE','Klap tr&aelig; sammen');
define('L_MODULEOUT_CONSOLE_TREE_EXPAND','&Aring;ben tr&aelig;');
define('L_MODULEOUT_PRETTYINT_SUFFIX','mio. mia. billioner billiarder trillioner trilliarder');

// preset_editor.php
define('L_PRESETEDITOR_MANAGE_PRESETS','H&aring;ndt&eacute;r forudinstillinger');
define('L_PRESETEDITOR_ID','ID');
define('L_PRESETEDITOR_CACHE_SIZE','Cache-st&oslash;rrelse');
define('L_PRESETEDITOR_SAVE_PRESET','Gem forudinstilling');
define('L_PRESETEDITOR_SAVE_PRESET_TEXT','Dette vil gemme de aktuelle indstillinger (dem alle) som en forudinstilling til fremtidig brug. Alle indstillinger bliver automatisk opsporet.');
define('L_PRESETEDITOR_SAVE_PRESET_MSG_ABS','ADVARSEL: De aktuelle indstillinger indeholder absolutte tidsrum for f&oslash;lgende modul(er)');
define('L_PRESETEDITOR_SAVE_PRESET_MSG_ABS2','Det giver ikke rigtigt nogen mening at gemme forudinstillinger med absolutte tidsrum!');
define('L_PRESETEDITOR_SAVE_PRESET_DUPLICATE','Denne forudinstilling er n&oslash;jagtigt den samme som i:');
define('L_PRESETEDITOR_SAVE_PRESET_DUPLICATE_CANNOT_BE_SAVED','Du kan kun gemme forudinstillinger, som er forskellige fra de eksisterende.');
define('L_PRESETEDITOR_CACHE','Gem denne forudinstilling.');
define('L_PRESETEDITOR_CACHE_NOT','Gem ikke denne forudinstilling.');
define('L_PRESETEDITOR_CACHE_UNCACHEABLE','Denne forudinstilling kan ikke gemmes.');
define('L_PRESETEDITOR_MSG_ERR_GUEST','Du har ikke tilladelse til at h&aring;ndtere forudinstillinger. Se <a href="../doc/config_settings_en.html#config_stat_guest_preset_manage" target="_blank">$config_stat_guest_preset_manage</a> og <a href="../doc/config_settings_en.html#config_stat_user_preset_manage" target="_blank">$config_stat_user_preset_manage</a>.');
define('L_PRESETEDITOR_MSG_PRESET_DELETE','Vil du virkelig slette denne forudinstilling?');
define('L_PRESETEDITOR_MSG_PRESET_DELETED','Forudinstilling slettet');
define('L_PRESETEDITOR_MSG_PRESET_SAVED','Forudinstilling gemt');

// anonymous_redirect.php
define('L_ANONYMOUS_REDIRECT','Du bliver omstillet anonymt til:');

// menu-texts
define('L_MENU_WEBSITE','Bes&oslash;g Website');
define('L_MENU_ABOUT','Om CrazyStat');
define('L_MENU_STATISTIC','Vis statistik');

// modules
define('L_MODULES_HIT_P','Hits');
define('L_MODULES_HIT_S','Hit');
define('L_MODULES_WEEKDAY_P','ugedage');
define('L_MODULES_WEEKDAY_S','ugedag');
define('L_MODULES_MONTH_P','m&aring;neder');
define('L_MODULES_MONTH_S','m&aring;ned');
define('L_MODULES_DAY_P','dage');
define('L_MODULES_DAY_S','dag');
define('L_MODULES_HOUR_P','timer');
define('L_MODULES_HOUR_S','time');
define('L_MODULES_BROWSER_P','browsere');
define('L_MODULES_BROWSER_S','browser');
define('L_MODULES_FILE_P','sider');
define('L_MODULES_FILE_S','side');
define('L_MODULES_RESOLUTION_P','opl&oslash;sninger');
define('L_MODULES_RESOLUTION_S','opl&oslash;sning');
define('L_MODULES_COLORDEPTH_P','farver');
define('L_MODULES_COLORDEPTH_S','farve');
define('L_MODULES_SYSTEM_P','operativsystemer');
define('L_MODULES_SYSTEM_S','operativsystem');
define('L_MODULES_REFERER_P','henvisninger fra');
define('L_MODULES_REFERER_S','henvist fra');
define('L_MODULES_KEYWORD_P','s&oslash;geord');
define('L_MODULES_KEYWORD_S','s&oslash;geord');

// general error messages
define('L_MSG_ERR_INCLUDE_ONLY','Denne fil kan ikke &aring;bnes direkte.');
define('L_MSG_ERR_NO_MODULE','Intet modul specificeret eller det findes ikke.');
define('L_MSG_ERR','Fejl');
define('L_MSG_ERR_CONTINUE','Forts&aelig;t');

// general texts
define('L_LOGOUT','Log ud');
define('L_CLOSE','Luk');
define('L_OK','OK');
define('L_NUMBER','Antal');
define('L_TIME','Kl.');
define('L_DATE','Dato');
define('L_IP','IP');
define('L_USERAGENT','Brugeragent');
define('L_BIT',' Bit');
define('L_DATE_FORMAT','m-d-Y');
define('L_TIME_FORMAT','H:i:s');
define('L_GO','OK');
define('L_PLEASE_WAIT','Vent venligst ...');
define('L_HITS','Hits');
define('L_VISITS','Bes&oslash;g');
define('L_PAGEIMPRESSIONS','Sidevisninger');
define('L_BACK','Tilbage');
define('L_CANCEL','Annul&eacute;r');
define('L_DELETE','Slet');
define('L_REFRESH','Opdat&eacute;r');
define('L_AND','og');
define('L_LANGUAGE','Sprog');
define('L_NAME','Navn');
define('L_DECIMAL_SEPARATOR',',');
define('L_THOUSANDS_SEPARATOR','.');
define('L_AVG_SYMBOL','Snit');

define('L_MINUTES','minutter');
define('L_MINUTES_ABR','min');
define('L_SECONDS','sekunder');
define('L_SECONDS_ABR','s');
define('L_HOURS','hours');
define('L_HOURS_ABR','h');
define('L_DAYS','dage');
define('L_days_ABR','d');

?>
