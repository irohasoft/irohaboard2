<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContentsQuestion $contentsQuestion
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

$this->Form->setTemplates(Configure::read('bootstrap_form_template'));
?>
<?= $this->element('admin_menu');?>
<?= $this->Html->css('summernote.css');?>
<?php $this->start('script-embedded'); ?>
<?= $this->Html->script('summernote.min.js');?>
<?= $this->Html->script('lang/summernote-ja-JP.js');?>
<script>
	$(document).ready(function()
	{
		init();
	});

	function add_option()
	{
		txt	= document.all("option");
		opt	= document.all("option_list[]").options;
		
		if(txt.value == '')
		{
			alert("選択肢を入力してください");
			return false;
		}
		
		if(txt.value.length > 100)
		{
			alert("選択肢は100文字以内で入力してください");
			return false;
		}
		
		if(opt.length == 10)
		{
			alert("選択肢の数が最大値を超えています");
			return false;
		}
		
		opt[opt.length] = new Option( txt.value, txt.value )
		txt.value = "";
		update_options();
		update_correct();

		return false;
	}

	function del_option()
	{
		var opt = document.all("option_list[]").options;
		
		if( opt.selectedIndex > -1 )
		{
			opt[opt.selectedIndex] = null;
			update_options();
			update_correct();
		}
	}

	function update_options()
	{
		var opt = document.all("option_list[]").options;
		var txt = document.all("options");
		
		txt.value = "";
		
		for(var i=0; i<opt.length; i++)
		{
			if(txt.value=="")
			{
				txt.value = opt[i].value;
			}
			else
			{
				txt.value += "|" + opt[i].value;
			}
		}
		
	}

	function update_correct()
	{
		var opt = document.all("option_list[]").options;
		
		if( opt.selectedIndex < 0 )
		{
			document.all("correct").value = "";
		}
		else
		{
			var corrects = new Array();
			
			for(var i=0; i<opt.length; i++)
			{
				if(opt[i].selected)
					corrects.push(i+1);
			}
			
			document.all("correct").value = corrects.join(',');
		}
	}

	function init()
	{
		// リッチテキストエディタを起動
		CommonUtil.setRichTextEditor('#body', <?= Configure::read('upload_image_maxsize') ?>, '<?= $this->webroot ?>');
		CommonUtil.setRichTextEditor('#explain', <?= Configure::read('upload_image_maxsize') ?>, '<?= $this->webroot ?>');
		
		// 保存時、コード表示モードの場合、解除する（編集中の内容を反映するため）
		$("form").submit( function() {
			if ($('#explain').summernote('codeview.isActivated')) {
				$('#explain').summernote('codeview.deactivate')
			}
		});
		
		if($("input[name='options']").val()=="")
			return;
		
		var options = $("input[name='options']").val().split('|');
		var corrects = $("input[name='correct']").val().split(',');
		
		for(var i=0; i<options.length; i++)
		{
			var no = (i+1).toString();
			var isSelected = (corrects.indexOf(no) >= 0);
			
			$option = $('<option>')
				.val(options[i])
				.text(options[i])
				.prop('selected', isSelected);
			$("#option-list").append($option);
		}
	}
</script>
<?php $this->end(); ?>
<div class="admin-contents-questions-edit">
	<div class="ib-breadcrumb">
	<?php 
		//debug($content);
		$this->Breadcrumbs->add('コース一覧',  ['controller' => 'courses', 'action' => 'index']);
		$this->Breadcrumbs->add($content->course->title,  ['controller' => 'contents', 'action' => 'index', $content->course->id]);
		$this->Breadcrumbs->add($content->title, ['controller' => 'contents_questions', 'action' => 'index', $content->id]);
		
		echo $this->Breadcrumbs->render(['class' => 'ib-breadcrumbs'], ['separator' => ' / ']);
	?>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?= $this->isEditPage() ? __('編集') :  __('新規問題'); ?>
		</div>
		<div class="panel-body">
			<?php
				echo $this->Form->create($contentsQuestion, ['class' => 'form-horizontal']);
				echo $this->Form->control('title',	['label' => __('タイトル')]);
				echo $this->Form->control('body',		['label' => __('問題文')]);
			?>
			<div class="form-group required">
				<label for="options" class="col col-sm-3 control-label">選択肢／正解</label>
				<div class="col col-sm-9 required">
				「＋」で選択肢の追加、「−」で選択された選択肢を削除します。（※最大10個まで）<br>
				また選択された選択肢が正解となります。Ctrlキーを押下したまま選択することで、複数の正解の設定も可能です。<br>
				<input type="text" size="20" name="option" style="width: 80%;display:inline-block;">
				<button class="btn" onclick="add_option();return false;">＋</button>
				<button class="btn" onclick="del_option();return false;">−</button><br>
			<?php
				echo $this->Form->control('option_list',	['label' => __('選択肢／正解'), 
					'type' => 'select',
					'label' => false,
					'multiple' => true,
					'size' => 5,
					'onchange' => 'update_correct()'
				]);
				echo $this->Form->hidden('options',		['label' => __('選択肢')]);
			?>
				</div>
			</div>
			<?php
				echo "<div class='' style='display:none;'>";
				echo $this->Form->control('correct',	['label' => __('正解')]);
				echo "</div>";
				echo $this->Form->control('score',	['label' => __('得点')]);
				echo $this->Form->control('explain',	['label' => __('解説')]);
				echo $this->Form->control('comment',	['label' => __('備考')]);
				echo $this->Form->submit('保存', Configure::read('form_submit_defaults'));
				$this->Form->end();
			?>
		</div>
	</div>
</div>