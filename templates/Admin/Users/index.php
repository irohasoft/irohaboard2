<?= $this->element('admin_menu');?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

$this->Form->setTemplates(Configure::read('bootstrap_search_template'));
?>
<div class="admin-users-index">
	<div class="ib-page-title"><?= __('ユーザ一覧'); ?></div>
	<div class="buttons_container">
		<?php if($loginedUser['role']=='admin'){ ?>
		<button type="button" class="btn btn-primary btn-export" onclick="location.href='<?= Router::url(['action' => 'export']) ?>'">エクスポート</button>
		<button type="button" class="btn btn-primary btn-import" onclick="location.href='<?= Router::url(['action' => 'import']) ?>'">インポート</button>
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?= Router::url(['action' => 'add']) ?>'">+ 追加</button>
		<?php }?>
	</div>
	<div class="ib-horizontal">
		<?php
			$data = ['username' => 'test'];
			
			echo $this->Form->create(null, ['valueSources' => 'query']);
			echo $this->Form->control('group_id',	[
				'label'		=> 'グループ : ', 
				'options'	=>$groups, 
//				'value'		=>$group_id, 
				'empty'		=> '全て', 
				'class'		=> 'form-control',
				'onchange'	=> 'submit(this.form);'
			]);
			echo $this->Form->control('username',	['label' => __('ログインID : ')]);
			echo $this->Form->control('name',		['label' => __('氏名 : ')]);
			echo $this->Form->hidden('mode',		['value' => 'search']); // グループ選択の解除用
			echo $this->Form->submit(__('検索'),	['class' => 'btn btn-info btn-add']);
			echo $this->Form->end();
		?>
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
	<?= $this->element('paging');?>
</div>