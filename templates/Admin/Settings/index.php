<?= $this->element('admin_menu'); ?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting[]|\Cake\Collection\CollectionInterface $settings
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

$this->Form->setTemplates(Configure::read('bootstrap_form_template'));
?>
<div class="admin-settings-index">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= __('システム設定'); ?>
        </div>
        <div class="panel-body">
            <?php
            echo $this->Form->create(null, ['class' => 'form-horizontal']);
            echo $this->Form->control('title',        ['label' => __('システム名'),        'value' => $settings['title']]);
            echo $this->Form->control('copyright',    ['label' => __('コピーライト'),    'value' => $settings['copyright']]);
            echo $this->Form->control('color',        ['label' => __('テーマカラー'),    'options' => $colors, 'selected' => $settings['color']]);
            echo $this->Form->control('information',    ['label' => __('全体のお知らせ'),    'value' => $settings['information'], 'type' => 'textarea']);
			echo $this->Form->button(__('保存'), Configure::read('form_submit_defaults'));
			echo $this->Form->end();
			?>
        </div>
    </div>
</div>
