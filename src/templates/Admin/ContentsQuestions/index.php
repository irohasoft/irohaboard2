<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContentsQuestion[]|\Cake\Collection\CollectionInterface $contentsQuestions
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;
?>
<?php $this->start('script-embedded'); ?>
<script>
	$(function(){
		$('#sortable-table tbody').sortable(
		{
			helper: function(event, ui)
			{
				var children = ui.children();
				var clone = ui.clone();

				clone.children().each(function(index)
				{
					$(this).width(children.eq(index).width());
				});
				return clone;
			},
			update: function(event, ui)
			{
				var id_list = new Array();

				$('.target_id').each(function(index)
				{
					id_list[id_list.length] = $(this).val();
				});
				
				var csrf = $('input[name=_csrfToken]').val();

				$.ajax({
					url: "<?= Router::url(['action' => 'order']) ?>",
					type: "POST",
					data: { id_list : id_list },
					dataType: "text",
					beforeSend: function(xhr){
						xhr.setRequestHeader("X-CSRF-Token",csrf);
					},
					success : function(response){
						//通信成功時の処理
						//alert(response);
					},
					error: function(){
						//通信失敗時の処理
						//alert('通信失敗');
					}
				});
			},
			cursor: "move",
			opacity: 0.5
		});
	});
</script>
<?php $this->end(); ?>
<?= $this->element('admin_menu');?>
<div class="admin-contents-questions-index">
	<div class="ib-breadcrumb">
	<?php 
		$this->Breadcrumbs->add(__('コース一覧'), ['controller' => 'courses', 'action' => 'index']);
		$this->Breadcrumbs->add($content->course->title, ['controller' => 'contents', 'action' => 'index', $content->course->id]);
		$this->Breadcrumbs->add(h($content->title));
		
		echo $this->Breadcrumbs->render(['class' => 'ib-breadcrumbs'], ['separator' => ' / ']);
	?>
	</div>
	<div class="ib-page-title"><?= __('テスト問題一覧'); ?></div>
	
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?= Router::url(['action' => 'add', $content->id]) ?>'">+ 追加</button>
	</div>
	
	<div class="alert alert-warning"><?= __('ドラッグアンドドロップで出題順が変更できます。'); ?></div>
	<table id='sortable-table' cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th><?= __('タイトル'); ?></th>
		<th><?= __('問題文'); ?></th>
		<th><?= __('選択肢'); ?></th>
		<th width="40" nowap><?= __('正解'); ?></th>
		<th width="40" nowap><?= __('得点'); ?></th>
		<th class="ib-col-date"><?= __('作成日時'); ?></th>
		<th class="ib-col-date"><?= __('更新日時'); ?></th>
		<th class="actions text-center"><?= __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($contentsQuestions as $contentsQuestion): ?>
	<tr>
		<td class="td-reader"><?= h($contentsQuestion->title); ?>&nbsp;</td>
		<td class="td-reader"><?= h(strip_tags($contentsQuestion->body)); ?>&nbsp;</td>
		<td class="td-reader"><?= h($contentsQuestion->options); ?>&nbsp;</td>
		<td><?= h($contentsQuestion->correct); ?>&nbsp;</td>
		<td><?= h($contentsQuestion->score); ?>&nbsp;</td>
		<td class="ib-col-date"><?= Utils::getYMDHN($contentsQuestion->created); ?>&nbsp;</td>
		<td class="ib-col-date"><?= Utils::getYMDHN($contentsQuestion->modified); ?>&nbsp;</td>
		<td class="ib-col-action">
			<?= $this->Html->link(__('編集'), ['action' => 'edit', $content->id, $contentsQuestion->id], ['class' => 'btn btn-success']) ?>
			<?= $this->Form->postLink(__('削除'), ['action' => 'delete', $contentsQuestion->id], ['confirm' => __('{0} を削除してもよろしいですか?', $contentsQuestion->title), 'class'=>'btn btn-danger']) ?>
			<?= $this->Form->hidden('id', ['id'=>'', 'class'=>'target_id', 'value'=>$contentsQuestion->id]); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
