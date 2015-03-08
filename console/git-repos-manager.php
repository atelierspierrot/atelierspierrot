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
 * console/git-repos-manager
 * 
 * Create/Read/Update/Delete some/all AteliersPierrot GitHub clones
 * 
 * For more infos run:
 * 
 *     ~$ php path/to/git-repos-manager -h
 * 
 */
@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

define('_AP_SCRIPT_VERSION', '1.0.0');
define('_AP_SCRIPT_NAME', 'GIT repositories manager for GitHub users');
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
    write();
    writeBold('Presentation');
    write('    Shell script to manage a local collection of GIT repositories from a webhost. Allowing to list distant repositories, create the collection clones, read the local collection informations, update the collection clones, delete the local clones and get the GIT status for each of them. You can specify a PCRE mask to filter working repositories list and define the local working path.');
    write('    (cURL and GIT binaries are required)');
    write();
    writeBold('Usage');
    write('    ~$ '.getBold('php '._AP_SCRIPT_FILENAME.' -[option[=value]]'));
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
    ~$ php '._AP_SCRIPT_FILENAME.' -a=l -m="carte-blanche*"

 - to create a clone of all bundles in the current directory, run
    ~$ php '._AP_SCRIPT_FILENAME.' -a=c -m="bundle"
    
The best practice is to begin listing the repositories to be sure your filters are good and then process an action upon them.');
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
    $repo->branch = getGitBranches($path);
    $repo->current_branch = getGitCurrentBranch($path);

    // origins
    $origins = execGitCommand('remote -v', $path, true);
    $origins_list = explode("\n", $origins);
    foreach($origins_list as $k=>$origin) {
        if (!empty($origin)) {
            list($trash, $remote_info) = explode("\t", $origin);
            $remote_parts = explode(' ', $remote_info);
            if (count($remote_parts)>1 && isset($remote_parts[1]) && $remote_parts[1]==='(fetch)') {
                $repo->origin = $remote_parts[0];
            }
        }
    }
    // last commit infos
    $commits = getCommitsHistory($path, '-1');
    if (isset($commits[0])) {
        $repo->last_commit = $commits[0];
    } else {
        $repo->last_commit = null;
    }
    // tags list
    $tags = getGitTags($path);
    if (!empty($tags)) {
        $repo->tags = $tags;
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
        default: $action = $type; break;
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
            writeBold(
                sprintf('Reading informations from concerned local repositories in "%s"', realpath($path))
            );
            if (file_exists($path)) {
                $data = array();
                $subdirs = scandir($path);
                usort($subdirs, 'strcasecmp');
                foreach($subdirs as $key=>$subdir) {
                    $realpath = realpath(slashPath($path).$subdir);
                    if (!in_array($subdir, array('.', '..')) && is_dir($realpath) &&
                        isGitRepository($realpath) && (
                        empty($mask) || (
                            !empty($mask) && 0!=preg_match('/'.$mask.'/i', $subdir)
                    ))) {
                        $data[$subdir] = getLocalRepoInfos($realpath);
                    }
                }
                if (!empty($data) && count($data)) {
                    $list = array();
                    foreach($data as $key=>$repo) {
                        $list[] = '';
                        $list[str_replace(realpath($path), '', $repo->path)] = 'clone from origin: '.$repo->origin;
                        $list[] = 'on branch '.$repo->current_branch;
                        $list[] = 'last commit '.$repo->last_commit['commit-abbrev'].' by '
                            .$repo->last_commit['author_name']
                            .(!empty($repo->last_commit['author_email']) ? ' <'.$repo->last_commit['author_email'].'>': '')
                            .' at '.$repo->last_commit['date'];
                        $list[] = '"'.$repo->last_commit['title'].'"';
                        if (!empty($repo->tags)) {
                            $list[] = 'tags list: '.implode(' ', $repo->tags);
                        }
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
                $relpath = '.'!==$path ? realpath($path) : null;
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
                $relpath = '.'!==$path ? realpath(slashPath($path).$repo->name) : $repo->name;
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
            foreach ($data as $key=>$repo) {
                $cmd = 'status';
                $relpath = '.'!==$path ? realpath(slashPath($path).$repo->name) : $repo->name;
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
                    $relpath = '.'!==$path ? realpath($path) : null;
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

// -------------------
// script execution
// -------------------

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
