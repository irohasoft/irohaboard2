<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

$this->Form->setTemplates(Configure::read('bootstrap_form_template'));
?>
<?= $this->element('admin_menu');?>
<?= $this->Html->css( 'select2.min.css');?>
<?= $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(['block' => true]); ?>
	$(function (e) {
		$('#groups-ids').select2({placeholder:   "<?= __('所属するグループを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
		$('#courses-ids').select2({placeholder:   "<?= __('受講するコースを選択して下さい。(複数選択可)')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
	});
<?php $this->Html->scriptEnd(); ?>
<div class="admin-infos-edit">
<?= $this->Html->link(__('<< 戻る'), ['action' => 'index'])?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= ($this->request->getParam('action') == 'edit') ? __('編集') :  __('新規お知らせ'); ?>
		</div>
		<div class="panel-body">
			<?php
			echo $this->Form->create($user, ['class' => 'form-horizontal']);
			echo $this->Form->control('username',		['label' => __('ログインID'), 'required' => true]);
			echo $this->Form->control('password',		['label' => __('新しいパスワード'), 'required' => true]);
			echo $this->Form->control('name',			['label' => __('氏名'), 'required' => true]);
			echo $this->Form->control('role',			['label' => __('権限'), 'required' => true, 
				'options' => Configure::read('user_role'), 'type' => 'radio', 'hiddenField' => false]);
			echo $this->Form->control('email',			['label' => __('メールアドレス'), 'required' => false]);
			echo $this->Form->control('groups._ids',	['options' => $groups, 'label' => __('対象グループ')]);
			echo $this->Form->control('courses._ids',	['options' => $courses, 'label' => __('受講コース')]);
			echo $this->Form->button(__('保存'), Configure::read('form_submit_defaults'));
			echo $this->Form->end();
				?>
		</div>
	</div>
</div>
