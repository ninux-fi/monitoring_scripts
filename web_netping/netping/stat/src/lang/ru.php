<?php
// Russian language file for CrazyStat 1.71 by Vladimir (thanks!)

// Password_protect.php
define('L_PASSWORD_MES_ERR_NEW_INVALID', 'Новый пароль отсутствует или не схож с повторенным.');
define('L_PASSWORD_MES_ERR_NOT_CHANGED', 'Пароль не изменен! ');
define('L_PASSWORD_MES_ERR_RETRY',' Повторить ');
define('L_PASSWORD_MES_ERR_WRITING_FAILED', 'Файл с паролями не может быть открыт для редактирования. Пожалуйста, проверьте разрешения (CHMOD или блокировка записи) на файле ');
define('L_PASSWORD_MES_ERR_SEE_README','Добро пожаловать! Похоже Вы еще не  установили(не доконца) CrazyStat. Пожалуйста, посмтрите <a href="../doc/install_ru.txt" target="_blank">ХЕЛП(на английском)</a> чтобы установить CrazyStat.');
define('L_PASSWORD_MES_OK_CHANGED', 'Ваш пароль был изменен! ');
define('L_PASSWORD_MES_ERR_NOT_LOGGED_IN', 'Время сессии истекло или вы не вошли корректно.');
define('L_PASSWORD_MES_ERR_RELOGIN',' Повторите Вход');

define('L_PASSWORD_PLEASE_LOGIN', 'Пожалуйста Войдите');
define('L_PASSWORD_USERNAME', 'Имя пользователя');
define('L_PASSWORD_PASSWORD', 'пароль');
define('L_PASSWORD_DO_NOT_COUNT', 'Не вести подсчет с этого компьютера!');
define('L_PASSWORD_PLEASE_WAIT', 'Пожалуйста, подождите');
define('L_PASSWORD_MES_ERR_WRONG_DATA', 'Имя пользователя и/или пароль введены  не верно! Отказано в доступе .');
define('L_PASSWORD_MES_ERR_WRONG_DATA_SEE_FAQ','Посмотрите <a href="../doc/faq_en.html#faq5">Популярные вопроссы и ответы(на английском)</a> если вы забыли свой пароль, ивам его надо изменить.');
define('L_PASSWORD_MES_OK_THANK_YOU', 'Спасибо за использование CrazyStat! ');
define('L_PASSWORD_MES_SUPPORT_CRAZYSTAT',' Поддержи <a href="http://en.christosoft.de/Support"> CrazyStat </a>');
define('L_PASSWORD_NO_JAVASCRIPT', 'Внимание: пароль будет передан не безопасным, потому что ваш браузер не поддерживает JavaScript ');
define('L_PASSWORD_CHANGE_PASSWORD', 'Изменить пароль');
define('L_PASSWORD_OLD_PASSWORD',' Старый пароль');
define('L_PASSWORD_NEW_PASSWORD', 'Новый пароль');
define('L_PASSWORD_REPEAT_PASSWORD', 'Новый пароль еще раз');
define('L_PASSWORD_CHANGE_AND_LOGIN',' изменить и войти ');
define('L_PASSWORD_MSG_ERR_NOMD5', 'Внимание:. новый пароль будет отправлен не безопасным, потому что $ config_stat_password_md5 установлен на false <br /> Чтобы надежно хранить пароль, необходимо изменить его на true (см. наш FAQ). . ');
define('L_PASSWORD_MSG_ERR_NO_MD5JS', 'Внимание: новый пароль будет отправлен незащищенным, потому что файл отсутствует src/extensions/md5.js Мы советуем вам не удалять это расширение! Соответствующие фаилы  можно найти на оригинальной сборке <a href="http://en.christosoft.de/_download/crazystat_current_version" target="_blank"> CrazyStat </a>.');

