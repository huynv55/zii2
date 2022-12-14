<?php
require __DIR__."/class_finder.php";

use App\Responses\ResponseHTML;
use App\Responses\ResponseJSON;
use App\Responses\ResponseRedirect;
use App\Services\CookieService;
use App\Services\SessionService;
use Psr\Log\LoggerInterface;
use App\Requests\Request;

/**
 * get instance class application
 *
 * @return App\Application
 */
function app() : App\Application
{
    return $GLOBALS[APPLICATION_NAME];
}

/**
 * get instance container
 *
 * @return DI\Container
 */
function container() : DI\Container
{
    return app()->getContainer();
}

/**
 * get instance session
 *
 * @return SessionService
 */
function session() : SessionService
{
    return container()->get(SessionService::class);
}

/**
 * get instance cookie
 *
 * @return CookieService
 */
function cookie() : CookieService
{
    return container()->get(CookieService::class);
}

/**
 * get view render
 *
 * @return ResponseHTML
 */
function view() : ResponseHTML
{
    return container()->get(ResponseHTML::class);
}

/**
 * get json response
 *
 * @return ResponseJSON
 */
function responseJson() : ResponseJSON
{
    return container()->get(ResponseJSON::class);
}

/**
 * get instance redirect
 *
 * @return ResponseRedirect
 */
function redirect() : ResponseRedirect
{
    return container()->get(ResponseRedirect::class);
}

/**
 * get instance request
 *
 * @return Request
 */
function request() : Request
{
    return container()->get(Request::class);
}

/**
 * get instance log
 *
 * @return LoggerInterface
 */
function appLog() : LoggerInterface
{
    return container()->get(LoggerInterface::class);
}

/**
 * get environment variable value
 *
 * @param string $env
 * @param mixed $default
 * @return mixed
 */
function env(string $env, mixed $default = null) : mixed
{
    if(!empty(getenv($env))) {
        return getenv($env);
    }
    if(!empty($_ENV[$env])) {
        return $_ENV[$env];
    }
    if(!empty($_SERVER[$env])) {
        return $_SERVER[$env];
    }
    return $default;
}

function isEnvProduction() : bool
{
    $settings = container()->get('settings');
    return $settings['app']['env'] == 'prod';
}

function camelCase($str, array $noStrip = [])
{
    // non-alpha and non-numeric characters become spaces
    $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
    $str = trim($str);
    // uppercase the first character of each word
    $str = ucwords($str);
    $str = str_replace(" ", "", $str);
    $str = lcfirst($str);
    return $str;
}

function from_camel_case($input) {
    $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
    preg_match_all($pattern, $input, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ?
        strtolower($match) :
        lcfirst($match);
    }
    return implode('_', $ret);
}

function createSlug(string $string) : string
{
    $string = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$string);
    $table = array(
        '??'=>'S', '??'=>'s', '??'=>'Dj', '??'=>'dj', '??'=>'Z', '??'=>'z', '??'=>'C', '??'=>'c', '??'=>'C', '??'=>'c',
        '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'C', '??'=>'E', '??'=>'E',
        '??'=>'E', '??'=>'E', '??'=>'I', '??'=>'I', '??'=>'I', '??'=>'I', '??'=>'N', '??'=>'O', '??'=>'O', '??'=>'O',
        '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'U', '??'=>'U', '??'=>'U', '??'=>'U', '??'=>'Y', '??'=>'B', '??'=>'Ss',
        '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'c', '??'=>'e', '??'=>'e',
        '??'=>'e', '??'=>'e', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'o', '??'=>'n', '??'=>'o', '??'=>'o',
        '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'y', '??'=>'y', '??'=>'b',
        '??'=>'y', '??'=>'R', '??'=>'r', '/' => '-', ' ' => '-'
    );
    // -- Remove duplicated spaces
    $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
    // -- Returns the slug
    return strtolower(strtr($stripped, $table));
}

// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}

/**
* This function creates recursive directories if it doesn't already exist
*
* @param String  The path that should be created
* 
* @return  void
*/
function create_dirs($path)
{
    if (!is_dir($path))
    {
        $directory_path = "";
        $directories = explode(DIRECTORY_SEPARATOR,$path);
        array_pop($directories);
        foreach($directories as $directory)
        {
            $directory_path .= $directory.DIRECTORY_SEPARATOR;
            if (!is_dir($directory_path))
            {
                mkdir($directory_path);
                chmod($directory_path, 0777);
            }
        }
    }
}


/**
* Unzip the source_file in the destination dir
*
* @param   string      The path to the ZIP-file.
* @param   string      The path where the zipfile should be unpacked, if false the directory of the zip-file is used
* @param   boolean     Indicates if the files will be unpacked in a directory with the name of the zip-file (true) or not (false) (only if the destination directory is set to false!)
* 
* @return  boolean     Succesful or not
*/
function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true)
{
    $zip = new ZipArchive;
    $res = $zip->open($src_file);
    if ($res)
    {
        $splitter = ($create_zip_name_dir === true) ? "." : DIRECTORY_SEPARATOR;
        if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter)).DIRECTORY_SEPARATOR;
        // Create the directories to the destination dir if they don't already exist
        create_dirs($dest_dir);
        $zip->extractTo($dest_dir);
        $zip->close();
        return true;
    } else {
        return false;
    }
}

/**
 * Creates a temporary file with a unique name in read-write (w+) mode and returns path to file temporary.
 *
 * @param mixed $content
 * @return string
 */
function writeTemporaryFile($content) : string
{
    $tmpfname = tempnam(sys_get_temp_dir(), 'tmp');
    file_put_contents($tmpfname, $content);
    register_shutdown_function(function() use($tmpfname) {
        unlink($tmpfname);
    });
    return $tmpfname;
}

?>