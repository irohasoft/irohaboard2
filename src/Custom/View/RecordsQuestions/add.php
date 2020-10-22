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
            <?= $this->Html->link(__('List Records Questions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="recordsQuestions form content">
            <?= $this->Form->create($recordsQuestion) ?>
            <fieldset>
                <legend><?= __('Add Records Question') ?></legend>
                <?php
                    echo $this->Form->control('record_id', ['options' => $records]);
                    echo $this->Form->control('question_id');
                    echo $this->Form->control('answer');
                    echo $this->Form->control('correct');
                    echo $this->Form->control('is_correct');
                    echo $this->Form->control('score');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