// Index.php (некоторые злоупотребляют путем about.php а)
define('L_INDEX_WELCOME', 'Добро пожаловать!');
define('L_INDEX_THIS_IS_CRAZYSTAT','Удобный, быстрый и с большими возможностями,  скрипт CrazyStat, бесплатный!!!!');
define('L_INDEX_INFORMATION',' Информация по поводу обновления, посетите: ');
define('L_INDEX_INSTALLED_VERSION',' Установленная версия');
define('L_INDEX_CURRENT_VERSION',' Последняя версия');
define('L_INDEX_NEWS',' Последние новости');

// About.php
define('L_ABOUT_PLEASE_SEE', 'Пожалуйста посмотрете');
define('L_ABOUT_README', 'ПрочитАЙкА');
define('L_ABOUT_FAQ', 'Вопр. и Отв.');
define('L_ABOUT_FOR_HELP',' за помощью .');

// Index.php и password_protect.php
define('L_LOGIN_MENU_HOME', 'Главная');
define('L_LOGIN_MENU_LOGIN', 'Вход');
define('L_LOGIN_MENU_CHANGE_PASSWORD', 'Изменить пароль');

// Calendar.php
define('L_CALENDAR_TITLE',' Определить период ');
define('L_CALENDAR_MSG_ERR_YEAR_INVALID', 'Неверно введены поле год, пожалуйста, убедитесь, что вы используете числа .');
define('L_CALENDAR_MSG_ERR_MONTH_ONLY', 'Ошибка: Пожалуйста, выберите месяц <i> и </i> год .');
define('L_CALENDAR_MSG_ERR_NO_JS', 'Если ваш броузер не поддерживает JavaScript или вы выключите эту опцию, вам нужно нажать, чтобы продолжить ');
define('L_CALENDAR_LIMIT_YEAR',' лимита в год');
define('L_CALENDAR_LIMIT_MONTH',' Предел в месяц ');
define('L_CALENDAR_LIMIT_PERIOD',' Предельная за период ');
define('L_CALENDAR_MONTH_ABR',' Янв Фев Март Апр Май Июнь Июль Авг Сент Окт Нояб Дек');
define('L_CALENDAR_MONTH_NAMES',' Январь Февраль Март Апрель Май Июнь Июль Август Сентябрь Октябрь Ноябрь Декабрь');
define('L_CALENDAR_WEEKDAYS_ABR', 'Воскр Пон Вт Ср Чет Пят Субб');
define('L_CALENDAR_WEEKDAYS','Воскресенье Понедельник Вторник Среда Четверг Пятница Суббота');
define('L_CALENDAR_TODAY', 'сегодня');
define('L_CALENDAR_WEEK_START_DAY', '0');
define('L_CALENDAR_START', 'Начало');
define('L_CALENDAR_END', 'Конец');
define('L_CALENDAR_RELATIVE',' Относ.');
define('L_CALENDAR_ABSOLUTE',' Абс.');
define('L_CALENDAR_RELATIVE_PRESET',' Быстрый набор ');
define('L_CALENDAR_RELATIVE_CUSTOM', 'Выборочно');
define('L_CALENDAR_RELATIVE_THIS_WEEK', 'На этой неделе');
define('L_CALENDAR_RELATIVE_THIS_MONTH', 'В этом месяце');
define('L_CALENDAR_RELATIVE_THIS_YEAR', 'В этом году');
define('L_CALENDAR_RELATIVE_CHECK', 'Проверить');
define('L_CALENDAR_RELATIVE_HELP', 'Детально описано в помощи.');

// Create_counter_image.php
define('L_COUNTER_FILE_NOT_FOUND',' Дизайн счетчика не найден(*.png) ');
define('L_COUNTER_GIF_NOT_SUPPORTED',' GiF файлы не поддерживаются ');
define('L_COUNTER_TYPE_NOT_SUPPORTED',' Тип файла не поддерживается ');

// Nojs.php
define('L_NOJS_TITLE', 'позволяют пропавших без вести ');
define('L_NOJS_TEXT','К сожалению, Ваш браузер не поддерживает <b>JavaScript</b> или вы отключили эту опцию. Мы рекомендуем использовать браузер, который поддерживает JavaScript, например, <a href="http://www.mozilla.com/"> Firefox </a> или эта функция отключена, когда вы её сами выключили.');

