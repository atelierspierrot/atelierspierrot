#!/usr/bin/php
<?php
/**
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <http://github.com/atelierspierrot/atelierspierrot>
 * 
 * console/dir-cleaner
 *
 * Delete a collection of unwanted OS or software files in a directory, recursively
 * 
 * For more infos run:
 * 
 *     ~$ php path/to/dir-cleaner -h
 * 
 */
@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

define('_AP_SCRIPT_VERSION', 'wip');
define('_AP_SCRIPT_NAME', 'CLI terminal & library tester');
define('_AP_SCRIPT_FILENAME', basename(__FILE__));
define('_AP_CONSOLE_LIBRARY', __DIR__.'/console_lib.php');

// -------------------
// requirements
// -------------------

if (@file_exists(_AP_CONSOLE_LIBRARY)) {
    include_once _AP_CONSOLE_LIBRARY;
} else {
    die('The library "console_lib.php" is required in the same directory as this script!'."\n");
}
cliOnly();

// -------------------
// defaults
// -------------------

global $path;
$path='.';

// -------------------
// library
// -------------------

function writeHelp()
{
    write();
    writeBold('Presentation');
    write('    Shell script to test your terminal and the distributed PHP console library "console_lib.php".');
    write();
    writeBold('Usage');
    write('    ~$ '.getBold('php '._AP_SCRIPT_FILENAME.' -[option[=value]]'));
    write();
    writeBold('Options');
    writeList(array(
        'p=path' => 'path to the working root directory, absolute path or relative to `cwd` (default is `.`, the current directory)',
        'c' => 'test the colorization of texts with your console',
    ), '    %s    %s');
    write();
}

// -------------------
// options treatments
// -------------------

function runHelp()
{
    writeTitle();
    writeHelp();
    exit(0);
}

function runColor()
{
    write(
        'test of '
        .getForegroundColored('colored text', 'red')
        .' and '
        .getBackgroundColored('backgrounded span', 'green')
    );
}

function run()
{

}

// -------------------
// script execution
// -------------------

// arguments
$args = array(
    'h'=>'help',
    'c'=>'color',
    'p:'=>'path',
);
$options = getopt( implode('', array_keys($args)) );

// if no option at all
if (empty($options)) {
    runHelp();
} else {
    foreach($options as $name=>$value) {
        if (isset($args[$name]) || isset($args[$name.':'])) {
            $argname = isset($args[$name.':']) ? $name.':' : $name;
            $_meth = 'run'.ucfirst($args[$argname]);
            $_arg = !empty($value) ? $value : null;
            if (function_exists($_meth)) {
                $_meth($_arg);
            } else {
                trigger_error('Unknown option "'.$name.'"!', E_USER_ERROR);
            }
        } else {
            trigger_error('Unknown option "'.$name.'"!', E_USER_ERROR);
        }
    }
}
run();
exit(0);
// Endfile
