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
            <?= $this->Html->link(__('List Contents Questions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="contentsQuestions form content">
            <?= $this->Form->create($contentsQuestion) ?>
            <fieldset>
                <legend><?= __('Add Contents Question') ?></legend>
                <?php
                    echo $this->Form->control('content_id', ['options' => $contents]);
                    echo $this->Form->control('question_type');
                    echo $this->Form->control('title');
                    echo $this->Form->control('body');
                    echo $this->Form->control('image');
                    echo $this->Form->control('options');
                    echo $this->Form->control('correct');
                    echo $this->Form->control('score');
                    echo $this->Form->control('explain');
                    echo $this->Form->control('comment');
                    echo $this->Form->control('sort_no');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
