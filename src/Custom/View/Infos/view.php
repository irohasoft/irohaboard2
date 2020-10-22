<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Info $info
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Info'), ['action' => 'edit', $info->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Info'), ['action' => 'delete', $info->id], ['confirm' => __('Are you sure you want to delete # {0}?', $info->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Infos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Info'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="infos view content">
            <h3><?= h($info->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($info->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $info->has('user') ? $this->Html->link($info->user->name, ['controller' => 'Users', 'action' => 'view', $info->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($info->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Opened') ?></th>
                    <td><?= h($info->opened) ?></td>
                </tr>
                <tr>
                    <th><?= __('Closed') ?></th>
                    <td><?= h($info->closed) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($info->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($info->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Body') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($info->body)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
