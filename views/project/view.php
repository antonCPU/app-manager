<style>
    .file {cursor: pointer;}
    .folder {cursor: pointer;}
</style>
<div style="width:30%; float:left;">
<?php $this->widget('AmWidgets.AmProjectTree', array(
    'url'  => array('project/children'),
    'animated' => 'normal',
    'htmlOptions' => array(
        'class' => 'filetree',
    ),
)); ?>
</div>

<div class="entity-block" style="width:65%; float:left; margin-right: 10px;">
    <?php $this->renderPartial('update', array(
        'entity' => $entity,
    )); ?>
</div>
<script type="text/javascript">

$(function(){

$('.filetree').on('entityClick', function(e, id) {
   $.post("<?php echo $this->createUrl('update'); ?>", {'id' : id}, function(ans) {
      $('.entity-block').html(ans); 
   });
});
   
});
</script>