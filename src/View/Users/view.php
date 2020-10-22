<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users view content">
            <h3><?= h($user->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($user->username) ?></td>
                </tr>
                <tr>
                    <th><?= __('Password') ?></th>
                    <td><?= h($user->password) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($user->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= h($user->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Logined') ?></th>
                    <td><?= h($user->last_logined) ?></td>
                </tr>
                <tr>
                    <th><?= __('Started') ?></th>
                    <td><?= h($user->started) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ended') ?></th>
                    <td><?= h($user->ended) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($user->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($user->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deleted') ?></th>
                    <td><?= h($user->deleted) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Comment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($user->comment)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Courses') ?></h4>
                <?php if (!empty($user->courses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Opened') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th><?= __('Sort No') ?></th>
                            <th><?= __('Comment') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->courses as $courses) : ?>
                        <tr>
                            <td><?= h($courses->id) ?></td>
                            <td><?= h($courses->title) ?></td>
                            <td><?= h($courses->opened) ?></td>
                            <td><?= h($courses->created) ?></td>
                            <td><?= h($courses->modified) ?></td>
                            <td><?= h($courses->deleted) ?></td>
                            <td><?= h($courses->sort_no) ?></td>
                            <td><?= h($courses->comment) ?></td>
                            <td><?= h($courses->user_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Courses', 'action' => 'view', $courses->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Courses', 'action' => 'edit', $courses->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Courses', 'action' => 'delete', $courses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $courses->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Groups') ?></h4>
                <?php if (!empty($user->groups)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Comment') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th><?= __('Status') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->groups as $groups) : ?>
                        <tr>
                            <td><?= h($groups->id) ?></td>
                            <td><?= h($groups->title) ?></td>
                            <td><?= h($groups->comment) ?></td>
                            <td><?= h($groups->created) ?></td>
                            <td><?= h($groups->modified) ?></td>
                            <td><?= h($groups->deleted) ?></td>
                            <td><?= h($groups->status) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Groups', 'action' => 'view', $groups->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Groups', 'action' => 'edit', $groups->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Groups', 'action' => 'delete', $groups->id], ['confirm' => __('Are you sure you want to delete # {0}?', $groups->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Contents') ?></h4>
                <?php if (!empty($user->contents)) : ?>
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
                        <?php foreach ($user->contents as $contents) : ?>
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
                <h4><?= __('Related Infos') ?></h4>
                <?php if (!empty($user->infos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Opened') ?></th>
                            <th><?= __('Closed') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->infos as $infos) : ?>
                        <tr>
                            <td><?= h($infos->id) ?></td>
                            <td><?= h($infos->title) ?></td>
                            <td><?= h($infos->body) ?></td>
                            <td><?= h($infos->opened) ?></td>
                            <td><?= h($infos->closed) ?></td>
                            <td><?= h($infos->created) ?></td>
                            <td><?= h($infos->modified) ?></td>
                            <td><?= h($infos->user_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Infos', 'action' => 'view', $infos->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Infos', 'action' => 'edit', $infos->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Infos', 'action' => 'delete', $infos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $infos->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Logs') ?></h4>
                <?php if (!empty($user->logs)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Log Type') ?></th>
                            <th><?= __('Log Content') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('User Ip') ?></th>
                            <th><?= __('User Agent') ?></th>
                            <th><?= __('Created') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->logs as $logs) : ?>
                        <tr>
                            <td><?= h($logs->id) ?></td>
                            <td><?= h($logs->log_type) ?></td>
                            <td><?= h($logs->log_content) ?></td>
                            <td><?= h($logs->user_id) ?></td>
                            <td><?= h($logs->user_ip) ?></td>
                            <td><?= h($logs->user_agent) ?></td>
                            <td><?= h($logs->created) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Logs', 'action' => 'view', $logs->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Logs', 'action' => 'edit', $logs->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Logs', 'action' => 'delete', $logs->id], ['confirm' => __('Are you sure you want to delete # {0}?', $logs->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Records') ?></h4>
                <?php if (!empty($user->records)) : ?>
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
                        <?php foreach ($user->records as $records) : ?>
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
