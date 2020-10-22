<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Setting'), ['action' => 'edit', $setting->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Setting'), ['action' => 'delete', $setting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setting->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Settings'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Setting'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="settings view content">
            <h3><?= h($setting->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Setting Key') ?></th>
                    <td><?= h($setting->setting_key) ?></td>
                </tr>
                <tr>
                    <th><?= __('Setting Name') ?></th>
                    <td><?= h($setting->setting_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Setting Value') ?></th>
                    <td><?= h($setting->setting_value) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($setting->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
