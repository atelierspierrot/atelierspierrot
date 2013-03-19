#!/usr/bin/php
<?php
/**
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License GPL-3.0 <http://www.opensource.org/licenses/gpl-3.0.html>
 * Sources <https://github.com/atelierspierrot/atelierspierrot>
 * 
 * Create/Read/Update/Delete some/all AteliersPierrot GitHub clones
 * 
 * For more infos run:
 * 
 *     ~$ php path/to/ap-repos-manager.php -h
 * 
 */
@ini_set('display_errors','1'); @error_reporting(E_ALL ^ E_NOTICE);
if (php_sapi_name()!='cli') {
    die('Command line usage only: run `~$ php ap-repos-manager.php -h` for more infos.');
}

define('_AP_MANAGER_VERSION', '1.0.0');
define('_AP_MANAGER_NAME', 'GIT repositories manager for GitHub users');
define('_AP_MANAGER_AUTHOR', 'Les Ateliers Pierrot <www.ateliers-pierrot.fr>');

// -------------------
// defaults
// -------------------

global $username, $usertype, $action, $mask, $path, $webhost, $hosturl;
$path='.';
$username = 'atelierspierrot';
$usertype = 'org'; // 'org' or 'user'
$webhost = 'https://api.github.com/';
$hosturl = '@webhost@/@usertype@/@username@/repos';

// -------------------
// library
// -------------------

function writeHelp()
{
    $file = basename(__FILE__);
    write();
    writeBold('Presentation');
    write('  Shell script to manage a local collection of GIT repositories from a webhost. Allowing to list distant repositories, create the collection clones, read the local collection informations, update the collection clones, delete the local clones and get the GIT status for each of them. You can specify a PCRE mask to filter working repositories list and define the local working path.');
    write('  (cURL and GIT binaries are required)');
    write();
    writeBold('Usage');
    write('    ~$ '.getBold('php '.$file.' -[option[=value]]'));
    write();
    writeBold('Basic options');
    writeList(array(
        'p=path' => 'path to your local clones directory, absolute path or relative to `cwd` (default is `.`, the current directory)',
        'm=mask' => 'filter the repositories that match the given mask (you must surround mask between double-quotes and may use PCRE valid expressions)',
        'a=action' => 'which action to process in:',
        '  l' => '    list concerned available repositories from the host',
        '  s' => '    get the git status for local clones of all concerned repositories',
        '  c' => '    create a local clone of all concerned repositories',
        '  r' => '    read local clones informations for all concerned repositories',
        '  u' => '    update local clones of all concerned repositories',
        '  d' => '    delete local clones of all concerned repositories',
    ), '    %s    %s');
    write();
    writeBold('Hosting options');
    writeList(array(
        'w=webhost' => 'the base URL of the web host (default is `https://api.github.com/`)',
        'u=username' => 'the username in the host system',
        't=usertype' => 'the type of user in the host system (`user` or `org` for the default GitHub host)',
        'i=mask' => 'the mask used to build webhost URL ; will be completed with `@webhost@`, `@username@`, `@usertype@` ; use with caution as this script is built to work with the GitHub API',
    ), '    %s    %s');
    write();
    writeBold('Examples');
    write(' - to list all available "CarteBlanche" repositories, run
    ~$ php ap-repos-manager.php -a=l -m="carte-blanche*"

 - to create a clone of all bundles in the current directory, run
    ~$ php ap-repos-manager.php -a=c -m="bundle"
    
The best practice is to begin listing the repositories to be sure your filters are good and then process an action upon them.');
}

set_error_handler("errorHandler");
function errorHandler($errno, $errstr, $errfile, $errline)
{
    write();
    writeBold('!! - '.$errstr);
    writeError("    Error sent in file [$errfile] at line [$errline]\n    For more infos, run:\n        ~$ php path/to/ap-repos-manager.php -h\n");
}

function writeError($string, $status = 1)
{
    fwrite(STDERR, $string."\n");
    exit($status);
}

function write($string = '')
{
    fwrite(STDOUT,$string."\n");
}

function writePrompt($string, $default = null)
{
    fwrite(STDOUT,$string.(!empty($default) ? ' ['.$default.']' : '')." ? ");
}

function writeBold($string)
{
    fwrite(STDOUT, getBold($string)."\n");
}

function getBold($string)
{
    return "\033[1m".$string."\033[0m";
}

function writeTitle()
{
    writeBold('## '.basename(__FILE__).' - '._AP_MANAGER_NAME.' ##');
    write('(v. '.getBold(_AP_MANAGER_VERSION).' - by '._AP_MANAGER_AUTHOR.')');
}

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

function cls()
{
    system("clear");
}

function getInput()
{
    $line = fgets(STDIN);
    return trim($line);
}

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

function execGitCommand($cmd = 'checkout', $relpath = null, $silent = false)
{
    $git_bin = exec('which git');
    $git_cmd = sprintf('%s %s', $git_bin, $cmd);
    return execCommand($git_cmd, $relpath, $silent);
}

function getRepoUrl()
{
    global $username, $usertype, $webhost, $hosturl;
    $url = str_replace('@webhost@', rtrim($webhost, '/'), $hosturl);
    $url = str_replace('@username@', $username, $url);
    if (in_array($usertype, array('user', 'org')) && substr($usertype, -1)!=='s') {
         $usertype = $usertype.'s';
    };
    $url = str_replace('@usertype@', $usertype, $url);
    return $url;
}

function getGitHubInfos($mask = null)
{
    $result = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, getRepoUrl());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    if(false===($content = curl_exec($ch))) {
        trigger_error('Network Error: "'.curl_error($ch).'" - please try again later.', E_USER_ERROR);
    }
    curl_close($ch);
    $data = json_decode($content);
    if (!empty($data) && count($data)) {
        foreach($data as $key=>$repo) {
            if (empty($mask) || (
                !empty($mask) && 0!=preg_match('/'.$mask.'/i', $repo->name)
            )) {
                $result[$repo->name] = $repo;
            }
        }
    }
    return $result;
}

