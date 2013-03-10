<style>
    .file {cursor: pointer;}
    .folder {cursor: pointer;}
</style>
<div style="width:30%; float:left;">
<?php $this->widget('CTreeView', array(
    'url'  => array('project/children'),
    'animated' => 'normal',
    'htmlOptions' => array(
        'class' => 'filetree',
    ),
)); ?>
</div>

<div class="entity-block" style="width:65%; float:left; margin-right: 10px;">
    
</div>
<script type="text/javascript">

$(function(){

$('.filetree').on('click', 'span', function() {
   var $elem = $(this);
   var id = $elem.closest('li').attr('id');
   $.post("<?php echo $this->createUrl('entity'); ?>", {'id' : id}, function(ans) {
      $('.entity-block').html(ans); 
   });
});
   
});
</script>