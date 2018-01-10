<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = "标签";
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>标签</h1>

<div class="row">
	<?php foreach ($tags as $tag) {?>
	<div class="col-md-3">
		<div class="media">
			<div class="media-lef">
				<a href="#">
					<img class="media-object" width="54px" height="54px" src="<?= $tag->tag_img ?>" alt="<?= $tag->tag ?>"></img>
				</a>
			</div>
			<div class="media-body">
				<h4 class="media-heading">
					<a href="<?= Url::toRoute(['post/index', 'id'=>$tag->id]) ?>"><?= $tag->tag ?></a>
				</h4>
				<p><?= $tag->meta_description ?></p>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
