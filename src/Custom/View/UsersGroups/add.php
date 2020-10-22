<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersGroup $usersGroup
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Users Groups'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="usersGroups form content">
            <?= $this->Form->create($usersGroup) ?>
            <fieldset>
                <legend><?= __('Add Users Group') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('group_id', ['options' => $groups]);
                    echo $this->Form->control('comment');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
