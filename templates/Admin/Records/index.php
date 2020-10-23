<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Record[]|\Cake\Collection\CollectionInterface $records
 */
?>
<div class="records index content">
    <?= $this->Html->link(__('New Record'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Records') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('course_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('content_id') ?></th>
                    <th><?= $this->Paginator->sort('full_score') ?></th>
                    <th><?= $this->Paginator->sort('pass_score') ?></th>
                    <th><?= $this->Paginator->sort('score') ?></th>
                    <th><?= $this->Paginator->sort('is_passed') ?></th>
                    <th><?= $this->Paginator->sort('is_complete') ?></th>
                    <th><?= $this->Paginator->sort('progress') ?></th>
                    <th><?= $this->Paginator->sort('understanding') ?></th>
                    <th><?= $this->Paginator->sort('study_sec') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                <tr>
                    <td><?= $this->Number->format($record->id) ?></td>
                    <td><?= $record->has('course') ? $this->Html->link($record->course->title, ['controller' => 'Courses', 'action' => 'view', $record->course->id]) : '' ?></td>
                    <td><?= $record->has('user') ? $this->Html->link($record->user->name, ['controller' => 'Users', 'action' => 'view', $record->user->id]) : '' ?></td>
                    <td><?= $record->has('content') ? $this->Html->link($record->content->title, ['controller' => 'Contents', 'action' => 'view', $record->content->id]) : '' ?></td>
                    <td><?= $this->Number->format($record->full_score) ?></td>
                    <td><?= $this->Number->format($record->pass_score) ?></td>
                    <td><?= $this->Number->format($record->score) ?></td>
                    <td><?= $this->Number->format($record->is_passed) ?></td>
                    <td><?= $this->Number->format($record->is_complete) ?></td>
                    <td><?= $this->Number->format($record->progress) ?></td>
                    <td><?= $this->Number->format($record->understanding) ?></td>
                    <td><?= $this->Number->format($record->study_sec) ?></td>
                    <td><?= h($record->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $record->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $record->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $record->id], ['confirm' => __('Are you sure you want to delete # {0}?', $record->id)]) ?>
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
