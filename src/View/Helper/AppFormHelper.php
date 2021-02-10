<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

namespace App\View\Helper;

use Cake\View\Helper\FormHelper;

/**
 * Application HtmlHelper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppFormHelper extends FormHelper
{
	/**
	 * 説明付きテキストボックス出力
	 * @param string $fieldName フィールド名
	 * @param array $options input 用のオプション
	 * @param string $exp 説明
	 * @return string 出力タグ
	 */
	public function controlExp($fieldName, $options = [], $exp)
	{
		$options['templateVars'] = ['after' => __($exp)];
		
		return $this->control($fieldName, $options);
	}

	/**
	 * ラジオボタン出力
	 * @param string $fieldName フィールド名
	 * @param array $options input 用のオプション
	 * @param string $exp 説明
	 * @return string 出力タグ
	 */
	public function controlRadio($fieldName, $options = [], $exp = '')
	{
		$options['type'] = 'radio';
		$options['templateVars'] = ['after' => __($exp)];
		$options['hiddenField'] = false;

		if(isset($options['default']))
			$options['default'] = $options['default'];
		
		return $this->control($fieldName, $options);
	}
}
