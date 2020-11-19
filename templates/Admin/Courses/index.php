<?php echo $this->element('admin_menu');?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course[]|\Cake\Collection\CollectionInterface $courses
 */
?>
<div class="courses index content">
    <?= $this->Html->link(__('New Course'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Courses') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('opened') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('deleted') ?></th>
                    <th><?= $this->Paginator->sort('sort_no') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?= $this->Number->format($course->id) ?></td>
                    <td><?= h($course->title) ?></td>
                    <td><?= h($course->opened) ?></td>
                    <td><?= h($course->created) ?></td>
                    <td><?= h($course->modified) ?></td>
                    <td><?= h($course->deleted) ?></td>
                    <td><?= $this->Number->format($course->sort_no) ?></td>
                    <td><?= $this->Number->format($course->user_id) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $course->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $course->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $course->id], ['confirm' => __('Are you sure you want to delete # {0}?', $course->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
	<?php echo $this->element('paging');?>
</div>
