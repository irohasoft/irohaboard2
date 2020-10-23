<?php use Cake\Core\Configure;?>
<?php $this->Form->setTemplates(Configure::read('bootstrap_form_template'));?>
<div class="users-login">
	<div class="panel panel-info form-signin">
		<div class="panel-heading">
			<?php echo __('受講者ログイン')?>
		</div>
		<div class="panel-body">
			<div class="text-right"><a href="<?= $this->Url->build(['action' => 'login', 'admin' => true]) ?>"><?php echo __('管理者ログインへ')?></a></div>
			<?= $this->Form->create(null, Configure::read('form_defaults')) ?>
			<?= $this->Form->control('username', ['label' => __('ログインID')]) ?>
			<?= $this->Form->control('password', ['label' => __('パスワード')]) ?>
			<div class="form-group">
				<label for="remember_me" class="col col-sm-3 control-label"></label>
				<div class="col col-sm-9">
					<input type="checkbox" name="remember_me" checked="checked" value="1" id="remember_me"><?php echo __('ログイン状態を保持')?>
				</div>
			</div>
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="ログイン">
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