// Stat.php
define('L_STAT_MSG_ERR_COUNTER_FILE',' поврежден или RC?');
define('L_STAT_MSG_ERR_READ_ERROR',' Ошибка чтения(возможно права ?)');
define('L_STAT_MSG_ERR_WRITE_ERROR', 'Ошибка записи (возможно права ?)');

// Analyze.php
define('L_ANALYZE_MSG_OK_CACHE_EXISTED',' В новой папке был фаил кэш для логов. он был удален.');
define('L_ANALYZE_MSG_ERR_LOGFOLDER_INEXISTENT',' Папка, в которой должен быть лог фаил не существует');
define('L_ANALYZE_MSG_ERR_CREATE_FOLDER',' Создать папку ');
define('L_ANALYZE_MSG_ERR_FILE_CREATION_FAILED', 'Файл не может быть создан ');
define('L_ANALYZE_MSG_ERR_FILE_NOT_FOUND',' лог-файл не найден. Мы рекомендуем очистить кэш .');
define('L_ANAYLZE_MSG_ERR_CHECK_RIGHTS', 'Пожалуйста, проверьте файл "USR/config.php" убедитесь, что папка, в которой будет храниться лог-файл, имеет необходимые права.');
define('L_ANAYLZE_MSG_ERR_CACHE_NOT_DELETED', 'кэш-файл неможет быть удален. Посмотрите, пожалуйста, права.');
define('L_ANALYZE_UNKNOWN_FILE', 'неизвестный');
define('L_ANALYZE_UNKNOWN_SYSTEM', 'Прочие');
define('L_ANALYZE_UNKNOWN_BROWSER', 'Прочие');
define('L_ANALYZE_UNKNOWN_RESOLUTION',' нет JavaScript ');
define('L_ANALYZE_UNSAVED', 'Не сохранен');
define('L_ANALYZE_MSG_ERR_COUNTERFILE_INEXISTENT',' Невозможно прочитать файл подсчета. Пытаемся сосздать новый.');
define('L_ANALYZE_MSG_ERR_COUNTERFILE_CREATION_FAILED', 'Невозможно саоздать фаил (права?). Данные не потеряны, но CrazyStat работает не нормально. смотрите Вопр. и Отв..');
define('L_ANALYZE_MSG_OK_COUNTERILE_CREATED',' Файл счетчика успешно создан, все данные успешно записаны.');
define('L_ANALYZE_MSG_ERR_CACHE_SAVE_FAILED', ' неможет быть записан. Проверьте разрешения(права) (CHMOD -. см. <a href="../doc/README_en.html" README_en.html target="_blank">Вопр и Отв на английском</a>) . ');
define('L_ANALYZE_MSG_ERR_INCOMPLETE','Статистика неможет быть полностью сгенерирована.');
define('L_ANALYZE_MSG_ERR_TIMEOUT','Возможно время выполнения скрипта - истекло!? ("Fatal error: Maximum execution time of ... seconds exceeded"). Это настройки вашего сервера. В этом случае, CrazyStat сможет и дальше соберать статистику, вохзможно вам понадобится нажимать \'продолжить\' больше чем один раз.');
define('L_ANALYZE_MSG_ERR_MEMORY_LIMIT','Возможно предел использования памяти - исчерпан ("Allowed memory size of ... exhausted"). В этом случае, востарайтесь увеличить <a href="../doc/config_settings_en.html#config_stat_memory_limit">$config_stat_memory_limit</a>.');
define('L_ANALYZE_MSG_ERR_UNKNOWN_ERROR','Ошибка была вызвана (возможно чемто перечисленным выше?). Возможно исчерпано время отведенное на работу скрипта или исчерпан лимит памяти. CrazyStat всеравно сможет собрать статистику.');
define('L_ANALYZE_MSG_ERR_CURRENT_POSITION','Текущая позиция');
define('L_ANALYZE_MSG_ERR_GUEST_CLEAR_CACHE','Вам не разрешено очищать статистику. См. <a href="../doc/config_settings_en.html#config_stat_guest_cache_delete" target="_blank">$config_stat_guest_cache_delete</a> и <a href="../doc/config_settings_en.html#config_stat_user_cache_delete" target="_blank">$config_stat_user_cache_delete</a>.');
define('L_ANALYZE_MSG_ERR_CACHE_BROKEN','Похоже кэш фаил сломан. Пожалуйста, следуйте следующим пунктам:');


