<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Info $info
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;
?>
<div class="infos-view">
	<div class="breadcrumb">
	<?php
	$this->Breadcrumbs->add('HOME', [
		'controller' => 'users_courses',
		'action' => 'index'
	]);

	$this->Breadcrumbs->add(__('お知らせ一覧'), [
		'controller' => 'infos',
		'action' => 'index'
	]);

	echo $this->Breadcrumbs->render(['class' => 'ib-breadcrumbs'], ['separator' => ' / ']);
	
	$title = h($info->title);
	$date  = h(Utils::getYMD($info->created));
	$body  = $info->body;
	$body  = $this->Text->autoLinkUrls($body, [ 'target' => '_blank']);
	$body  = nl2br($body);
	?>
	</div>

	<div class="panel panel-success">
		<div class="panel-heading"><?= $title; ?></div>
		<div class="panel-body">
			<div class="text-right"><?= $date; ?></div>
			<?= $body; ?>
		</div>
	</div>
</div>
