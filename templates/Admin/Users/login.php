<?php use Cake\Core\Configure;?>
<?php $this->Form->setTemplates(Configure::read('bootstrap_form_template'));?>
<div class="admin-users-login">
	<div class="panel panel-default form-signin">
		<div class="panel-heading">
			<?= __('管理者ログイン')?>
		</div>
		<div class="panel-body">
			<div class="text-right"><?= $this->Html->link(__('受講者ログインへ'), '/users/login')?>
			<?= $this->Form->create(null, Configure::read('form_defaults')) ?>
			<?= $this->Form->control('username', ['label' => __('ログインID')]) ?>
			<?= $this->Form->control('password', ['label' => __('パスワード')]) ?>
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="ログイン">
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
