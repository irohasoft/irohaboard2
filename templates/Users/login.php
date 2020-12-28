<?php use Cake\Core\Configure;?>
<?php $this->Form->setTemplates(Configure::read('bootstrap_login_template'));?>
<div class="users-login">
	<div class="panel panel-info form-signin">
		<div class="panel-heading">
			<?php echo __('受講者ログイン')?>
		</div>
		<div class="panel-body">
			<div class="text-right"><?= $this->Html->link(__('管理者ログインへ'), '/admin/users/login')?></div>
			<?= $this->Form->create(null) ?>
			<?= $this->Form->control('username', ['label' => __('ログインID')]) ?>
			<?= $this->Form->control('password', ['label' => __('パスワード')]) ?>
			<?= $this->Form->control('remember_me', ['type' => 'checkbox', 'label'=> __('ログイン状態を保持'), 'default' => 1]);?>
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="ログイン">
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
