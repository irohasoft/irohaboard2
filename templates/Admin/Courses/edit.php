<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course $course
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

$this->Form->setTemplates(Configure::read('bootstrap_form_template'));
?>
<?= $this->element('admin_menu');?>
<div class="admin-courses-edit">
<?= $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= ($this->getAction() == 'edit') ? __('編集') :  __('新規コース'); ?>
		</div>
		<div class="panel-body">
			<?php
				echo $this->Form->create($course, ['class' => 'form-horizontal']);
				echo $this->Form->control('title',	array('label' => __('コース名')));
				echo $this->Form->control('introduction',	array('label' => __('コース紹介')));
				echo $this->Form->control('comment',		array('label' => __('備考')));
				echo $this->Form->button(__('保存'), Configure::read('form_submit_defaults'));
				echo $this->Form->end();
			?>
		</div>
	</div>
</div>
