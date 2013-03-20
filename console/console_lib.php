<?php
/**
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/atelierspierrot>
 *
 * console/console_lib.php
 * 
 * Library of functions for PHP CLI usage.
 * 
 * To overwrite one of these functions, just define your method BEFORE including this
 * file.
 *
 */
@ini_set('display_errors','1'); @error_reporting(E_ALL ^ E_NOTICE);

// ----------------
// Defaults
// ----------------

@define('_AP_SCRIPT_AUTHOR', 'Les Ateliers Pierrot <www.ateliers-pierrot.fr>');
@define('_AP_SCRIPT_DIR', __DIR__);

// ----------------
// First checks
// ----------------

$librequires = array('_AP_SCRIPT_VERSION', '_AP_SCRIPT_NAME', '_AP_SCRIPT_FILENAME');
foreach($librequires as $def) {
    if (!defined($def)) {
        die(sprintf('You must define constant "%s" in your script to use the console library!', $def)."\n");
    }
}

// ----------------
// Library
// ----------------

if (!function_exists('cliOnly')) {
    function cliOnly()
    {
        if (php_sapi_name()!='cli') {
            die('Command line usage only: run `~$ php '._AP_SCRIPT_FILENAME.' -h` for more infos.');
        }
    }
}

if (!function_exists('errorHandler')) {
    function errorHandler($errno, $errstr, $errfile, $errline)
    {
        writeError(
            getBold('!! - '.$errstr)."\n"
            .'    Error sent in file ['.$errfile.'] at line ['.$errline.']'."\n"
            .'    For more infos, run:'."\n"
            .'        ~$ php path/to/'._AP_SCRIPT_FILENAME.' -h'."\n"
        );
    }
    set_error_handler('errorHandler');
}

if (!function_exists('exceptionHandler')) {
    function exceptionHandler($exception)
    {
        writeError(
            getBold('!! - '.$exception->getMessage())."\n"
            .'    Error sent in file ['.$exception->getFile().'] at line ['.$exception->getLine().']'."\n"
            .'    For more infos, run:'."\n"
            .'        ~$ php path/to/'._AP_SCRIPT_FILENAME.' -h'."\n"
        );
    }
    set_exception_handler('exceptionHandler');
}

if (!function_exists('writeError')) {
    function writeError($string, $status = 1)
    {
        fwrite(STDERR, "\n".$string."\n");
        exit($status);
    }
}

if (!function_exists('write')) {
    function write($string = '')
    {
        fwrite(STDOUT, $string."\n");
    }
}

if (!function_exists('writePrompt')) {
    function writePrompt($string, $default = null)
    {
        fwrite(STDOUT, $string.(!empty($default) ? ' ['.$default.']' : '')." ? ");
    }
}

if (!function_exists('writeBold')) {
    function writeBold($string)
    {
        fwrite(STDOUT, getBold($string)."\n");
    }
}

if (!function_exists('getBold')) {
    function getBold($string)
    {
        return "\033[1m".$string."\033[0m";
    }
}

if (!function_exists('writeList')) {
    function writeList($list, $mask = '  %s : %s')
    {
        $indexlgt = 0;
        foreach($list as $key=>$value) {
            if (is_string($key) && strlen($key)>$indexlgt) {
                $indexlgt = strlen($key);
            }
        }
        foreach($list as $left=>$right) {
            if (''==trim($right) && (!is_string($left) || ''==trim($left))) {
                write();
            } else {
                write(sprintf(
                    $mask, str_pad((is_string($left) ? $left : ''), $indexlgt), $right
                ));
            }
        }
    }
}

if (!function_exists('cls')) {
    function cls()
    {
        system("clear");
    }
}

if (!function_exists('getInput')) {
    function getInput()
    {
        $line = fgets(STDIN);
        return trim($line);
    }
}

if (!function_exists('execCommand')) {
    function execCommand($cmd = null, $relpath = null, $silent = false)
    {
        $here = getcwd();
        if (!is_null($relpath)) {
            $cmd = 'cd '.$relpath.' && '.$cmd;
        }
        if (!$silent) {
            write('-- cmd: '.$cmd);
            $return = system($cmd);
        } else {
            $ok = exec($cmd, $return);
            $return = implode("\n", $return);
        }
        exec('cd '.$here);
        return $return;
    }
}

if (!function_exists('writeTitle')) {
    function writeTitle()
    {
        writeBold('## '.basename(__FILE__).' - '._AP_SCRIPT_NAME.' ##');
        write('(v. '.getBold(_AP_SCRIPT_VERSION).' - by '._AP_SCRIPT_AUTHOR.')');
    }
}

// Endfile
