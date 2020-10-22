<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersCourse $usersCourse
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $usersCourse->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $usersCourse->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Users Courses'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="usersCourses form content">
            <?= $this->Form->create($usersCourse) ?>
            <fieldset>
                <legend><?= __('Edit Users Course') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('course_id', ['options' => $courses]);
                    echo $this->Form->control('started', ['empty' => true]);
                    echo $this->Form->control('ended', ['empty' => true]);
                    echo $this->Form->control('comment');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
