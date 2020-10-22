<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContentsQuestion $contentsQuestion
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Contents Question'), ['action' => 'edit', $contentsQuestion->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Contents Question'), ['action' => 'delete', $contentsQuestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contentsQuestion->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Contents Questions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Contents Question'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="contentsQuestions view content">
            <h3><?= h($contentsQuestion->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Content') ?></th>
                    <td><?= $contentsQuestion->has('content') ? $this->Html->link($contentsQuestion->content->title, ['controller' => 'Contents', 'action' => 'view', $contentsQuestion->content->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Question Type') ?></th>
                    <td><?= h($contentsQuestion->question_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($contentsQuestion->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Image') ?></th>
                    <td><?= h($contentsQuestion->image) ?></td>
                </tr>
                <tr>
                    <th><?= __('Options') ?></th>
                    <td><?= h($contentsQuestion->options) ?></td>
                </tr>
                <tr>
                    <th><?= __('Correct') ?></th>
                    <td><?= h($contentsQuestion->correct) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($contentsQuestion->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Score') ?></th>
                    <td><?= $this->Number->format($contentsQuestion->score) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sort No') ?></th>
                    <td><?= $this->Number->format($contentsQuestion->sort_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($contentsQuestion->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($contentsQuestion->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Body') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($contentsQuestion->body)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Explain') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($contentsQuestion->explain)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Comment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($contentsQuestion->comment)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
