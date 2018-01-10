<?php
/* @var $this yii\web\View */
$this->title = $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?php echo  $post->title; ?></h1>
<p class="text-muted">作者：<?php echo $post->author; ?>nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>标签: 
	<?php foreach ($tags as $tag) {
		echo "<kbd>" . $tag['tag'] . "</kbd>&nbsp;&nbsp;";
	} ?>
	</span>
</p>
<p>
    <?php echo yii\helpers\Markdown::process($post->content, 'gfm'); ?>
</p>