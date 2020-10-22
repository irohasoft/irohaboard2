<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersGroup $usersGroup
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Users Group'), ['action' => 'edit', $usersGroup->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Users Group'), ['action' => 'delete', $usersGroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersGroup->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Users Groups'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Users Group'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="usersGroups view content">
            <h3><?= h($usersGroup->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $usersGroup->has('user') ? $this->Html->link($usersGroup->user->name, ['controller' => 'Users', 'action' => 'view', $usersGroup->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Group') ?></th>
                    <td><?= $usersGroup->has('group') ? $this->Html->link($usersGroup->group->title, ['controller' => 'Groups', 'action' => 'view', $usersGroup->group->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($usersGroup->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($usersGroup->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($usersGroup->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Comment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($usersGroup->comment)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
