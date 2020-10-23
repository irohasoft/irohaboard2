<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersCourse[]|\Cake\Collection\CollectionInterface $usersCourses
 */
?>
<div class="usersCourses index content">
    <?= $this->Html->link(__('New Users Course'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Users Courses') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('course_id') ?></th>
                    <th><?= $this->Paginator->sort('started') ?></th>
                    <th><?= $this->Paginator->sort('ended') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usersCourses as $usersCourse): ?>
                <tr>
                    <td><?= $this->Number->format($usersCourse->id) ?></td>
                    <td><?= $usersCourse->has('user') ? $this->Html->link($usersCourse->user->name, ['controller' => 'Users', 'action' => 'view', $usersCourse->user->id]) : '' ?></td>
                    <td><?= $usersCourse->has('course') ? $this->Html->link($usersCourse->course->title, ['controller' => 'Courses', 'action' => 'view', $usersCourse->course->id]) : '' ?></td>
                    <td><?= h($usersCourse->started) ?></td>
                    <td><?= h($usersCourse->ended) ?></td>
                    <td><?= h($usersCourse->created) ?></td>
                    <td><?= h($usersCourse->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $usersCourse->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $usersCourse->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $usersCourse->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersCourse->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
