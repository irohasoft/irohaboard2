<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\RecordsQuestion $recordsQuestion
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Records Question'), ['action' => 'edit', $recordsQuestion->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Records Question'), ['action' => 'delete', $recordsQuestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $recordsQuestion->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Records Questions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Records Question'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="recordsQuestions view content">
            <h3><?= h($recordsQuestion->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Record') ?></th>
                    <td><?= $recordsQuestion->has('record') ? $this->Html->link($recordsQuestion->record->id, ['controller' => 'Records', 'action' => 'view', $recordsQuestion->record->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Answer') ?></th>
                    <td><?= h($recordsQuestion->answer) ?></td>
                </tr>
                <tr>
                    <th><?= __('Correct') ?></th>
                    <td><?= h($recordsQuestion->correct) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($recordsQuestion->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Question Id') ?></th>
                    <td><?= $this->Number->format($recordsQuestion->question_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Correct') ?></th>
                    <td><?= $this->Number->format($recordsQuestion->is_correct) ?></td>
                </tr>
                <tr>
                    <th><?= __('Score') ?></th>
                    <td><?= $this->Number->format($recordsQuestion->score) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($recordsQuestion->created) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