// Show_log.php
define('L_SHOWLOG_TITLE', 'Вход');
define('L_SHOWLOG_ALLLOGS', 'Все журналы');
define('L_SHOWLOG_MSG_ERR_INVALID_ID', 'Существует файл журнала не известно к этому ID.');
define('L_SHOWLOG_MSG_ERR_LOGFILE_NOTFOUND', 'Лог-файл  для отображения не существует Пожалуйста, проверьте что файл  действительно существует:');
define('L_SHOWLOG_END_OF_LOGFILE', 'Последний лог-файл ');
define('L_SHOWLOG_NEXT_LOGFILE',' След. лог-файл ');
define('L_SHOWLOG_PREV_LOGFILE', 'Пред. лог-файл ');
define('L_SHOWLOG_PREV_PAGE', 'Пред. страница');
define('L_SHOWLOG_NEXT_PAGE', 'След. страница');
define('L_SHOWLOG_PAGE', 'Страница');
define('L_SHOWLOG_FORWARD', 'Вперед');
define('L_SHOWLOG_BACKWARD','Назад');
define('L_SHOWLOG_TEXT', 'Текст');
define('L_SHOWLOG_TABLE',' Таблица');
define('L_SHOWLOG_FILTERED',' отфильтрованно ');
define('L_SHOWLOG_ROWS_FOUND', 'строк найдено');

// Logs.php
define('L_LOGS_SEARCH_CONTAINS', 'содержит');
define('L_LOGS_SEARCH_CONTAINS_NOT', 'не содержит');
define('L_LOGS_SEARCH_UNEQUAL', 'неправильно');
define('L_LOGS_VALUE', 'Размер');
define('L_LOGS_TITLE', 'Лог фаилы');
define('L_LOGS_BACKUP',' резерв ');
define('L_LOGS_VIEW', 'Просмотр');
define('L_LOGS_FILTER_TITLE',' Поиск в журналах (фильтр) ');
define('L_LOGS_ADD_CONDITION',' Добавить условие ');
define('L_LOGS_SEARCH_SUBMIT', 'Поиск');
define('L_LOGS_SEARCH_WAIT', 'поиск в логах отчета... Пожалуйста, подождите .');
define('L_LOGS_MSG_ERR_NOFILE', 'Файл не выбран .');
define('L_LOGS_MSG_CONFIM_DELETE', 'Вы действительно хотите удалить эти файлы? ');
define('L_LOGS_SEARCH', 'Поиск');
define('L_LOGS_SELECT_ALL', 'Выделить все');
define('L_LOGS_SELECT_NONE','Очистить все');
define('L_LOGS_SELECTED', 'выделено');
define('L_LOGS_MSG_ERR_GUEST_DOWNLOAD','вым не разрешено скачивать статистику. см <a href="../doc/config_settings_en.html#config_stat_guest_log_download" target="_blank">$config_stat_guest_log_download</a> и <a href="../doc/config_settings_en.html#config_stat_user_log_download" target="_blank">$config_stat_user_log_download</a>.');
define('L_LOGS_MSG_ERR_GUEST_DELETE','Вам не разрешено удалять логи статистики. см <a href="../doc/config_settings_en.html#config_stat_guest_log_delete" target="_blank">$config_stat_guest_log_delete</a> и <a href="../doc/config_settings_en.html#config_stat_user_log_delete" target="_blank">$config_stat_user_log_delete</a>.');

