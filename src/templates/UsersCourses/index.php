<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersCourse[]|\Cake\Collection\CollectionInterface $usersCourses
 */
use Cake\Routing\Router;
use App\Vendor\Utils;

?>
<div class="users-courses-index">
	<div class="panel panel-success">
		<div class="panel-heading"><?= __('お知らせ'); ?></div>
		<div class="panel-body">
			<?php if($info!=""){?>
			<div class="well">
				<?php
				$info = $this->Text->autoLinkUrls($info, [ 'target' => '_blank']);
				$info = nl2br($info);
				echo $info;
				?>
			</div>
			<?php }?>
			
			<?php if($infos->count() > 0){?>
			<table cellpadding="0" cellspacing="0">
            <tbody>
			<?php foreach ($infos as $info): ?>
                <tr>
				<td width="100" valign="top"><?= h(Utils::getYMD($info['created'])); ?></td>
				<td><?= $this->Html->link($info['title'], ['controller' => 'infos', 'action' => 'view', $info['id']]); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
			<div class="text-right"><?= $this->Html->link(__('一覧を表示'), ['controller' => 'infos', 'action' => 'index']); ?></div>
			<?php }?>
			<?= $no_info;?>
    </div>
	</div>
	<div class="panel panel-info">
	<div class="panel-heading"><?= __('コース一覧'); ?></div>
	<div class="panel-body">
		<ul class="list-group">
		<?php foreach ($courses as $course): ?>
		<?php //debug($course)?>
			<a href="<?= Router::url(['controller' => 'contents', 'action' => 'index', $course['id']]);?>" class="list-group-item">
				<?php if($course['left_cnt']!=0){?>
				<button type="button" class="btn btn-danger btn-rest"><?= __('残り')?> <span class="badge"><?= h($course['left_cnt']); ?></span></button>
				<?php }?>
				<h4 class="list-group-item-heading"><?= h($course['title']);?></h4>
				<p class="list-group-item-text">
					<span><?= __('学習開始日').': '.Utils::getYMD($course['first_date']); ?></span>
					<span><?= __('最終学習日').': '.Utils::getYMD($course['last_date']); ?></span>
				</p>
			</a>
		<?php endforeach; ?>
		<?= $no_record;?>
        </ul>
	</div>
    </div>
</div>
