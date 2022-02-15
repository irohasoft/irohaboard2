<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	  Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link		  https://cakephp.org CakePHP(tm) Project
 * @since		  0.10.0
 * @license 	  https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?= $this->Html->charset() ?>
	<title><?= h($this->getRequest()->getSession()->read('Setting.title')); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="application-name" content="<?= APP_NAME; ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<?php
		// 管理画面フラグ（ログイン画面は例外とする）
		$is_admin_page = (($this->request->getParam('prefix')=='Admin')&&($this->request->getParam('action')!='login'));
		
		// 受講者向け画面及び、管理システムのログイン画面のみ viewport を設定（スマートフォン対応）
		if(!$is_admin_page)
			echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
	?>
	
	<?= $this->Html->meta('icon') ?>
	
	<!--custom 2020.10.08-->
	<?= $this->Html->css('jquery-ui') ?>
	<?= $this->Html->css('bootstrap.min') ?>
	<?= $this->Html->css('common.css?20201209') ?>
	
	<?php
		// 管理画面用CSS
		if($is_admin_page)
			echo $this->Html->css('admin.css?20200701');
	?>

	<?= $this->Html->script('jquery-1.9.1.min') ?>
	<?= $this->Html->script('jquery-ui-1.9.2.min.js') ?>
	<?= $this->Html->script('bootstrap.min.js') ?>
	<?= $this->Html->script('moment.js') ?>
	<?= $this->Html->script('common.js?20200701') ?>

	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
	<?= $this->fetch('script') ?>
	<?= $this->fetch('css-embedded') ?>
	<?= $this->fetch('script-embedded') ?>
	<?php
	$logoutURL	= 'users/logout';
	$color		= $this->getRequest()->getSession()->read('Setting.color');
	$title		= $this->getRequest()->getSession()->read('Setting.title');
	$copyright	= $this->getRequest()->getSession()->read('Setting.copyright');
	?>
	<style>
		.ib-theme-color
		{
			background-color	: <?= h($color); ?>;
			color				: white;
		}
		
		.ib-logo a
		{
			color				: white;
			text-decoration		: none;
		}
	</style>
</head>
<body>
	<div class="header ib-theme-color">
		<div class="ib-logo ib-left">
			<?= $this->Html->link($title, '/')?>
		</div>
		<?php if($this->isLogined()) {?>
		<div class="ib-navi-item ib-right"><?= $this->Html->link(__('ログアウト'), ['controller' => 'users', 'action' => 'logout']); ?></div>
		<div class="ib-navi-sepa ib-right"></div>
		<div class="ib-navi-item ib-right"><?= $this->Html->link(__('設定'), ['controller' => 'users', 'action' => 'setting']); ?></div>
		<div class="ib-navi-sepa ib-right"></div>
		<div class="ib-navi-item ib-right"><?= __('ようこそ').' '.h($this->readAuthUser("name")).' '.__('さん'); ?></div>
		<?php }?>
	</div>

	<main class="main">
		<div class="container">
			<div id="content">
			<?= $this->Flash->render() ?>
			<?= $this->fetch('content') ?>
			</div>
		</div>
	</main>

	<div class="ib-theme-color text-center">
		<?= h($copyright); ?>
	</div>
	
	<div class="irohasoft">
		Powered by <a href="http://irohaboard.irohasoft.jp/">iroha Board</a>
	</div>
</body>
</html>