// Show_stat.php
define('L_SHOWSTAT_PRESETS',' Настройки ');
define('L_SHOWSTAT_PRESETS_DEFAULT', 'По умолчанию');
define('L_SHOWSTAT_PRESETS_DEFAULT_OLD','Старый стандарт');
define('L_SHOWSTAT_PRESETS_IP1', 'Блоки по IP адресам');
define('L_SHOWSTAT_PRESETS_IP0', 'Не по IP адресу');
define('L_SHOWSTAT_PRESETS_PIE_CHARTS',' Круговые диаграммы');
define('L_SHOWSTAT_PRESETS_BAR_CHARTS', 'Гистограммы');
define('L_SHOWSTAT_PRESETS_THIS_YEAR', 'В этом году');
define('L_SHOWSTAT_PRESETS_THIS_MONTH', 'В этом месяце');
define('L_SHOWSTAT_PRESETS_ALL', 'Показать все(все сразу)');
define('L_SHOWSTAT_PRESETS_LIMIT', 'Только первое(ограничение)');
define('L_SHOWSTAT_PRESETS_SCROLL1',' Модуль прокрутки вкл');
define('L_SHOWSTAT_PRESETS_SCROLL0',' Модуль прокрутки выкл');
define('L_SHOWSTAT_PRESETS_TOTAL_TIME', 'Общее время');
define('L_SHOWSTAT_PRESETS_CURRENT_PERIOD',' Текущий период');
define('L_SHOWSTAT_PRESETS_MANAGE',' Управление настройками ');
define('L_SHOWSTAT_CLEAR_CACHE',' Очистить кэш');
define('L_SHOWSTAT_REFRESH_ALL', 'Обновить все');
define('L_SHOWSTAT_LOGS',' Журналы ');
define('L_SHOWSTAT_MSG_OK_WAIT',' Запрашиваемая операция займет некоторое время,пожалуйста ожидайте, статистика собирается.');

// Предустановка конкретных
define('L_PRESET_DEFAULT_YEAR', 'за последние 12 месяцев ');
define('L_PRESET_DEFAULT_MONTH', 'за месяц');
define('L_PRESET_DEFAULT_WEEK',' за последние 7 дней ');
define('L_PRESET_DEFAULT_DAY','за последние 24 часа ');

