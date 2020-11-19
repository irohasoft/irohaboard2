<?php echo $this->element('admin_menu');?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Content[]|\Cake\Collection\CollectionInterface $contents
 */
?>
<div class="contents index content">
    <?= $this->Html->link(__('New Content'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Contents') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('course_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('url') ?></th>
                    <th><?= $this->Paginator->sort('kind') ?></th>
                    <th><?= $this->Paginator->sort('timelimit') ?></th>
                    <th><?= $this->Paginator->sort('pass_rate') ?></th>
                    <th><?= $this->Paginator->sort('opened') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('deleted') ?></th>
                    <th><?= $this->Paginator->sort('sort_no') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contents as $content): ?>
                <tr>
                    <td><?= $this->Number->format($content->id) ?></td>
                    <td><?= $content->has('course') ? $this->Html->link($content->course->title, ['controller' => 'Courses', 'action' => 'view', $content->course->id]) : '' ?></td>
                    <td><?= $content->has('user') ? $this->Html->link($content->user->name, ['controller' => 'Users', 'action' => 'view', $content->user->id]) : '' ?></td>
                    <td><?= h($content->title) ?></td>
                    <td><?= h($content->url) ?></td>
                    <td><?= h($content->kind) ?></td>
                    <td><?= $this->Number->format($content->timelimit) ?></td>
                    <td><?= $this->Number->format($content->pass_rate) ?></td>
                    <td><?= h($content->opened) ?></td>
                    <td><?= h($content->created) ?></td>
                    <td><?= h($content->modified) ?></td>
                    <td><?= h($content->deleted) ?></td>
                    <td><?= $this->Number->format($content->sort_no) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $content->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $content->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $content->id], ['confirm' => __('Are you sure you want to delete # {0}?', $content->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
	<?php echo $this->element('paging');?>
</div>
