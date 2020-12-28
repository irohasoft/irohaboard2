<?php
use Cake\Core\Configure;

$this->Form->setTemplates(Configure::read('bootstrap_form_template'));
?>
<div class="users-setting">
	<div class="breadcrumb">
	<?php
	$this->Breadcrumbs->add('HOME', [
		'controller' => 'users_courses',
		'action' => 'index'
	]);
	echo $this->Breadcrumbs->render(['class' => 'ib-breadcrumbs'], ['separator' => ' / ']);
	?>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= __('設定')?>
		</div>
		<div class="panel-body">
			<?php
				echo $this->Form->create(null, ['class' => 'form-horizontal']);
				echo $this->Form->control('User.new_password', [
					'label' => __('新しいパスワード'),
					'type' => 'password',
					'autocomplete' => 'new-password'
				]);
				
				echo $this->Form->control('User.new_password2', [
					'label' => __('新しいパスワード (確認用)'),
					'type' => 'password',
					'autocomplete' => 'new-password'
				]);
				
				echo $this->Form->button(__('保存'), Configure::read('form_submit_defaults'));
				echo $this->Form->end();
			?>
		</div>
	</div>
</div>
