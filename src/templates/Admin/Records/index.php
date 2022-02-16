<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Record[]|\Cake\Collection\CollectionInterface $records
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

$this->Form->setTemplates(Configure::read('bootstrap_search_template'));
?>
<?= $this->element('admin_menu');?>
<?php $this->start('script-embedded'); ?>
<script>
	function openRecord(course_id, user_id)
	{
		window.open(
			'<?= Router::url(['controller' => 'contents', 'action' => 'admin-record', 'prefix' => false]) ?>/'+course_id+'/'+user_id,
			'irohaboard_record',
			'width=1100, height=700, menubar=no, toolbar=no, scrollbars=yes'
		);
	}
	
	function openTestRecord(content_id, record_id)
	{
		window.open(
			'<?= Router::url(['controller' => 'contents_questions', 'action' => 'admin-record', 'prefix' => false]) ?>/'+content_id+'/'+record_id,
			'irohaboard_record',
			'width=1100, height=700, menubar=no, toolbar=no, scrollbars=yes'
		);
	}
	
	function downloadCSV()
	{
		$("#RecordCmd").val("csv");
		$("#RecordAdminIndexForm").submit();
		$("#RecordCmd").val("");
	}
</script>
<?php $this->end(); ?>
<div class="admin-records-index">
	<div class="ib-page-title"><?= __('学習履歴一覧'); ?></div>
	<div class="ib-horizontal">
	<?php
		echo $this->Form->create(null, ['valueSources' => 'query']);

		echo '<div class="ib-search-buttons">';
		echo $this->Form->submit(__('検索'),	['class' => 'btn btn-info btn-add']);
		echo $this->Form->button(__('CSV出力'),	['class' => 'btn btn-default', 'onclick' => 'downloadCSV();']);
		echo $this->Form->hidden('cmd');
		echo '</div>';
		
		echo '<div class="ib-row">';
		echo $this->Form->searchField('course_id',			['label' => __('コース'), 'options' => $courses, 'empty' => '全て']);
		echo $this->Form->searchField('content_category',	['label' => __('コンテンツ種別'), 'options' => Configure::read('content_category'), 'empty' => '全て']);
		echo $this->Form->searchField('contenttitle',		['label' => __('コンテンツ名')]);
		echo '</div>';
		
		echo '<div class="ib-row">';
		echo $this->Form->searchField('group_id',		['label' => __('グループ'), 'options' => $groups, 'empty' => '全て']);
		echo $this->Form->searchField('username',		['label' => __('ログインID')]);
		echo $this->Form->searchField('name',			['label' => __('氏名')]);
		echo '</div>';
		
		echo '<div class="ib-search-date-container">';
		echo $this->Form->searchDate('from_date', ['label'=> __('対象日時'), 'value' => $from_date]);
		echo $this->Form->searchDate('to_date',   ['label'=> __('～'), 'value' => $to_date]);
		echo '</div>';
		echo $this->Form->end();
	?>
	</div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th nowrap><?= $this->Paginator->sort('Users.username', __('ログインID')); ?></th>
		<th nowrap><?= $this->Paginator->sort('Users.name', __('氏名')); ?></th>
		<th nowrap><?= $this->Paginator->sort('course_id', __('コース')); ?></th>
		<th nowrap><?= $this->Paginator->sort('content_id', __('コンテンツ')); ?></th>
		<th nowrap class="ib-col-center"><?= $this->Paginator->sort('score', __('得点')); ?></th>
		<th class="ib-col-center" nowrap><?= $this->Paginator->sort('pass_score', __('合格点')); ?></th>
		<th nowrap class="ib-col-center"><?= $this->Paginator->sort('is_passed', __('結果')); ?></th>
		<th class="ib-col-center" nowrap><?= $this->Paginator->sort('understanding', __('理解度')); ?></th>
		<th class="ib-col-center"><?= $this->Paginator->sort('study_sec', __('学習時間')); ?></th>
		<th class="ib-col-datetime"><?= $this->Paginator->sort('created', __('学習日時')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($records as $record): ?>
	<tr>
		<td><?= h($record->user->username); ?>&nbsp;</td>
		<td><?= h($record->user->name); ?>&nbsp;</td>
		<td><a href="javascript:openRecord(<?= h($record->course->id); ?>, <?= h($record->user->id); ?>);"><?= h($record->course->title); ?></a></td>
		<td><?= h($record->content->title); ?>&nbsp;</td>
		<td class="ib-col-center"><?= h($record->score); ?>&nbsp;</td>
		<td class="ib-col-center"><?= h($record->pass_score); ?>&nbsp;</td>
		<td nowrap class="ib-col-center"><a href="javascript:openTestRecord(<?= h($record->content->id); ?>, <?= h($record->id); ?>);"><?= Configure::read('record_result.'.$record->is_passed); ?></a></td>
		<td nowrap class="ib-col-center"><?= h(Configure::read('record_understanding.'.$record->understanding)); ?>&nbsp;</td>
		<td class="ib-col-center"><?= h(Utils::getHNSBySec($record->study_sec)); ?>&nbsp;</td>
		<td class="ib-col-date"><?= h(Utils::getYMDHN($record->created)); ?>&nbsp;</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?= $this->element('paging');?>
</div>
