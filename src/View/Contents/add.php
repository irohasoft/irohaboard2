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
            <?= $this->Html->link(__('List Contents'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="contents form content">
            <?= $this->Form->create($content) ?>
            <fieldset>
                <legend><?= __('Add Content') ?></legend>
                <?php
                    echo $this->Form->control('course_id', ['options' => $courses]);
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('title');
                    echo $this->Form->control('url');
                    echo $this->Form->control('kind');
                    echo $this->Form->control('body');
                    echo $this->Form->control('timelimit');
                    echo $this->Form->control('pass_rate');
                    echo $this->Form->control('opened', ['empty' => true]);
                    echo $this->Form->control('deleted', ['empty' => true]);
                    echo $this->Form->control('sort_no');
                    echo $this->Form->control('comment');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
