<div class="paginator">
	<div class="text-center">
		<?= $this->Paginator->counter(__('合計').' : {{count}}'.__('件').'　{{page}} / {{pages}}'.__('ページ')) ?></p>
	</div>
	<div class="text-center">
		<ul class="pagination">
			<?= $this->Paginator->first('<< ') ?>
			<?= $this->Paginator->prev('< ') ?>
			<?= $this->Paginator->numbers() ?>
			<?= $this->Paginator->next(' >') ?>
			<?= $this->Paginator->last(' >>') ?>
		</ul>
	</div>
</div>