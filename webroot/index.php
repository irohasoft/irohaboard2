<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

// ロードバランサー対応
if(isset($_SERVER['HTTP_X_FORWARDED_HOST']))
{
	// 1.2.3.4, 1.2.3.4 形式をカンマで分解
	$host_list = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
	
	$_SERVER['HTTP_HOST'] = trim($host_list[count($host_list) - 1]); // 先頭のIPアドレスを設定
	
	if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']))
	{
		if($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
		{
			$_SERVER['HTTPS'] = 'on'; // HTTPSアクセスを強制
		}
	}
}

// Check platform requirements
require dirname(__DIR__) . '/config/requirements.php';

// For built-in server
if (PHP_SAPI === 'cli-server') {
    $_SERVER['PHP_SELF'] = '/' . basename(__FILE__);

    $url = parse_url(urldecode($_SERVER['REQUEST_URI']));
    $file = __DIR__ . $url['path'];
    if (strpos($url['path'], '..') === false && strpos($url['path'], '.') !== false && is_file($file)) {
        return false;
    }
}
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;
use Cake\Http\Server;

// Bind your application to the server.
$server = new Server(new Application(dirname(__DIR__) . '/config'));

// Run the request/response through the application and emit the response.
$server->emit($server->run());
