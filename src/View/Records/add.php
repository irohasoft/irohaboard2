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
            <?= $this->Html->link(__('List Records'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="records form content">
            <?= $this->Form->create($record) ?>
            <fieldset>
                <legend><?= __('Add Record') ?></legend>
                <?php
                    echo $this->Form->control('course_id', ['options' => $courses]);
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('content_id', ['options' => $contents]);
                    echo $this->Form->control('full_score');
                    echo $this->Form->control('pass_score');
                    echo $this->Form->control('score');
                    echo $this->Form->control('is_passed');
                    echo $this->Form->control('is_complete');
                    echo $this->Form->control('progress');
                    echo $this->Form->control('understanding');
                    echo $this->Form->control('study_sec');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
