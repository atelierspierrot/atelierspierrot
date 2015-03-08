#!/usr/bin/php
<?php
/**
 * Copyleft (ↄ) 2008-2015 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * The source code of this package is available online at 
 * <http://github.com/atelierspierrot/atelierspierrot>.
 * 
 * console/submodules-from-packages-builder
 *
 * Generate a .gitmodules file from composer dependencies of a package
 * 
 * For more infos run:
 * 
 *     ~$ php path/to/submodules-from-packages-builder -h
 * 
 */
@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

define('_AP_SCRIPT_VERSION', '1.0.0');
define('_AP_SCRIPT_NAME', 'GIT submodules builder from a package Composer dependencies');
define('_AP_SCRIPT_AUTHOR', 'Les Ateliers Pierrot <www.ateliers-pierrot.fr>');
define('_AP_SCRIPT_FILENAME', basename(__FILE__));
define('_AP_SCRIPT_DIR', __DIR__);
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

global $filename, $backup_filename, $path, $output, $go, $mode, $target_file, $init;
$filename = 'composer.json';
$target_file = '.gitmodules';
$backup_filename = '%s.bak';
$path='.';
$output=false;
$mode='';
$go=false;
$init=false;

// -------------------
// library
// -------------------

function writeHelp()
{
    global $files_collection;
    write();
    writeBold('Presentation');
    write('    Shell script to generate a `.gitmodules` file for a GIT repository of a package based on its Composer dependencies.');
    write();
    writeBold('Usage');
    write('    ~$ '.getBold('php '._AP_SCRIPT_FILENAME.' -[option[=value]]'));
    write();
    writeBold('Options');
    writeList(array(
        'p=path' => 'path to the working root directory, absolute path or relative to `cwd` (default is `.`, the current directory)',
        'f=filename' => 'the filename defining the package (default is `composer.json`)',
        't=filename' => 'the target filename to populate (default is `.gitmodules`)',
        'b=filename' => 'the filename used to backup an existing file (default is `.gitmodules.bak`)',
        'm=mode' => 'the Composer mode to use for "require" statement (default is normal, can be "dev")',
        'o'=>'output: writes the changelog on screen instead of writing the file',
        'i'=>'init: run `git submodule init` after file creation to test its content',
        'g'=>'"GO": process the changelog generation ; use this option to avoid viewing the help without any option',
    ), '    %s    %s');
    write();
    writeBold('Generated module block for each dependency');
    write('
    [submodule "package/name"]
            path = package/path/in/project
            url = git source url
');
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

function runFilename($name)
{
    global $filename;
    $filename = $name;
}

function runTargetFile($name)
{
    global $target_file;
    $target_file = $name;
}

function runBackupFilename($name)
{
    global $backup_filename;
    $backup_filename = $name;
}

function runMode($name)
{
    global $mode;
    $mode = $name;
}

function runOutput()
{
    global $output;
    $output = true;
}

function runPath($_path)
{
    global $path;
    $path = $_path;
}

function runGo()
{
    global $go;
    $go = true;
}

function runInit()
{
    global $init;
    $init = true;
}

function run()
{
    global $filename, $backup_filename, $path, $output, $mode, $target_file, $init;
    if (file_exists($path)) {
        if (true===isGitRepository($path)) {
            $composer_file = rtrim($path, '/').'/'.$filename;
            if (file_exists($composer_file)) {
                $package = getComposerLocalPackageInfos($composer_file);
                if (!empty($package)) {
                    $vendor_dir = 'vendor';
                    if (isset($package['config']) && isset($package['config']['vendor-dir'])) {
                        $vendor_dir = $package['config']['vendor-dir'];
                    }
                    if (file_exists(rtrim($path, '/').'/'.$vendor_dir)) {
                        $modules_db = array();
                        foreach($package['require'] as $name=>$module) {
                            getModuleAndFollow($name, &$modules_db, $mode);
                        }
                        if (!empty($mode) && $mode==='dev' && !empty($package['require-dev'])) {
                            foreach($package['require-dev'] as $name=>$module) {
                                getModuleAndFollow($name, &$modules_db, $mode);
                            }
                        }

                        if (!empty($modules_db)) {
                            $gitmodules = '';
                            foreach($modules_db as $name=>$url) {
                                $dir = rtrim($vendor_dir, '/').'/'.$name;
                                $gitmodules .= <<<EOT
[submodule "$name"]
        path = $dir
        url = $url

EOT;
                            }
                            if (true===$output) {
                                write($gitmodules);
                            } else {
                                $gitmodules_file = rtrim($path, '/').'/'.$target_file;
                                $backup_gitmodules_file = rtrim($path, '/').'/'.sprintf($backup_filename, $target_file);
                                if (false!==writeFile($gitmodules_file, $gitmodules, $backup_gitmodules_file)) {
                                    execCommand('chmod +x '.$target_file, null, true);
                                    writeBold(sprintf('Creating modules database in file "%s"', $gitmodules_file));
                                    if (true===$init) {
                                        execGitCommand('submodule init', null, false);
                                    }
                                } else {
                                    trigger_error(sprintf('Can not create file "%s"!', $gitmodules_file), E_USER_ERROR);
                                }
                            }
                        } else {
                            write('No module found!');
                        }
                    } else {
                        trigger_error(sprintf('Vendor directory "%s" not found!', $vendor_dir), E_USER_ERROR);
                    }
                } else {
                    trigger_error(sprintf('Composer file "%s" can not be red or is not a valid JSON!', $composer_file), E_USER_ERROR);
                }
            } else {
                trigger_error(sprintf('Composer file "%s" not found!', $composer_file), E_USER_ERROR);
            }
        } else {
            trigger_error(sprintf('Directory "%s" is not a GIT repository!', $path), E_USER_ERROR);
        }
    } else {
        trigger_error(sprintf('Directory "%s" not found!', $path), E_USER_ERROR);
    }
}

function getModuleAndFollow($package_name, &$modules_db, $mode = 'normal')
{
    $module_package = getComposerPackageInfos($package_name);
    $source_url = getPackageSourceUrl($module_package);
    if (!empty($source_url)) {
        $modules_db[$package_name] = $source_url;
    }

    if (!empty($module_package['requires'])) {
        foreach($module_package['requires'] as $req_name=>$req) {
            if (!array_key_exists($req_name, $modules_db)) {
                getModuleAndFollow($req_name, $modules_db, $mode);
            }
        }
    }

    if ($mode==='dev' && !empty($module_package['requires (dev)'])) {
        foreach($module_package['requires (dev)'] as $reqdev_name=>$reqdev) {
            if (!array_key_exists($reqdev_name, $modules_db)) {
                getModuleAndFollow($reqdev_name, $modules_db, $mode);
            }
        }
    }
}

// -------------------
// script execution
// -------------------

// arguments
$args = array(
    'h'=>'help',
    'f:'=>'filename',
    'b:'=>'backupFilename',
    't:'=>'targetFile',
    'p:'=>'path',
    'm:'=>'mode',
    'o'=>'output',
    'i'=>'init',
    'g'=>'go',
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
