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

	/**
	 * 検索フィールド
	 * @param string $fieldName フィールド名
	 * @param array $additional_options 追加オプション
	 * @return string 出力タグ
	 */
	public function searchField($fieldName, $additional_options = [])
	{
		// デフォルトオプション
		$options = [
			'class'=>'form-control',
			'required' => false
		];

		// 追加オプション
		foreach($additional_options as $key => $value)
		{
			$options[$key] = $value;
		}
		
		if(isset($options['label']))
			$options['label'] .= ' :';
		
		return $this->control($fieldName, $options);
	}

	/**
	 * 日付指定リストボックスの出力
	 * @param string $fieldName フィールド名
	 * @param array $additional_options 追加オプション
	 * @return string 出力タグ
	 */
	public function controlDate($fieldName, $additional_options = [])
	{
		// デフォルトオプション
		$options = [
			'type' => 'date',
		];
		
		// 追加オプション
		foreach($additional_options as $key => $value)
		{
			$options[$key] = $value;
		}
		
		if(isset($options['label']))
		{
			if($options['label'] != '～')
				$options['label'] .=  ' :';
		}
		
		return $this->control($fieldName, $options);
	}

	/**
	 * 検索用日付指定リストボックスの出力
	 * @param string $fieldName フィールド名
	 * @param array $additional_options 追加オプション
	 * @return string 出力タグ
	 */
	public function searchDate($fieldName, $additional_options = [])
	{
		// デフォルトオプション
		$options = [
			'type' => 'date',
			'class'=>'form-control',
		];
		
		// 追加オプション
		foreach($additional_options as $key => $value)
		{
			$options[$key] = $value;
		}
		
		if(isset($options['label']))
		{
			if($options['label'] != '～')
				$options['label'] .=  ' :';
		}
		
		return $this->control($fieldName, $options);
	}
	/**
	 * 説明用ブロックの出力
	 * @param string $label ラベル
	 * @param string $content 内容
	 * @param bool $is_bold 太字
	 * @param string $block_class クラス
	 * @return string 出力タグ
	 */
	public static function block($label, $content, $is_bold = false, $block_class = '')
	{
		$content = $is_bold ? '<h5>'.$content.'</h5>' : $content;
		
		$tag = 
			'<div class="form-group '.$block_class.'">'.
			'  <label for="UserRegistNo" class="col col-sm-3 control-label">'.$label.'</label>'.
			'  <div class="col col-sm-9">'.$content.'</div>'.
			'</div>';
		
		return $tag;
	}
}
