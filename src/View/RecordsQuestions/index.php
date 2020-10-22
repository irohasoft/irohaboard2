<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\RecordsQuestion[]|\Cake\Collection\CollectionInterface $recordsQuestions
 */
?>
<div class="recordsQuestions index content">
    <?= $this->Html->link(__('New Records Question'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Records Questions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('record_id') ?></th>
                    <th><?= $this->Paginator->sort('question_id') ?></th>
                    <th><?= $this->Paginator->sort('answer') ?></th>
                    <th><?= $this->Paginator->sort('correct') ?></th>
                    <th><?= $this->Paginator->sort('is_correct') ?></th>
                    <th><?= $this->Paginator->sort('score') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recordsQuestions as $recordsQuestion): ?>
                <tr>
                    <td><?= $this->Number->format($recordsQuestion->id) ?></td>
                    <td><?= $recordsQuestion->has('record') ? $this->Html->link($recordsQuestion->record->id, ['controller' => 'Records', 'action' => 'view', $recordsQuestion->record->id]) : '' ?></td>
                    <td><?= $this->Number->format($recordsQuestion->question_id) ?></td>
                    <td><?= h($recordsQuestion->answer) ?></td>
                    <td><?= h($recordsQuestion->correct) ?></td>
                    <td><?= $this->Number->format($recordsQuestion->is_correct) ?></td>
                    <td><?= $this->Number->format($recordsQuestion->score) ?></td>
                    <td><?= h($recordsQuestion->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $recordsQuestion->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $recordsQuestion->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $recordsQuestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $recordsQuestion->id)]) ?>
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
