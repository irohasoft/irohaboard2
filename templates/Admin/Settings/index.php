<?php echo $this->element('admin_menu');?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting[]|\Cake\Collection\CollectionInterface $settings
 */
?>
<div class="settings index content">
    <?= $this->Html->link(__('New Setting'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Settings') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('setting_key') ?></th>
                    <th><?= $this->Paginator->sort('setting_name') ?></th>
                    <th><?= $this->Paginator->sort('setting_value') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settings as $setting): ?>
                <tr>
                    <td><?= $this->Number->format($setting->id) ?></td>
                    <td><?= h($setting->setting_key) ?></td>
                    <td><?= h($setting->setting_name) ?></td>
                    <td><?= h($setting->setting_value) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $setting->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $setting->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $setting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setting->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
	<?php echo $this->element('paging');?>
</div>