// Module_out.php
define('L_MODULEOUT_HITS_PI',' Общее количество просмотров');
define('L_MODULEOUT_HITS_VISITS',' Всего просмотров ');
define('L_MODULEOUT_HITS_THIS_MONTH',' Просмотров за этот месяц');
define('L_MODULEOUT_HITS_LAST_MONTH', 'Просмотров за прошлый месяц');
define('L_MODULEOUT_HITS_USER_ONLINE', 'пользователь онлайн');
define('L_MODULEOUT_HITS_MAX_DAY', 'Мак. посещений в день ');
define('L_MODULEOUT_HITS_AV_PER_DAY', 'Средне посещений в день ');
define('L_MODULEOUT_HITS_HITS_PER_USER',' Среднее кол-во просмотров страниц на посетителя');
define('L_MODULEOUT_HITS_VISIT_TIME_AVG','Среднее время посещения');
define('L_MODULEOUT_HITS_VISIT_TIME_TOTAL','Общее время посещения');
define('L_MODULEOUT_IP0', 'Просмотры страниц (не по IP)');
define('L_MODULEOUT_IP1', 'Просмотры страниц (по IP)');
define('L_MODULEOUT_NO_DATA',' нет данных ');
define('L_MODULEOUT_TOTAL_TIME', 'общее время');
define('L_MODULEOUT_PIE_CHART', 'Круговая');
define('L_MODULEOUT_BAR_CHART', 'График');
define('L_MODULEOUT_SORT_BY', 'Нажмите здесь для сортировки');
define('L_MODULEOUT_SORT_BY_NUM', 'Нажмите, для сортировки по количеству ');
define('L_MODULEOUT_SORT_BY_PER', 'Нажмите, чтобы сортировать по процентам');
define('L_MODULEOUT_SORT_BY_RATIO', 'Нажмите, для сортировки по соотношению');
define('L_MODULEOUT_NUM', 'Количество');
define('L_MODULEOUT_NUM_ABR', 'циф.');
define('L_MODULEOUT_PER',' проц.');
define('L_MODULEOUT_TOTAL', 'Всего');
define('L_MODULEOUT_RATIO', 'Отношение');
define('L_MODULEOUT_DOMAINS', 'Домены');
define('L_MODULEOUT_PAGES',' Страници ');
define('L_MODULEOUT_COLORS', 'цвета');
define('L_MODULEOUT_CONSOLE_PERIOD',' Период ');
define('L_MODULEOUT_CONSOLE_PERIOD_DEFINE',' Определить период ');
define('L_MODULEOUT_CLICK_IP1', 'Нажмите, для просмотра посетителей (по IP адресу).');
define('L_MODULEOUT_CLICK_IP0', 'Нажмите, для просмотра страниц (не  по IP адресу)');
define('L_MODULEOUT_CONSOLE_ALL', 'Показать все');
define('L_MODULEOUT_CONSOLE_ALL_ABR', 'все');
define('L_MODULEOUT_CONSOLE_SHOW_ONLY', 'Показать только');
define('L_MODULEOUT_CONSOLE_TREE_ABR',' Включить древовидный вид ');
define('L_MODULEOUT_CONSOLE_TREE',' Вклюсить древовидный вид и сортировать ссылающихся по хосту ');
define('L_MODULEOUT_CONSOLE_TREE_OFF',' Откл деревовидный вид, список всех ссылок');
define('L_MODULEOUT_CONSOLE_TREE_OFF_ABR',' Откл деревовидный вид');
define('L_MODULEOUT_CONSOLE_TREE_OTHER', 'Изменить внешний вид');
define('L_MODULEOUT_CONSOLE_TREE_COLLAPSE',' Свернуть ');
define('L_MODULEOUT_CONSOLE_TREE_EXPAND',' Развернуть ');
define('L_MODULEOUT_PRETTYINT_SUFFIX', 'м квадриллион квинтиллиона секстиллионов трлн млрд.');

// Preset_editor.php.........................................................................................
define('L_PRESETEDITOR_MANAGE_PRESETS',' Управление настройками');
define('L_PRESETEDITOR_ID', 'ID');
define('L_PRESETEDITOR_CACHE_SIZE', 'размер кэша');
define('L_PRESETEDITOR_SAVE_PRESET', 'Сохранить настройки');
define('L_PRESETEDITOR_SAVE_PRESET_TEXT','Это позволит сохранить текущие настройки (все из них), как настройки для будущего использования. Все настройки определяются автоматически.');
define('L_PRESETEDITOR_SAVE_PRESET_MSG_ABS','Внимание: Ваши текущие настройки содержат абсолютные временные промежутки для следующих модулей');
define('L_PRESETEDITOR_SAVE_PRESET_MSG_ABS2','Абсолютный промежуток времени не имеет смысла при сохранения как настройка!');
define('L_PRESETEDITOR_SAVE_PRESET_DUPLICATE','Эта настройка так же как:');
define('L_PRESETEDITOR_SAVE_PRESET_DUPLICATE_CANNOT_BE_SAVED','Можно только сохранять настройки, которые отличаются от существующих.');
define('L_PRESETEDITOR_CACHE','Кэшировать эту настройку');
define('L_PRESETEDITOR_CACHE_NOT','Не кэшировать эту настройку');
define('L_PRESETEDITOR_CACHE_UNCACHEABLE','Эта настройка неможет быть кэширована');
define('L_PRESETEDITOR_MSG_ERR_GUEST','Не разрешено изменять настройки . см <a href="../doc/config_settings_en.html#config_stat_guest_preset_manage" target="_blank">$config_stat_guest_preset_manage</a> и <a href="../doc/config_settings_en.html#config_stat_user_preset_manage" target="_blank">$config_stat_user_preset_manage</a>.');
define('L_PRESETEDITOR_MSG_PRESET_DELETE','Вы РЕАЛЬНО хотите удалить эту настройку');
define('L_PRESETEDITOR_MSG_PRESET_DELETED','Настройка удалена');
define('L_PRESETEDITOR_MSG_PRESET_SAVED','Настрока сохранена');

