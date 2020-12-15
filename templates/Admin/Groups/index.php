<?= $this->element('admin_menu');?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group[]|\Cake\Collection\CollectionInterface $groups
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;
?>
<div class="admin-groups-index">
	<div class="ib-page-title"><?= __('グループ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?= Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>
	
	<table>
	<thead>
	<tr>
		<th><?= $this->Paginator->sort('title', 'グループ名'); ?></th>
		<th nowrap class="col-course"><?= __('受講コース'); ?></th>
		<th class="ib-col-date"><?= $this->Paginator->sort('created', __('作成日時')); ?></th>
		<th class="ib-col-date"><?= $this->Paginator->sort('modified', __('更新日時')); ?></th>
		<th class="ib-col-action"><?= __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($groups as $group): ?>
	<tr>
		<td><?= h($group->title); ?></td>
		<td><div class="reader" title="<?= h($group->course_title); ?>"><p><?= h($group->course_title); ?>&nbsp;</p></div></td>
		<td class="ib-col-date"><?= h(Utils::getYMDHN($group->created)); ?>&nbsp;</td>
		<td class="ib-col-date"><?= h(Utils::getYMDHN($group->modified)); ?>&nbsp;</td>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success" onclick="location.href='<?= Router::url(array('action' => 'edit', $group->id)) ?>'"><?= __('編集')?></button>
			<?= $this->Form->postLink(__('削除'), 
					array('action' => 'delete', $group->id), 
					array('class'=>'btn btn-danger'), 
					__('[%s] を削除してもよろしいですか?', $group->title)
			); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?= $this->element('paging');?>
</div>
