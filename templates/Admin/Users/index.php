<?php echo $this->element('admin_menu');?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="admin-users-index">
	<div class="ib-page-title"><?php echo __('ユーザ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo $this->Url->build(['action' => 'add']) ?>'">+ 追加</button>
	</div>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th><?= $this->Paginator->sort('username',		__('ログインID')) ?></th>
				<th><?= $this->Paginator->sort('name',			__('氏名')) ?></th>
				<th><?= $this->Paginator->sort('role',			__('権限')) ?></th>
				<th><?= $this->Paginator->sort('group_title',	__('所属グループ')) ?></th>
				<th class="ib-col-datetime"><?= $this->Paginator->sort('course_title',	__('受講コース')) ?></th>
				<th><?= $this->Paginator->sort('last_logined',	__('最終ログイン日時')) ?></th>
				<th><?= $this->Paginator->sort('created',		__('作成日時')) ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
			<tr>
				<td><?= h($user->username) ?></td>
				<td><?= h($user->name) ?></td>
				<td><?= h($user->role) ?></td>
				<td><div class="reader" title="<?= h($user->group_title); ?>"><p><?= h($user->group_title); ?>&nbsp;</p></td>
				<td><div class="reader" title="<?= h($user->course_title); ?>"><p><?= h($user->course_title); ?>&nbsp;</p></div></td>
				<td><?= h($user->last_logined) ?></td>
				<td><?= h($user->created) ?></td>
				<td class="ib-col-action">
					<?= $this->Html->link(__('編集'), ['action' => 'edit', $user->id], ['class' => 'btn btn-success']) ?>
					<?= $this->Form->postLink(__('削除'), ['action' => 'delete', $user->id], ['confirm' => __('{0} を削除してもよろしいですか?', $user->title), 'class'=>'btn btn-danger']) ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('paging');?>
</div>