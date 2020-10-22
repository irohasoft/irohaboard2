<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Log[]|\Cake\Collection\CollectionInterface $logs
 */
?>
<div class="logs index content">
    <?= $this->Html->link(__('New Log'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Logs') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('log_type') ?></th>
                    <th><?= $this->Paginator->sort('log_content') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('user_ip') ?></th>
                    <th><?= $this->Paginator->sort('user_agent') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $this->Number->format($log->id) ?></td>
                    <td><?= h($log->log_type) ?></td>
                    <td><?= h($log->log_content) ?></td>
                    <td><?= $log->has('user') ? $this->Html->link($log->user->name, ['controller' => 'Users', 'action' => 'view', $log->user->id]) : '' ?></td>
                    <td><?= h($log->user_ip) ?></td>
                    <td><?= h($log->user_agent) ?></td>
                    <td><?= h($log->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $log->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $log->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $log->id], ['confirm' => __('Are you sure you want to delete # {0}?', $log->id)]) ?>
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
