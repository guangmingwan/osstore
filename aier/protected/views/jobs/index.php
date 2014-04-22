<script language="javascript">
function reload()
{
	window.location.reload();
}
</script>
任务:[待处理队列:<?php echo $jobscount;?>]
<?php $form=$this->beginWidget('CActiveForm'); ?>
<input type="hidden" name="act" value="1" />
<div class="row buttons">
		<?php echo CHtml::button( '刷新', array('title'=>"刷新",'onclick'=>'js:reload()')  ); ?> 
		<?php echo CHtml::submitButton( '开始刷机'  ); ?>
		<?php echo CHTML::ajaxLink( '恢复影子产品', array('reloadproduct'),
array('success'=>'js:function(data) {
alert(data);
}'))?>
	</div>
 

<?php $this->endWidget(); ?>

<div class="row">
		<p><?php echo CHtml::label('日至','content'); ?></p>
		<?php echo CHtml::textArea('gitlog',$gitlog,array('rows'=>30, 'cols'=>90)); ?> 
		 
</div>
