<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Record $record
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Record'), ['action' => 'edit', $record->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Record'), ['action' => 'delete', $record->id], ['confirm' => __('Are you sure you want to delete # {0}?', $record->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Records'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Record'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="records view content">
            <h3><?= h($record->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Course') ?></th>
                    <td><?= $record->has('course') ? $this->Html->link($record->course->title, ['controller' => 'Courses', 'action' => 'view', $record->course->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $record->has('user') ? $this->Html->link($record->user->name, ['controller' => 'Users', 'action' => 'view', $record->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Content') ?></th>
                    <td><?= $record->has('content') ? $this->Html->link($record->content->title, ['controller' => 'Contents', 'action' => 'view', $record->content->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($record->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Full Score') ?></th>
                    <td><?= $this->Number->format($record->full_score) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pass Score') ?></th>
                    <td><?= $this->Number->format($record->pass_score) ?></td>
                </tr>
                <tr>
                    <th><?= __('Score') ?></th>
                    <td><?= $this->Number->format($record->score) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Passed') ?></th>
                    <td><?= $this->Number->format($record->is_passed) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Complete') ?></th>
                    <td><?= $this->Number->format($record->is_complete) ?></td>
                </tr>
                <tr>
                    <th><?= __('Progress') ?></th>
                    <td><?= $this->Number->format($record->progress) ?></td>
                </tr>
                <tr>
                    <th><?= __('Understanding') ?></th>
                    <td><?= $this->Number->format($record->understanding) ?></td>
                </tr>
                <tr>
                    <th><?= __('Study Sec') ?></th>
                    <td><?= $this->Number->format($record->study_sec) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($record->created) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Records Questions') ?></h4>
                <?php if (!empty($record->records_questions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Record Id') ?></th>
                            <th><?= __('Question Id') ?></th>
                            <th><?= __('Answer') ?></th>
                            <th><?= __('Correct') ?></th>
                            <th><?= __('Is Correct') ?></th>
                            <th><?= __('Score') ?></th>
                            <th><?= __('Created') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($record->records_questions as $recordsQuestions) : ?>
                        <tr>
                            <td><?= h($recordsQuestions->id) ?></td>
                            <td><?= h($recordsQuestions->record_id) ?></td>
                            <td><?= h($recordsQuestions->question_id) ?></td>
                            <td><?= h($recordsQuestions->answer) ?></td>
                            <td><?= h($recordsQuestions->correct) ?></td>
                            <td><?= h($recordsQuestions->is_correct) ?></td>
                            <td><?= h($recordsQuestions->score) ?></td>
                            <td><?= h($recordsQuestions->created) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'RecordsQuestions', 'action' => 'view', $recordsQuestions->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'RecordsQuestions', 'action' => 'edit', $recordsQuestions->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'RecordsQuestions', 'action' => 'delete', $recordsQuestions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $recordsQuestions->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
