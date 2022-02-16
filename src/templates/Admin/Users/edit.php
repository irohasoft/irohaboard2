<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

$this->Form->setTemplates(Configure::read('bootstrap_form_template'));
$this->Form->setConfig('errorClass', 'form-control form-error');
?>
<?= $this->element('admin_menu');?>
<?= $this->Html->css( 'select2.min.css');?>
<?= $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(['block' => true]); ?>
	$(function (e) {
		$('#groups-ids').select2({placeholder:   "<?= __('所属するグループを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		$('#courses-ids').select2({placeholder:   "<?= __('受講するコースを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		// パスワードの自動復元を防止
		setTimeout('$("#new-password").val("");', 500);
	});
<?php $this->Html->scriptEnd(); ?>
<div class="admin-users-edit">
<?= $this->Html->link(__('<< 戻る'), ['action' => 'index'])?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= $this->isEditPage() ? __('編集') :  __('新規ユーザ'); ?>
		</div>
		<div class="panel-body">
			<?php
			echo $this->Form->create($user, ['class' => 'form-horizontal']);
			
			$password_label = $this->isEditPage() ? __('新しいパスワード') : __('パスワード');
			
			echo $this->Form->control('username',		['label' => __('ログインID')]);
			echo $this->Form->control('new_password',	['label' => $password_label, 'type' => 'password', 'autocomplete' => 'new-password']);
			echo $this->Form->control('name',			['label' => __('氏名')]);
			
			// root アカウント、もしくは admin 権限以外の場合、権限変更を許可しない
			$disabled = (($username == 'root') || ($this->readAuthUser('role') != 'admin'));
			
			echo $this->Form->controlRadio('role',	['label' => __('権限'), 'options' => Configure::read('user_role')]);

			echo $this->Form->control('email',				['label' => __('メールアドレス')]);
			echo $this->Form->control('groups._ids',				['label' => __('所属グループ')]);
			echo $this->Form->control('courses._ids',				['label' => __('受講コース')]);
			echo $this->Form->control('comment',				['label' => __('備考')]);
			echo Configure::read('form_submit_before')
				.$this->Form->submit(__('保存'), Configure::read('form_submit_defaults'))
				.Configure::read('form_submit_after');
			echo $this->Form->end();
			
			// 編集の場合のみ、学習履歴削除ボタンを表示
			if($this->isEditPage())
			{
				echo $this->Form->postLink(__('学習履歴を削除'),
					['action' => 'clear', $user->id],
					['class' => 'btn btn-default pull-right btn-clear', 'confirm' => __('学習履歴を削除してもよろしいですか？')]);
			}
			?>
		</div>
	</div>
</div>
