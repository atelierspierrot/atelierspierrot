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

define('_AP_CONSOLE_LIBRARY_VERSION', '1.0.0');
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
// CLI utilities
// ----------------

if (!function_exists('cliOnly')) {
    function cliOnly()
    {
        if (php_sapi_name()!='cli') {
            die('Command line usage only: run `~$ php '._AP_SCRIPT_FILENAME.' -h` for more infos.');
        }
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

// ----------------
// CLI I/O
// ----------------

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

if (!function_exists('writeTitle')) {
    function writeTitle()
    {
        write(
            getBold('## '._AP_SCRIPT_FILENAME.' : '._AP_SCRIPT_NAME.' ##')."\n"
            .'(v. '.getBold(_AP_SCRIPT_VERSION).' - by '._AP_SCRIPT_AUTHOR.')'
        );
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

if (!function_exists('output')) {
    function output($string = '')
    {
        echo $string;
    }
}

if (!function_exists('write')) {
    function write($string = '', $pass_line = true)
    {
        fwrite(STDOUT, $string.(true===$pass_line ? "\n" : ''));
    }
}

if (!function_exists('writeError')) {
    function writeError($string, $status = 1, $pass_line = true, $prepend_line = true)
    {
        fwrite(STDERR, (true===$prepend_line ? "\n" : '').$string.(true===$pass_line ? "\n" : ''));
        exit($status);
    }
}

if (!function_exists('writePrompt')) {
    function writePrompt($string, $default = null)
    {
        fwrite(STDOUT, $string.(!empty($default) ? ' ['.$default.']' : '')." ? ");
    }
}

if (!function_exists('writeBold')) {
    function writeBold($string, $pass_line = true)
    {
        fwrite(STDOUT, getBold($string).(true===$pass_line ? "\n" : ''));
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

if (!function_exists('getScreenWidth')) {
    function getScreenWidth()
    {
        return exec('tput cols');
    }
}

if (!function_exists('getScreenHeight')) {
    function getScreenHeight()
    {
        return exec('tput lines');
    }
}

if (!function_exists('getPositionedString')) {
    function getPositionedString($string, $x = 0, $y = 0)
    {
        return "\033[".$y.";".$x."H".$string;
    }
}

if (!function_exists('erasePosition')) {
    function erasePosition($x = 0, $y = 0, $length = 1, $height = 1)
    {
        $str = str_pad(' ', $length);
        for($j=0; $j<$height; $j++) {
            output(getPositionedString($str, $x, $y+$j));
        }
    }
}

if (!function_exists('getBold')) {
    function getBold($string)
    {
        return "\033[1m".$string."\033[0m";
    }
}

if (!function_exists('getBackgroundColored')) {
    function getBackgroundColored($string, $color)
    {
        static $background_colors = array(
            'black'         => '40',
            'red'           => '41',
            'magenta'       => '45',
            'yellow'        => '43',
            'green'         => '42',
            'blue'          => '44',
            'cyan'          => '46',
            'light_gray'    => '47',
        );
        if (!isColorCapable()) return $string;
        if (isset($background_colors[$color])) {
            return "\033[" . $background_colors[$color] . "m" . $string . "\033[0m";
        } else {
            throw new Exception(sprintf('Foreground color "%s" is not defined!', $color));
        }
    }
}

if (!function_exists('getForegroundColored')) {
    function getForegroundColored($string, $color)
    {
        static $foreground_colors = array(
            'black'         => '0;30',
            'dark_gray'     => '1;30',
            'red'           => '0;31',
            'bold_red'      => '1;31',
            'green'         => '0;32',
            'bold_green'    => '1;32',
            'brown'         => '0;33',
            'yellow'        => '1;33',
            'blue'          => '0;34',
            'bold_blue'     => '1;34',
            'purple'        => '0;35',
            'bold_purple'   => '1;35',
            'cyan'          => '0;36',
            'bold_cyan'     => '1;36',
            'white'         => '1;37',
            'bold_gray'     => '0;37',
        );
        if (!isColorCapable()) return $string;
        if (isset($foreground_colors[$color])) {
            return "\033[" . $foreground_colors[$color] . "m" . $string . "\033[0m";
        } else {
            throw new Exception(sprintf('Foreground color "%s" is not defined!', $color));
        }
    }
}

if (!function_exists('isColorCapable')) {
    function isColorCapable()
    {
        $col = execCommand('tput colors', null, true);
        return (!empty($col) && intval($col)>=8);
    }
}

// ----------------
// GIT utilities
// ----------------

if (!function_exists('execGitCommand')) {
    function execGitCommand($cmd = 'checkout', $relpath = null, $silent = false)
    {
        $git_bin = exec('which git');
        $git_cmd = sprintf('%s %s', $git_bin, $cmd);
        return execCommand($git_cmd, $relpath, $silent);
    }
}

if (!function_exists('isGitRepository')) {
    function isGitRepository($path)
    {
        return (file_exists($path) && file_exists(rtrim($path, '/').'/.git') && is_dir(rtrim($path, '/').'/.git'));
    }
}

if (!function_exists('getGitBranches')) {
    function getGitBranches($path)
    {
        $branches = execGitCommand('branch', $path, true);
        $branches_list = explode("\n", $branches);
        $result = array();
        foreach($branches_list as $k=>$branch) {
            $branchname = str_replace(array('*', ' '), '', $branch);
            $result[] = $branchname;
        }
        return $result;
    }
}

if (!function_exists('getGitCurrentBranch')) {
    function getGitCurrentBranch($path)
    {
        $branches = execGitCommand('branch', $path, true);
        $branches_list = explode("\n", $branches);
        $current_branch = 'HEAD';
        foreach($branches_list as $k=>$branch) {
            if (strlen($branch) && $branch[0]=='*') {
                $current_branch = str_replace(array('*', ' '), '', $branch);
            }
        }
        return $current_branch;
    }
}

function getGitNearestTag($path)
{
    $refs = rtrim($path, '/').'/.git/refs/tags';
    if (file_exists($refs) && count(scandir($refs))>2) {
        $result = execGitCommand('describe --tags --abbrev=0', $path, true);
        if ('fatal'===substr($result, 0, strlen('fatal'))) {
            return false;
        }
        return $result;
    }
    return false;
}

function getCommitsHistory($path, $limit = false, $since = null, $until = null)
{
    $pretty_format = '----%ncommit-abbrev:%h%nauthor_name:%an%nauthor_email:%ae%ndate:%ai%ntitle:%s%nmessage:%b%n';
    $added = '';
    if (!empty($since) || !empty($until)) {
        if (empty($since)) {
            $last_commit = getCommitsHistory($path, '-1');
            $since = $last_commit[0]['commit-abbrev'];
        }
        $added = $since.'..'.$until;
    }
    $history_cmd = 'log '.(!empty($limit) ? $limit.' ' : '').$added.' --format="'.$pretty_format.'"';
    $history = execGitCommand('--no-pager '.$history_cmd, $path, true);
    $changelog = array();

    $commits_list = explode("----\n", $history);
    foreach($commits_list as $k=>$commit) {
        $changelog_entry = array();
        $last_index = null;
        $commit_infos = explode("\n", $commit);
        if (count($commit_infos)>1 && !empty($commit_infos)) {
            foreach($commit_infos as $k=>$infostr) {
                $ok_match = preg_match('/^([a-z-_]+):(.*)$/i', $infostr, $matches);
                if ($ok_match!==0) {
                    $last_index = $matches[1];
                    $changelog_entry[$matches[1]] = $matches[2];
                } else {
                    if (!isset($last_index)) $last_index = 'info';
                    if (!isset($changelog_entry[$last_index])) $changelog_entry[$last_index] = '';
                    $changelog_entry[$last_index] .= ' '.$infostr;
                }
            }
            if (!empty($changelog_entry)) {
                $changelog[] = $changelog_entry;
            }
        }
    }
    return $changelog;     
}

// ----------------
// Filesystem utilities
// ----------------

if (!function_exists('writeFile')) {
    function writeFile($filepath, $content, $backup_filepath = false)
    {
        if (file_exists(dirname($filepath))) {
            if (file_exists($filepath) && !empty($backup_filepath)) {
                rename($filepath, $backup_filepath);                    
            }
            return file_put_contents($filepath, $content);
        }
        return false;
    }
}

if (!function_exists('appendInFile')) {
    function appendInFile($filepath, $content, $separator = "\n")
    {
        if (file_exists($filepath)) {
            $ctt = file_get_contents($filepath);
            return file_put_contents($filepath, $ctt.$separator.$content);
        } else {
            return writeFile($filepath, $content);
        }
    }
}

if (!function_exists('prependInFile')) {
    function prependInFile($filepath, $content, $separator = "\n")
    {
        if (file_exists($filepath)) {
            $ctt = file_get_contents($filepath);
            return file_put_contents($filepath, $content.$separator.$ctt);
        } else {
            return writeFile($filepath, $content);
        }
    }
}

// Endfile
