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
<?php echo $this->element('admin_menu');?>
<?php $this->start('script-embedded'); ?>
<script>
	function openRecord(course_id, user_id)
	{
		window.open(
			'<?php echo Router::url(array('controller' => 'contents', 'action' => 'admin-record', 'prefix' => false)) ?>/'+course_id+'/'+user_id,
			'irohaboard_record',
			'width=1100, height=700, menubar=no, toolbar=no, scrollbars=yes'
		);
	}
	
	function openTestRecord(content_id, record_id)
	{
		window.open(
			'<?= Router::url(array('controller' => 'contents_questions', 'action' => 'admin-record', 'prefix' => false)) ?>/'+content_id+'/'+record_id,
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
			echo $this->Form->control('course_id',			['label' => __('コース :'), 'options'=>$courses, 'empty' => '全て']);
			echo $this->Form->control('content_category',	['label' => __('コンテンツ種別 :'), 'options'=>Configure::read('content_category'), 'empty' => '全て']);
			echo $this->Form->control('contenttitle',		['label' => __('コンテンツ名 :')]);
			echo '</div>';
			
			echo '<div class="ib-row">';
			echo $this->Form->control('group_id',		['label' => __('グループ :'), 'options'=>$groups, 'empty' => '全て']);
			echo $this->Form->control('username',		['label' => __('ログインID :')]);
			echo $this->Form->control('name',			['label' => __('氏名 :')]);
			echo '</div>';
			
			echo '<div class="ib-search-date-container">';
			echo $this->Form->control('from_date', [
				'label'		=> __('対象日時 : '),
				'type'		=> 'date',
				'value'		=> $from_date,
//				'default'	=> date('Y-m-d'),
/*
				'minYear'	=> date('Y') - 5,
				'maxYear'	=> date('Y'),
				'dateFormat' => 'YMD',
				'monthNames' => false,
				'timeFormat' => '24',
*/
//				'separator' => ' / ',
//				'class'=>'form-control',
//				'style' => 'display: inline;',
			]);
			echo $this->Form->control('to_date', [
				'label'		=> '～',
				'type'		=> 'date',
				'value'		=> $to_date,
//				'default'	=> date('Y-m-d')
/*
				'dateFormat' => 'YMD',
				'monthNames' => false,
				'timeFormat' => '24',
				'minYear' => date('Y') - 5,
				'maxYear' => date('Y'),
				'separator' => ' / ',
				'class'=>'form-control',
				'style' => 'display: inline;',
*/
			]);
			echo '</div>';
			echo $this->Form->end();
		?>
	</div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th nowrap><?= $this->Paginator->sort('User.username', __('ログインID')); ?></th>
		<th nowrap><?= $this->Paginator->sort('User.name', __('氏名')); ?></th>
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
		<?php //debug($record);?>
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
