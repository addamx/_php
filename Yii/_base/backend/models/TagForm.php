<?php
namespace backend\models;
use yii\base\Model;
use yii\web\UploadedFile;
use Common\models\Tags;
//use yii\helpers\Url;

class TagForm extends Model
{
	public $tag_img;

	public function rules()
	{
		return [
			[['tag_img'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg']
		];
	}

	public function upload()
	{
		if ($this->validate()) {
			$randName = date("Y").date("m").date("d").date("H").date("i").date("s").rand(100,999).'-'.md5(microtime());
			$uploadPath = 'uploads/' . $randName . '.' . $this->tag_img->extension;	//设置保存路径， 为 backend\web\uploads
			$this->tag_img->saveAs($uploadPath);	//保存文件
			return $uploadPath;
		} else {
			return false;
		}                   
	}
}