function getLocalRepoInfos($path)
{
    $repo = new StdClass;
    $repo->path = $path;
    // branches
    $branches = execGitCommand('branch', $path, true);
    $branches_list = explode("\n", $branches);
    $repo->branch = array();
    foreach($branches_list as $k=>$branch) {
        $branchname = str_replace(array('*', ' '), '', $branch);
        $repo->branch[] = $branchname;
        if ($branch[0]=='*') {
            $repo->current_branch = $branchname;
        }
    }
    // origins
    $origins = execGitCommand('remote -v', $path, true);
    $origins_list = explode("\n", $origins);
    foreach($origins_list as $k=>$origin) {
        list($trash, $remote_info) = explode("\t", $origin);
        $remote_parts = explode(' ', $remote_info);
        if ($remote_parts[1]==='(fetch)') {
            $repo->origin = $remote_parts[0];
        }
    }
    // last commit infos
    $pretty_format = 'commit-abbrev:%h%nauthor_name:%an%nauthor_email:%ae%ndate:%ai%ntitle:%s%nmessage:%b%n';
    $infos = execGitCommand('log -1 --pretty=format:'.$pretty_format, $path, true);
    $infos_list = explode("\n", $infos);
    $repo->last_commit = array();
    foreach($infos_list as $k=>$infostr) {
        $ok_match = preg_match('/^([a-z-_]+):(.*)$/i', $infostr, $matches);
        if ($ok_match!==0) {
            $last_index = $matches[1];
            $repo->last_commit[$matches[1]] = $matches[2];
        } else {
            if (!isset($last_index)) $last_index = 'info';
            $repo->last_commit[$last_index] .= $infostr;
        }
    }
    return $repo;
/*
var_export($repo);
exit('yo');
*/
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

function runMask($_mask)
{
    global $mask;
    $mask = $_mask;
}

function runAction($type)
{
    global $action;
    switch($type) {
        case 'l': case 'list': $action = 'list'; break;
        case 'r': case 'read': $action = 'read'; break;
        case 'c': case 'create': $action = 'create'; break;
        case 'u': case 'update': $action = 'update'; break;
        case 'd': case 'delete': $action = 'delete'; break;
        case 's': case 'status': $action = 'status'; break;
        default: break;
    }
}

function runUsername($name)
{
    global $username;
    $username = $name;
}

function runUsertype($type)
{
    global $usertype;
    $usertype = $type;
}

function runWebhost($url)
{
    global $webhost;
    $webhost = $url;
}

function runPath($_path)
{
    global $path;
    $path = $_path;
}

function run()
{
    global $action, $mask, $path;
    $data = getGitHubInfos($mask);
/*
var_export($data);
exit('yo');
 */
    switch($action) {
        case 'read':
            writeBold('Reading informations from concerned local repositories');
            if (file_exists($path)) {
                $data = array();
                foreach(scandir($path) as $key=>$subdir) {
                    if (!in_array($subdir, array('.', '..')) && (
                        empty($mask) || (
                            !empty($mask) && 0!=preg_match('/'.$mask.'/i', $subdir)
                    ))) {
                        $data[$subdir] = getLocalRepoInfos(rtrim($path, '/').'/'.$subdir);
                    }
                }
                if (!empty($data) && count($data)) {
                    $list = array();
                    foreach($data as $key=>$repo) {
                        $list[] = '';
                        $list[$repo->path] = 'clone from origin: '.$repo->origin;
                        $list[] = 'on branch '.$repo->current_branch;
                        $list[] = 'last commit '.$repo->last_commit['commit-abbrev'].' by '
                            .$repo->last_commit['author_name']
                            .(!empty($repo->last_commit['author_email']) ? ' <'.$repo->last_commit['author_email'].'>': '')
                            .' at '.$repo->last_commit['date'];
                        $list[] = '"'.$repo->last_commit['title'].'"';
                    }
                    writeList($list);
                } else {
                    write();
                    write('No local repository found');
                }
            } else {
                trigger_error(sprintf('Directory "%s" not found!', $path), E_USER_ERROR);
            }
            write();
            exit(0);
            break;
        case 'list':
            $list = array();
            if (!empty($data) && count($data)) {
                foreach($data as $key=>$repo) {
                    $list[] = '';
                    $list[$repo->full_name] = $repo->clone_url;
                    $list[] = $repo->description;
                    $list[] = (true===$repo->fork ? 'FORK - ' : '')
                        .(!empty($repo->updated_at) ? 'Last update at '.$repo->updated_at : '');
                }
            }
            writeBold('List of all matching repositories');
            writeList($list);
            write();
            exit(0);
            break;
        case 'create':
            writeBold('Creating a clone for each matching repository');
            foreach($data as $key=>$repo) {
                $cmd = sprintf('clone %s %s', $repo->clone_url, $repo->name);
                $relpath = '.'!==$path ? $relpath : null;
                write();
                execGitCommand($cmd, $relpath);
            }
            write();
            exit(0);
            break;
        case 'update':
            writeBold('Updating the clone of each matching repository');
            foreach($data as $key=>$repo) {
                $cmd = 'pull';
                $relpath = '.'!==$path ? rtrim($relpath, '/').'/'.$repo->name : $repo->name;
                if (file_exists($relpath)) {
                    write();
                    execGitCommand($cmd, $relpath);
                }
            }
            write();
            exit(0);
            break;
        case 'status':
            writeBold('Getting clone status of each matching repositories');
            foreach($data as $key=>$repo) {
                $cmd = 'status';
                $relpath = '.'!==$path ? rtrim($relpath, '/').'/'.$repo->name : $repo->name;
                if (file_exists($relpath)) {
                    write();
                    execGitCommand($cmd, $relpath);
                }
            }
            write();
            exit(0);
            break;
        case 'delete':
            writeBold('Deleting the clone of each matching repository');
            write();
            foreach($data as $key=>$repo) {
                if (file_exists(rtrim($path, '/').'/'.$repo->name)) {
                    $cmd = sprintf('rm -rf %s', $repo->name);
                    $relpath = '.'!==$path ? $relpath : null;
                    writePrompt(sprintf('  - delete directory "%s"', $repo->name), 'o/N');
                    $userval = getInput();
                    if (!empty($userval) && 'o'===$userval) {
                        execCommand($cmd, $relpath);
                    } else {
                        write('-- skipped');
                    }
                    write();
                }
            }
            exit(0);
            break;
        default:
            trigger_error(sprintf('Unknwon action "%s"!', $action), E_USER_ERROR);
            break;
    }
}

// arguments
$args = array(
    'h'=>'help',
    'm:'=>'mask',
    'a:'=>'action',
    'u:'=>'username',
    't:'=>'usertype',
    'w:'=>'webhost',
    'p:'=>'path'
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
