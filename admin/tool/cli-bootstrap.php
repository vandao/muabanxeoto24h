<?php

error_reporting(E_ALL);

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', getEnviroment());

if (in_array(APPLICATION_ENV, array('production', 'stagging'))) {
    ini_set('display_errors', 0);
} else {
    ini_set('display_errors', 1);
}
echo "############################
Running on " . APPLICATION_ENV . "
############################\n\n";

try {

    /**
     * Read the configuration
     */
    $config    = include __DIR__ . "/../app/config/config.php";
    $configEnv = include __DIR__ . "/../app/config/config-" . APPLICATION_ENV . ".php";
    $config->merge($configEnv);

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Read constants
     */
    include __DIR__ . "/../app/config/constants.php";

    /**
     * Read api function
     */
    include __DIR__ . "/api-function.php";
    include __DIR__ . "/api-commission-function.php";
    include __DIR__ . "/api-publisher-function.php";

    /**
     * Handle the request
     */
    $app = new \Phalcon\Mvc\Application($di);

} catch (\Exception $e) {
    echo $e->getMessage();
}



/**
 * Create folders
 * @param string $folderPath
 */
function createFolders($folderPath) {
    if (! is_dir($folderPath)) {

        $oldmask = umask(0);
        mkdir($folderPath, 0777, true);
        umask($oldmask);
    }
}

function getEnviroment() {
    $webserverConfigFile = '/etc/nginx/sites-enabled/admin.affiliate';

    $config     = `cat $webserverConfigFile | grep APPLICATION_ENV`;
    $enviroment = 'production';

    if ($config != '') list(,$enviroment) = explode('"', $config);

    return $enviroment;
}

function message($status, $message) {
    echo $message . "\n";
}

function getValues($string, $seperate = ':', $explode = "\n", $keyPosition = 'before') {
    $values = array();

    foreach (explode($explode, $string) as $line) {
        if ($line != '') {
            $value = explode($seperate, $line);
            if (count($value) > 1) {
                $key   = current($value);
                $value = end($value);

                if ($keyPosition == 'before') {
                    $values[trim($key)] = trim($value);
                } else {
                    $values[trim($value)] = trim($key);
                }
            }
        }
    }

    return $values;
}

function getString($string, $posKey = "\n", $lastKey = "", $position = 1) {
    foreach (explode($posKey, $string) as $linePosition => $line) {
        if ($position == $linePosition) {
            if ($line != '') {
                if ($lastKey === "") {
                    return trim($line);
                } else {
                    $values = explode($lastKey, $line);

                    if (isset($values[1])) return trim($values[1]);
                }
            }
        }
    }

    return "";
}

function getIps() {
    $ipString = trim(`ifconfig | grep "inet addr" | awk '{print $2}' | sed 's/addr://g'`);

    return explode("\n", $ipString);
}

/**
 * Get all day in month from day to day
 * @param string $dateFrom (04-05-2011)
 * @param string $dayTo (10-05-2011)
 * @return array day
 */
function getDailyDates($dateFrom, $dateTo) {
    $dateTimestampFrom    = strtotime($dateFrom);
    $dateTimestampTo      = strtotime($dateTo);

    $dayTimestamp         = $dateTimestampFrom;

    $days = array();
    while($dayTimestamp <= $dateTimestampTo) {
        $days[] = date('Y-m-d', $dayTimestamp);

        $dayTimestamp = strtotime(date('Y-m-d', $dayTimestamp) . " + 1 day");
    }

    return $days;
}

function getWeeklyDates($dateFrom, $dateTo) {
    $dateTimestampFrom = strtotime($dateFrom);
    $dateTimestampTo   = strtotime($dateTo);

    $dayTimestamp = strtotime('this week', $dateTimestampFrom);

    $days = array();
    $i    = 1;
    while($dayTimestamp <= $dateTimestampTo) {
        $week = date("W", $dayTimestamp);
        $year = date("Y", $dayTimestamp);

        if ($i == 1) {
            if ($week == "01") $year++;
            if (! isset($days[$year]))        $days[$year]        = array();
            if (! isset($days[$year][$week])) $days[$year][$week] = array('from' => '', 'to' => '');

            $days[$year][$week]['from'] = date('Y-m-d', $dayTimestamp);
        }

        if ($i == 7) {
            $days[$year][$week]['to'] = date('Y-m-d', $dayTimestamp);
            $i = 0;
            $week++;
        } elseif ($dayTimestamp == $dateTimestampTo) {
            $days[$year][$week]['to'] = date('Y-m-d', $dayTimestamp);
            $i = 0;
            $week++;
        }

        $i++;
        $dayTimestamp = strtotime(date('Y-m-d', $dayTimestamp) . " + 1 day");
    }

    return $days;
}

function getFortnightlyDates($dateFrom, $dateTo) {
    $days = getWeeklyDates($dateFrom, $dateTo);

    $fortnightly = array();
    foreach ($days as $year => $weeks) {
        foreach ($weeks as $week => $dates) {
            $count = round($week/2);

            if ($week % 2) {
                $fortnightly[$year][$count] = array();

                $fortnightly[$year][$count]['from'] = $dates['from'];
            } else {
                if (! isset($fortnightly[$year][$count]['from'])) {
                    $fortnightly[$year][$count]['from'] = $dates['from'];
                }

                $fortnightly[$year][$count]['to']   = $dates['to'];
            }

            $lastDate = $dates['to'];
        }
    }

    $fortnightly[$year][$count]['to'] = $lastDate;

    return $fortnightly;
}

function getMonthlyDates($dateFrom, $dateTo) {
    $dateTimestampFrom = strtotime($dateFrom);
    $dateTimestampTo   = strtotime($dateTo);

    $dayTimestamp = $dateTimestampFrom;

    $days      = array();
    while($dayTimestamp <= $dateTimestampTo) {
        $year  = date("Y", $dayTimestamp);
        $month = date("m", $dayTimestamp);

        if (! isset($days[$year]))         $days[$year]         = array();
        if (! isset($days[$year][$month])) $days[$year][$month] = array('from' => '', 'to' => '');

        $days[$year][$month]['from'] = date('Y-m-d', strtotime('first day of this month', $dayTimestamp));
        $days[$year][$month]['to']   = date('Y-m-d', strtotime('last day of this month', $dayTimestamp));

        $dayTimestamp = strtotime(date('Y-m-d', $dayTimestamp) . " + 1 month");
    }

    // Re set last day
    $days[$year][$month]['to'] = date('Y-m-d', $dateTimestampTo);

    return $days;
}

/**
 * Replaces url entities with -
 *
 * @param string $fragment
 * @return string
 */
function cleanEntities($fragment)
{
    $translite_simbols = array (
    '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
    '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
    '#(ì|í|ị|ỉ|ĩ)#',
    '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
    '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
    '#(ỳ|ý|ỵ|ỷ|ỹ)#',
    '#(đ)#',
    '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
    '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
    '#(Ì|Í|Ị|Ỉ|Ĩ)#',
    '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
    '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
    '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
    '#(Đ)#',
    "/[^a-zA-Z0-9\-\_]/",
    ) ;
    $replace = array (
    'a',
    'e',
    'i',
    'o',
    'u',
    'y',
    'd',
    'A',
    'E',
    'I',
    'O',
    'U',
    'Y',
    'D',
    '-',
    ) ;
    $fragment = preg_replace($translite_simbols, $replace, $fragment);
    $fragment = preg_replace('/(-)+/', '-', $fragment);

    return $fragment;
}
