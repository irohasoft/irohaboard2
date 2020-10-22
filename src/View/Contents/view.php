<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Content $content
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Content'), ['action' => 'edit', $content->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Content'), ['action' => 'delete', $content->id], ['confirm' => __('Are you sure you want to delete # {0}?', $content->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Contents'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Content'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="contents view content">
            <h3><?= h($content->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Course') ?></th>
                    <td><?= $content->has('course') ? $this->Html->link($content->course->title, ['controller' => 'Courses', 'action' => 'view', $content->course->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $content->has('user') ? $this->Html->link($content->user->name, ['controller' => 'Users', 'action' => 'view', $content->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($content->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Url') ?></th>
                    <td><?= h($content->url) ?></td>
                </tr>
                <tr>
                    <th><?= __('Kind') ?></th>
                    <td><?= h($content->kind) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($content->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Timelimit') ?></th>
                    <td><?= $this->Number->format($content->timelimit) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pass Rate') ?></th>
                    <td><?= $this->Number->format($content->pass_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sort No') ?></th>
                    <td><?= $this->Number->format($content->sort_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Opened') ?></th>
                    <td><?= h($content->opened) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($content->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($content->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deleted') ?></th>
                    <td><?= h($content->deleted) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Body') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($content->body)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Comment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($content->comment)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Contents Questions') ?></h4>
                <?php if (!empty($content->contents_questions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Content Id') ?></th>
                            <th><?= __('Question Type') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Image') ?></th>
                            <th><?= __('Options') ?></th>
                            <th><?= __('Correct') ?></th>
                            <th><?= __('Score') ?></th>
                            <th><?= __('Explain') ?></th>
                            <th><?= __('Comment') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Sort No') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($content->contents_questions as $contentsQuestions) : ?>
                        <tr>
                            <td><?= h($contentsQuestions->id) ?></td>
                            <td><?= h($contentsQuestions->content_id) ?></td>
                            <td><?= h($contentsQuestions->question_type) ?></td>
                            <td><?= h($contentsQuestions->title) ?></td>
                            <td><?= h($contentsQuestions->body) ?></td>
                            <td><?= h($contentsQuestions->image) ?></td>
                            <td><?= h($contentsQuestions->options) ?></td>
                            <td><?= h($contentsQuestions->correct) ?></td>
                            <td><?= h($contentsQuestions->score) ?></td>
                            <td><?= h($contentsQuestions->explain) ?></td>
                            <td><?= h($contentsQuestions->comment) ?></td>
                            <td><?= h($contentsQuestions->created) ?></td>
                            <td><?= h($contentsQuestions->modified) ?></td>
                            <td><?= h($contentsQuestions->sort_no) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'ContentsQuestions', 'action' => 'view', $contentsQuestions->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'ContentsQuestions', 'action' => 'edit', $contentsQuestions->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'ContentsQuestions', 'action' => 'delete', $contentsQuestions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contentsQuestions->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Records') ?></h4>
                <?php if (!empty($content->records)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Course Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Content Id') ?></th>
                            <th><?= __('Full Score') ?></th>
                            <th><?= __('Pass Score') ?></th>
                            <th><?= __('Score') ?></th>
                            <th><?= __('Is Passed') ?></th>
                            <th><?= __('Is Complete') ?></th>
                            <th><?= __('Progress') ?></th>
                            <th><?= __('Understanding') ?></th>
                            <th><?= __('Study Sec') ?></th>
                            <th><?= __('Created') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($content->records as $records) : ?>
                        <tr>
                            <td><?= h($records->id) ?></td>
                            <td><?= h($records->course_id) ?></td>
                            <td><?= h($records->user_id) ?></td>
                            <td><?= h($records->content_id) ?></td>
                            <td><?= h($records->full_score) ?></td>
                            <td><?= h($records->pass_score) ?></td>
                            <td><?= h($records->score) ?></td>
                            <td><?= h($records->is_passed) ?></td>
                            <td><?= h($records->is_complete) ?></td>
                            <td><?= h($records->progress) ?></td>
                            <td><?= h($records->understanding) ?></td>
                            <td><?= h($records->study_sec) ?></td>
                            <td><?= h($records->created) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Records', 'action' => 'view', $records->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Records', 'action' => 'edit', $records->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Records', 'action' => 'delete', $records->id], ['confirm' => __('Are you sure you want to delete # {0}?', $records->id)]) ?>
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
