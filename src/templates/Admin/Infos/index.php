<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Info[]|\Cake\Collection\CollectionInterface $infos
 */
?>
<?= $this->element('admin_menu');?>
<div class="infos index content">
	<?= $this->Html->link(__('+ 追加'), ['action' => 'add'], ['class' => 'btn btn-primary btn-add pull-right']) ?>
	<h3><?= __('お知らせ一覧') ?></h3>
	<table>
		<thead>
		<tr>
			<th><?= $this->Paginator->sort('title',		__('タイトル')) ?></th>
			<th nowrap><?= __('対象グループ'); ?></th>
			<th><?= $this->Paginator->sort('created',	__('作成日時')) ?></th>
			<th><?= $this->Paginator->sort('modified',	__('更新日時')) ?></th>
				<th class="actions"><?= __('Actions') ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($infos as $info): ?>
		<tr>
			<td><?= h($info->title) ?></td>
			<td><div class="reader col-group" title="<?= h($info->group_title); ?>"><p><?= h($info->group_title); ?>&nbsp;</p></td>
			<td><?= h($info->created) ?></td>
			<td><?= h($info->modified) ?></td>
			<td class="ib-col-action">
				<?= $this->Html->link(__('編集'), ['action' => 'edit', $info->id], ['class' => 'btn btn-success']) ?>
				<?= $this->Form->postLink(__('削除'), ['action' => 'delete', $info->id], ['confirm' => __('{0} を削除してもよろしいですか?', $info->title), 'class'=>'btn btn-danger']) ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?= $this->element('paging');?>
</div>