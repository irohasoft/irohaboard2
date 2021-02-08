<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Admin;

use Cake\Controller\Controller;
use App\Controller\AppController;
use App\Vendor\Utils;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AdminController extends AppController
{
    // custom 2020.10.7
    // ページネーションの件数
    public $paginate = [
        'limit' => 10,
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
     /*
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        // custom 2020.06.07
        // 認証結果を確認し、サイトのロックを行うために次の行を追加します
        $this->loadComponent('Authentication.Authentication');
        
        $this->set('loginedUser', $this->getRequest()->getSession()->read('Auth'));
    }
    */


	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		
		// role が admin, manager, editor, teacher以外の場合、強制ログアウトする
		if($this->isLogined())
		{
			if(
				($this->readAuthUser('role')!='admin')&&
				($this->readAuthUser('role')!='manager')&&
				($this->readAuthUser('role')!='editor')&&
				($this->readAuthUser('role')!='teacher')
			)
			{
				if($this->readCookie('Auth'))
					$this->deleteCookie('Auth');
				
				$this->Flash->error(__('管理画面へのアクセス権限がありません'));
				$this->Authentication->logout();
				return;
			}
		}
	}
}
