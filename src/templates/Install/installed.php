<?php
use Cake\Routing\Router;
?>
<div class="install-installed">
	<div class="panel panel-info">
		<div class="panel-heading">
			<?= APP_NAME; ?> Installer
		</div>
		<div class="panel-body">
			<p class="msg">既にインストールされています。</p>
		</div>
		<div class="panel-footer text-center">
			<button class="btn btn-primary" onclick="location.href='<?= Router::url(['controller' => 'users', 'action' => 'login', 'prefix' => 'Admin']) ?>'">管理者ログイン画面へ</button>
		</div>
	</div>
</div>
