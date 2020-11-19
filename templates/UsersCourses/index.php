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
		<div class="panel-heading"><?php echo __('お知らせ'); ?></div>
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
			
			<?php if(count($infos) > 0){?>
			<table cellpadding="0" cellspacing="0">
            <tbody>
			<?php foreach ($infos as $info): ?>
                <tr>
				<td width="100" valign="top"><?php echo h(Utils::getYMD($info['created'])); ?></td>
				<td><?php echo $this->Html->link($info['title'], ['controller' => 'infos', 'action' => 'view', $info['id']]); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
			<div class="text-right"><?php echo $this->Html->link(__('一覧を表示'), ['controller' => 'infos', 'action' => 'index']); ?></div>
			<?php }?>
			<?php echo $no_info;?>
    </div>
	</div>
	<div class="panel panel-info">
	<div class="panel-heading"><?php echo __('コース一覧'); ?></div>
	<div class="panel-body">
		<ul class="list-group">
		<?php foreach ($courses as $course): ?>
		<?php //debug($course)?>
			<a href="<?php echo Router::url(['controller' => 'contents', 'action' => 'index', $course['id']]);?>" class="list-group-item">
				<?php if($course['left_cnt']!=0){?>
				<button type="button" class="btn btn-danger btn-rest"><?php echo __('残り')?> <span class="badge"><?php echo h($course['left_cnt']); ?></span></button>
				<?php }?>
				<h4 class="list-group-item-heading"><?php echo h($course['title']);?></h4>
				<p class="list-group-item-text">
					<span><?php echo __('学習開始日').': '.Utils::getYMD($course['first_date']); ?></span>
					<span><?php echo __('最終学習日').': '.Utils::getYMD($course['last_date']); ?></span>
				</p>
			</a>
		<?php endforeach; ?>
		<?php echo $no_record;?>
        </ul>
	</div>
    </div>
</div>
