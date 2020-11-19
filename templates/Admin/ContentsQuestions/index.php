<?php echo $this->element('admin_menu');?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContentsQuestion[]|\Cake\Collection\CollectionInterface $contentsQuestions
 */
?>
<div class="contentsQuestions index content">
    <?= $this->Html->link(__('New Contents Question'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Contents Questions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('content_id') ?></th>
                    <th><?= $this->Paginator->sort('question_type') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('image') ?></th>
                    <th><?= $this->Paginator->sort('options') ?></th>
                    <th><?= $this->Paginator->sort('correct') ?></th>
                    <th><?= $this->Paginator->sort('score') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('sort_no') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contentsQuestions as $contentsQuestion): ?>
                <tr>
                    <td><?= $this->Number->format($contentsQuestion->id) ?></td>
                    <td><?= $contentsQuestion->has('content') ? $this->Html->link($contentsQuestion->content->title, ['controller' => 'Contents', 'action' => 'view', $contentsQuestion->content->id]) : '' ?></td>
                    <td><?= h($contentsQuestion->question_type) ?></td>
                    <td><?= h($contentsQuestion->title) ?></td>
                    <td><?= h($contentsQuestion->image) ?></td>
                    <td><?= h($contentsQuestion->options) ?></td>
                    <td><?= h($contentsQuestion->correct) ?></td>
                    <td><?= $this->Number->format($contentsQuestion->score) ?></td>
                    <td><?= h($contentsQuestion->created) ?></td>
                    <td><?= h($contentsQuestion->modified) ?></td>
                    <td><?= $this->Number->format($contentsQuestion->sort_no) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $contentsQuestion->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $contentsQuestion->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $contentsQuestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contentsQuestion->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
	<?php echo $this->element('paging');?>
</div>
