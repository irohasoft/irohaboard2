<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

$this->Form->setTemplates(Configure::read('bootstrap_form_template'));
?>
<div class="install-index">
	<div class="panel panel-info">
		<div class="panel-heading">
			<?= APP_NAME; ?> Installer
		</div>
		<div class="panel-body">
			<p class="msg"><?= APP_NAME; ?> のインストール及び管理者アカウントの作成を行います。</p>
			<p class="msg">作成する管理者アカウント(root)のパスワードを入力し、「インストール」ボタンをクリックしてください。</p>
			<p class="msg">パスワードは4文字以上32文字以内で、英数字のみを使用してください。</p>
		</div>
		<div class="panel-body">
			<?php
				echo $this->Form->create(null, ['class' => 'form-horizontal']);
				echo $this->Form->control('password', [
					'label' => __('新しいパスワード'),
					'type' => 'password',
					'autocomplete' => 'new-password'
				]);
				
				echo $this->Form->control('password2', [
					'label' => __('新しいパスワード (確認用)'),
					'type' => 'password',
					'autocomplete' => 'new-password'
				]);
				
				echo $this->Form->button(__('インストール'), Configure::read('form_submit_defaults'));
				echo $this->Form->end();
			?>
		</div>
	</div>
</div>