// Anonymous_redirect.php
define('L_ANONYMOUS_REDIRECT', 'Вы будете перенаправлены анонимно: ');

// Меню текстов
define('L_MENU_WEBSITE', 'Посетить сайт');
define('L_MENU_ABOUT',' О CrazyStat');
define('L_MENU_STATISTIC',' Открытая статистика');

// Модули
define('L_MODULES_HIT_P', 'Клики');
define('L_MODULES_HIT_S', 'Клик');
define('L_MODULES_WEEKDAY_P',' Дни недели ');
define('L_MODULES_WEEKDAY_S', 'День недели');
define('L_MODULES_MONTH_P', 'Месяцы');
define('L_MODULES_MONTH_S', 'Месяц');
define('L_MODULES_DAY_P', 'Дней');
define('L_MODULES_DAY_S', 'День');
define('L_MODULES_HOUR_P',' Часы ');
define('L_MODULES_HOUR_S', 'Час');
define('L_MODULES_BROWSER_P',' Браузеры ');
define('L_MODULES_BROWSER_S',' Браузер ');
define('L_MODULES_FILE_P',' Файлы ');
define('L_MODULES_FILE_S', 'Файл');
define('L_MODULES_RESOLUTION_P',' Разрешения ');
define('L_MODULES_RESOLUTION_S', 'Разрешение');
define('L_MODULES_COLORDEPTH_P',' Глубины цветов ');
define('L_MODULES_COLORDEPTH_S',' Глубина цвета ');
define('L_MODULES_SYSTEM_P', 'Системы');
define('L_MODULES_SYSTEM_S', 'Система');
define('L_MODULES_REFERER_P', 'Рефералы');
define('L_MODULES_REFERER_S',' Реферер ');
define('L_MODULES_KEYWORD_P', 'Ключевые слова');
define('L_MODULES_KEYWORD_S',' Ключевое слово ');

// Общие сообщения об ошибках
define('L_MSG_ERR_INCLUDE_ONLY','Этот фаил не может быть открыт напрямую.');
define('L_MSG_ERR_NO_MODULE','Модуль не указан или нет подходящего.');
define('L_MSG_ERR','Оибка');
define('L_MSG_ERR_CONTINUE','Продолжить');

// Общая тексты
define('L_LOGOUT', 'Выход');
define('L_CLOSE', 'Close');
define('L_OK', 'OK');
define('L_NUMBER', 'Нет');
define('L_TIME', 'Время');
define('L_DATE', 'Дата');
define('L_IP', 'IP');
define('L_USERAGENT', 'UA');
define('L_BIT','Бит');
define('L_DATE_FORMAT', 'd/m/y');
define('L_TIME_FORMAT', 'H:i:s');
define('L_GO', '&gt;&gt;');
define('L_PLEASE_WAIT', 'Пожалуйста, подождите ...');
define('L_HITS', 'клики');
define('L_VISITS',' Количество визитов ');
define('L_PAGEIMPRESSIONS', 'Page Views');
define('L_BACK',' Назад ');
define('L_CANCEL', 'Отмена');
define('L_DELETE', 'Удалить');
define('L_REFRESH',' Обновить ');
define('L_AND', 'и ');
define('L_LANGUAGE', 'Язык');
define('L_NAME', 'Имя');
define('L_DECIMAL_SEPARATOR','.');
define('L_THOUSANDS_SEPARATOR',' ');
define('L_AVG_SYMBOL','≈');

define('L_MINUTES','минуты');
define('L_MINUTES_ABR','мин');
define('L_SECONDS','секунды');
define('L_SECONDS_ABR','с');
define('L_HOURS','часы');
define('L_HOURS_ABR','ч');
define('L_DAYS','дни');
define('L_days_ABR','д');

?>