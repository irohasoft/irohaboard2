<?= $this->element('admin_menu');?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course[]|\Cake\Collection\CollectionInterface $courses
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;
?>
<?php $this->Html->scriptStart(['block' => true]); ?>
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
					url: "<?= Router::url(array('action' => 'order')) ?>",
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
<?php $this->Html->scriptEnd(); ?>
<div class="courses index content">
	<div class="ib-page-title"><?= __('コース一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?= Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>

	<div class="alert alert-warning"><?= __('ドラッグアンドドロップでコースの並び順が変更できます。'); ?></div>
	<table id='sortable-table'>
	<thead>
	<tr>
		<th><?= __('コース名'); ?></th>
		<th class="ib-col-datetime"><?= __('作成日時'); ?></th>
		<th class="ib-col-datetime"><?= __('更新日時'); ?></th>
		<th class="ib-col-action"><?= __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($courses as $course): ?>
	<tr>
		<td>
			<?php 
				echo $this->Html->link($course->title, array('controller' => 'contents', 'action' => 'index', $course->id));
			?>
		</td>
		<td class="ib-col-date"><?= h(Utils::getYMDHN($course->created)); ?>&nbsp;</td>
		<td class="ib-col-date"><?= h(Utils::getYMDHN($course->modified)); ?>&nbsp;</td>
		<td class="ib-col-action">
			<?= $this->Html->link(__('編集'), ['action' => 'edit', $course->id], ['class' => 'btn btn-success']) ?>
			<?= $this->Form->postLink(__('削除'), ['action' => 'delete', $course->id], ['confirm' => __('{0} を削除してもよろしいですか?', $course->title), 'class'=>'btn btn-danger']) ?>
			<?= $this->Form->hidden('id', ['id'=>'', 'class'=>'target_id', 'value'=>$course->id]); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
