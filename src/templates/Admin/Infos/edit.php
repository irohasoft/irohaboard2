<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Info $info
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

$this->Form->setTemplates(Configure::read('bootstrap_form_template'));
?>
<?= $this->element('admin_menu');?>
<?= $this->Html->css( 'select2.min.css');?>
<?= $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(['block' => true]); ?>
	$(function (e) {
		$('#groups-ids').select2({placeholder:   "<?= __('選択しない場合、全てのユーザが対象となります。')?>", closeOnSelect: <?= (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
	});
<?php $this->Html->scriptEnd(); ?>
<div class="admin-infos-edit">
<?= $this->Html->link(__('<< 戻る'), ['action' => 'index'])?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= $this->isEditPage() ? __('編集') :  __('新規お知らせ'); ?>
		</div>
		<div class="panel-body">
		<?php
			echo $this->Form->create($info, ['class' => 'form-horizontal']);
			echo $this->Form->control('title',			['label' => __('タイトル')]);
			echo $this->Form->control('body',			['label' => __('本文')]);
			echo $this->Form->control('groups._ids',	['options' => $groups, 'label' => __('対象グループ')]);
			echo Configure::read('form_submit_before')
				.$this->Form->submit(__('保存'), Configure::read('form_submit_defaults'))
				.Configure::read('form_submit_after');
			echo $this->Form->end();
		?>
        </div>
    </div>
</div>
