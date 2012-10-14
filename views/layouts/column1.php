<?php $this->beginContent('/layouts/main'); ?>
    <div class="menu-sections">
		<?php $this->widget('zii.widgets.CMenu', array('items'=>$this->getMenu())); ?>
	</div>
    <div id="content">
        <?php $this->widget('AmFlash'); ?>
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>
