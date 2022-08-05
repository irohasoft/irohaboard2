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
			<p><?= APP_NAME; ?> のインストール及び管理者アカウントの作成を行います。</p>
			<li>作成する管理者アカウントのログインIDとパスワードを入力し、「インストール」ボタンをクリックしてください。</li>
			<li>管理者ログインIDは4文字以上32文字以内で、英数字のみを使用してください。</li>
			<li>パスワードは4文字以上32文字以内で、英数字のみを使用してください。</li>
		</div>
		<div class="panel-body">
			<?php
				echo $this->Form->create(null, ['class' => 'form-horizontal']);
				
				echo $this->Form->control('username', [
					'label' => __('管理者ログインID'),
					'type' => 'username',
					'autocomplete' => 'new-password'
				]);
				
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
