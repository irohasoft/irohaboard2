<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersCourse $usersCourse
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Users Course'), ['action' => 'edit', $usersCourse->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Users Course'), ['action' => 'delete', $usersCourse->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersCourse->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Users Courses'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Users Course'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="usersCourses view content">
            <h3><?= h($usersCourse->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $usersCourse->has('user') ? $this->Html->link($usersCourse->user->name, ['controller' => 'Users', 'action' => 'view', $usersCourse->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Course') ?></th>
                    <td><?= $usersCourse->has('course') ? $this->Html->link($usersCourse->course->title, ['controller' => 'Courses', 'action' => 'view', $usersCourse->course->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($usersCourse->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Started') ?></th>
                    <td><?= h($usersCourse->started) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ended') ?></th>
                    <td><?= h($usersCourse->ended) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($usersCourse->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($usersCourse->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Comment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($usersCourse->comment)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
