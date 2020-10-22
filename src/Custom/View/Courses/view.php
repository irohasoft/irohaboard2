<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course $course
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Course'), ['action' => 'edit', $course->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Course'), ['action' => 'delete', $course->id], ['confirm' => __('Are you sure you want to delete # {0}?', $course->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Courses'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Course'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="courses view content">
            <h3><?= h($course->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($course->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($course->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sort No') ?></th>
                    <td><?= $this->Number->format($course->sort_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('User Id') ?></th>
                    <td><?= $this->Number->format($course->user_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Opened') ?></th>
                    <td><?= h($course->opened) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($course->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($course->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deleted') ?></th>
                    <td><?= h($course->deleted) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Comment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($course->comment)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Users') ?></h4>
                <?php if (!empty($course->users)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Username') ?></th>
                            <th><?= __('Password') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Role') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Comment') ?></th>
                            <th><?= __('Last Logined') ?></th>
                            <th><?= __('Started') ?></th>
                            <th><?= __('Ended') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($course->users as $users) : ?>
                        <tr>
                            <td><?= h($users->id) ?></td>
                            <td><?= h($users->username) ?></td>
                            <td><?= h($users->password) ?></td>
                            <td><?= h($users->name) ?></td>
                            <td><?= h($users->role) ?></td>
                            <td><?= h($users->email) ?></td>
                            <td><?= h($users->comment) ?></td>
                            <td><?= h($users->last_logined) ?></td>
                            <td><?= h($users->started) ?></td>
                            <td><?= h($users->ended) ?></td>
                            <td><?= h($users->created) ?></td>
                            <td><?= h($users->modified) ?></td>
                            <td><?= h($users->deleted) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Contents') ?></h4>
                <?php if (!empty($course->contents)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Course Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Url') ?></th>
                            <th><?= __('Kind') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Timelimit') ?></th>
                            <th><?= __('Pass Rate') ?></th>
                            <th><?= __('Opened') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th><?= __('Sort No') ?></th>
                            <th><?= __('Comment') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($course->contents as $contents) : ?>
                        <tr>
                            <td><?= h($contents->id) ?></td>
                            <td><?= h($contents->course_id) ?></td>
                            <td><?= h($contents->user_id) ?></td>
                            <td><?= h($contents->title) ?></td>
                            <td><?= h($contents->url) ?></td>
                            <td><?= h($contents->kind) ?></td>
                            <td><?= h($contents->body) ?></td>
                            <td><?= h($contents->timelimit) ?></td>
                            <td><?= h($contents->pass_rate) ?></td>
                            <td><?= h($contents->opened) ?></td>
                            <td><?= h($contents->created) ?></td>
                            <td><?= h($contents->modified) ?></td>
                            <td><?= h($contents->deleted) ?></td>
                            <td><?= h($contents->sort_no) ?></td>
                            <td><?= h($contents->comment) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Contents', 'action' => 'view', $contents->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Contents', 'action' => 'edit', $contents->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Contents', 'action' => 'delete', $contents->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contents->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Records') ?></h4>
                <?php if (!empty($course->records)) : ?>
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
                        <?php foreach ($course->records as $records) : ?>
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
