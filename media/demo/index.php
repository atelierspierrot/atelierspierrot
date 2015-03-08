<?php

/**
 * show errors at least initially:
 * -    `E_ALL` => for hard dev
 * -    `E_ALL & ~E_STRICT` => for hard dev in PHP5.4 avoiding strict warnings
 * -    `E_ALL & ~E_NOTICE & ~E_STRICT` => classic setting
 */
@ini_set('display_errors','1'); @error_reporting(E_ALL);
//@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_STRICT);
//@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

// set a default timezone to avoid PHP5 warnings
$dtmz = @date_default_timezone_get();
date_default_timezone_set($dtmz?:'Europe/Paris');

// debug ?
define('DEBUG', 
//true
false
);

// the media path
define('MEDIA_DIR', realpath(__DIR__.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR);

// media looper
function mediaLooper(callable $validator, callable $renderer, $path = MEDIA_DIR)
{
    static $media_dir = array();
    if (!array_key_exists($path, $media_dir)) {
        $media_dir[$path] = new DirectoryIterator(MEDIA_DIR);
    }
    $files = array();
    foreach ($media_dir[$path] as $fileinfo) {
        if (
            !$fileinfo->isDot() && !$fileinfo->isDir() && substr($fileinfo->getFilename(), 0, 1)!=='.' &&
            call_user_func($validator, $fileinfo)===true
        ) {
            $files[str_replace('.'.$fileinfo->getExtension(), '', $fileinfo->getFilename())] = clone $fileinfo;
        }
    }
    ksort($files, SORT_NATURAL | SORT_FLAG_CASE);
    foreach ($files as $name=>$fileinfo) {
        if (DEBUG) {
            var_dump($fileinfo->getFilename());
        }
        echo call_user_func($renderer, $fileinfo);
    }
}

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Media tests | atelierspierrot</title>
    <link rel="apple-touch-icon" sizes="57x57" href="../favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="../favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../favicons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="../favicons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="../favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="../favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="msapplication-TileImage" content="../favicons/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
<style>
html {
  position: relative;
  min-height: 100%;
}
body {
  margin-bottom: 100px;
}
.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 100px;
  background-color: #f5f5f5;
}
.container {
  width: auto;
  max-width: 820px;
  padding: 0 15px;
}
.container .text-muted {
  margin: 20px 0;
}
</style>
</head>
<body>
    <div class="container">

        <div class="page-header">
            <h1>
                Test of images <small>from <a href="http://github.com/atelierspierrot/atelierspierrot">atelierspierrot/atelierspierrot</a></small>
            </h1>
        </div>

        <h2>Test of icons</h2>
        
        <p>The header of this page embeds favicons generated with <a href="http://realfavicongenerator.net/faq">RealFaviconGenerator.net</a>.</p>
        <pre>
&lt;link rel="apple-touch-icon" sizes="57x57" href="../favicons/apple-touch-icon-57x57.png"&gt;
&lt;link rel="apple-touch-icon" sizes="60x60" href="../favicons/apple-touch-icon-60x60.png"&gt;
&lt;link rel="apple-touch-icon" sizes="72x72" href="../favicons/apple-touch-icon-72x72.png"&gt;
&lt;link rel="apple-touch-icon" sizes="76x76" href="../favicons/apple-touch-icon-76x76.png"&gt;
&lt;link rel="apple-touch-icon" sizes="114x114" href="../favicons/apple-touch-icon-114x114.png"&gt;
&lt;link rel="apple-touch-icon" sizes="120x120" href="../favicons/apple-touch-icon-120x120.png"&gt;
&lt;link rel="apple-touch-icon" sizes="144x144" href="../favicons/apple-touch-icon-144x144.png"&gt;
&lt;link rel="apple-touch-icon" sizes="152x152" href="../favicons/apple-touch-icon-152x152.png"&gt;
&lt;link rel="apple-touch-icon" sizes="180x180" href="../favicons/apple-touch-icon-180x180.png"&gt;
&lt;link rel="icon" type="image/png" href="../favicons/favicon-32x32.png" sizes="32x32"&gt;
&lt;link rel="icon" type="image/png" href="../favicons/android-chrome-192x192.png" sizes="192x192"&gt;
&lt;link rel="icon" type="image/png" href="../favicons/favicon-96x96.png" sizes="96x96"&gt;
&lt;link rel="icon" type="image/png" href="../favicons/favicon-16x16.png" sizes="16x16"&gt;
&lt;link rel="manifest" href="../favicons/manifest.json"&gt;
&lt;meta name="msapplication-TileColor" content="#00aba9"&gt;
&lt;meta name="msapplication-TileImage" content="../favicons/mstile-144x144.png"&gt;
&lt;meta name="theme-color" content="#ffffff"&gt;
        </pre>

        <hr class="clearfix">
        <h2>Test of logos <small>at <code>media/*-logo*.png</code></small></h2>
        <pre>&lt;img title="../[filename]" src="../[filename]" alt="[filename]"&gt;</pre>
        <table class="table">
<?php
    mediaLooper(
        function($f) { return (bool) ($f->getExtension()=='png' && preg_match('/.*-logo.*/', $f->getFilename())>0); },
        function($f) {
            return <<<CONTENT
        <tr>
            <td><span class="label label-default">{$f->getFilename()}</span></td>
            <td><img title="../{$f->getFilename()}" src="../{$f->getFilename()}" alt="{$f->getFilename()}"></td>
        </tr>
CONTENT;
        }
    );
?>
        </table>

        <hr class="clearfix">
        <h2>Test of SVG images <small>at <code>media/*.svg</code></small></h2>
        <pre>&lt;img title="../[filename]" src="../[filename]" alt="[filename]"&gt;
&lt;img title="../[filename]" src="../[filename]" alt="[filename]" width="200"&gt;</pre>
        <table class="table">
<?php
    mediaLooper(
        function($f) { return (bool) ($f->getExtension()=='svg'); },
        function($f) {
            return <<<CONTENT
        <tr>
            <td><span class="label label-default">{$f->getFilename()}</span></td>
            <td>
                <p><img title="../{$f->getFilename()}" src="../{$f->getFilename()}" alt="{$f->getFilename()}"></p>
                <p><img title="../{$f->getFilename()}" src="../{$f->getFilename()}" alt="{$f->getFilename()}" width="200"></p>
            </td>
        </tr>
CONTENT;
        }
    );
?>
        </table>

        <hr class="clearfix">
    </div>
    <footer class="footer">
      <div class="container">
        <p class="text-muted text-center">
            Logos are licensed under <a href="https://creativecommons.org/licenses/by-nd/3.0/legalcode">Creative Commons BY-ND 3.0</a>
            <br>
            SVG images are licensed under <a href="https://creativecommons.org/licenses/by-sa/3.0/legalcode">Creative Commons BY-SA 3.0</a>
            <br>
            - <a href="http://www.ateliers-pierrot.fr/">Les Ateliers Pierrot</a> -
        </p>
      </div>
    </footer>
</body>
</html>