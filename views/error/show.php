<?php
$this->breadcrumbs=array(
	$this->getTitle(),
);
?>

<h2><?php echo $this->getTitle(); ?> <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>