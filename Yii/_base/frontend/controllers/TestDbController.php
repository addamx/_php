<?php
namespace frontend\controllers;

use common\models\Test;
use common\models\Posts;
use common\models\Tags;
use yii\web\NotFoundHttpException;

class TestDbController extends \yii\web\Controller
{
	public function actionSeeder()
	{
		$faker = \Faker\Factory::create('zh_CN');
        $a=0;
        for ($i=0; $i <20 ; $i++) {
            $test = new Test();
            $test->title = $faker->text($maxNbChars = 20);
            $test->email = $faker->email;
            $test->name = $faker->name;
            $test->phone = $faker->phoneNumber;
            if ($test->insert()) {
                 $a+=1;
            }else{
            }
        }
        //其他用法:
        /*
		$faker->address;$faker->city;$faker->phoneNumber;$faker->firstName;$faker->sentence(7, true);
        */
       //? $test->password = Yii::$app->getSecurity()->generatePasswordHash('password_' . $index);
       // $test->auth_key = Yii::$app->getSecurity()->generateRandomString();
        echo "添加".$a."条数据";
	}

	public function actionIndex()
    {
        $form = new \frontend\models\ContactForm;   //继承model类(包括AR)
        $form['name'] = 'Tom';                      //[属性]: 代表可像普通类属性或数组 一样被访问的业务数据;
        var_dump($form->getAttributes());
        echo $form->getAttributeLabel('verifyCode');    //[属性标签]
        $res = \common\models\Test::findOne(1);
        var_dump($res->attributes);          //[数据导出]
        $test = new Test;
        //$test->email_address = "new attr by fields";
        $test->name = 'Tom';                //修改了fields()
        var_dump($test->toArray());
        //var_dump($test->toArray([], ['test']));  //? 返回fields()定义的所有字段和额外字段中的'test'
    }

    public function actionScenario()
    {

    }

	/**
	 * 原生数据库访问
	 */
	public function actionDb()
	{
		$db0 = new \yii\db\Connection([
				'dsn' => 'mysql:host:localhost;dbname=example',
				'username' => 'root',
				'password' => '123',
				'charset' => 'utf8',
			]);
		$db = \Yii::$app->db;

		$query0 = $db->createCommand("SELECT * FROM {{Test}}")
				// ->queryAll()
				->queryOne()
				// ->queryColumn()
				// ->queryScalar()
				;

		$params = ['id' => 10, 'phone' => 2147483647];
		$id = 10;$phone = 2147483647;

		$query1 = $db->createCommand("SELECT * FROM {{Test}} WHERE id=:id AND phone=:phone")
				->bindValue(':id',10)	//bind a single parameter value
				->bindValue(':phone',2147483647)
				->bindValues($params)	//bind multiple parameter values in one call
				->bindParam(':id', $id) //similar to bindValue() but also support binding parameter references.
				->queryOne();

		//或者直接在createCommand里添加参数
		$query2 = $db->createCommand("SELECT * FROM {{Test}} WHERE id=:id AND phone=:phone", $params);

		/**
		 * INSERT
		 * Return 影响行数
		 */
		// var_dump($db->createCommand()->insert('test', [
		// 		'name' => 'jack',
		// 		'title' => 'jack title',
		// 		'email' => 'jackk@test.com',
		// 		'phone' => 111232
		// 	])->execute());
		
		/**
		 * UPDATE
		 * Return 影响行数
		 */
		var_dump($db->createCommand()->update('test', ['name' => 'Jackson'], 'id = 45')->execute());

		/**
		 * DELETE
		 * Return 影响行数
		 */
		var_dump($db->createCommand()->delete('test', 'id = 45')->execute());

		//其他SQL操作见http://www.yiichina.com/doc/guide/2.0/db-dao

		//针对不同的数据库引用table和column的符号也不同的情况, 可以使用方括号[[]]和花括号{{}}
		//当tabel使用了prefix, 则还要加上百分号%
		$query3 = $db->createCommand("SELECT [[name]] FROM {{%Test}}")->queryAll();
		//var_dump($query3);


		/**
		 * 事务transaction
		 * 即使设置了读写分离, 事务默认所有操作只针对master
		 * 		如果要针对slave则:$transaction = Yii::$app->db->slave->beginTransaction();
		 */
		//1. $db->transaction()
		$db->transaction(function($db) {
			//$db->createCommand($sql1)->execute();
			//$db->createCommand($sql2)->execute();
		});
		//2. $db->beginTransaction(), ->commit(), ->rollBack()
		$transaction = $db->beginTransaction();

		try {
		    //$db->createCommand($sql1)->execute();
		    //$db->createCommand($sql2)->execute();
		    // ... executing other SQL statements ...
		    $transaction->commit();
		    
		} catch(\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		}
	}


	/**
	 * 读写分离 master-slaves
	 */
	public function actionDbMasterSlaves()
	{
		// 1 master for multi slaves;
		$config = [
			'class' => 'yii\db\Connection',
		    // configuration for the master
		    'dsn' => 'dsn for master server',
		    'username' => 'master',
		    'password' => '',

		    // common configuration for slaves
		    'slaveConfig' => [
		        'username' => 'slave',
		        'password' => '',
		        'attributes' => [
		            // use a smaller connection timeout
		            PDO::ATTR_TIMEOUT => 10,
		        ],
		    ],

		    // list of slave configurations
		    'slaves' => [
		        ['dsn' => 'dsn for slave server 1'],
		        ['dsn' => 'dsn for slave server 2'],
		        ['dsn' => 'dsn for slave server 3'],
		        ['dsn' => 'dsn for slave server 4'],
		    ],
		];
		// create a Connection instance using the above configuration
		\Yii::$app->db = \Yii::createObject($config);

		// 读: query against one of the slaves
		$rows = \Yii::$app->db->createCommand('SELECT * FROM user LIMIT 10')->queryAll();
			//强制使用master
		$rows = Yii::$app->db->useMaster(function ($db) {
		    return $db->createCommand('SELECT * FROM user LIMIT 10')->queryAll();
		});

		// 写: query against the master
		\Yii::$app->db->createCommand("UPDATE user SET username='demo' WHERE id=1")->execute();
			//(?maybe?不合逻辑)强制使用slaves()
			//$transaction = Yii::$app->db->slave.....


		// multi master for multi salves
		$config1 = [
			'class' => 'yii\db\Connection',

		    // common configuration for masters
		    'masterConfig' => [
		        'username' => 'master',
		        'password' => '',
		        'attributes' => [
		            // use a smaller connection timeout
		            PDO::ATTR_TIMEOUT => 10,
		        ],
		    ],

		    // list of master configurations
		    'masters' => [
		        ['dsn' => 'dsn for master server 1'],
		        ['dsn' => 'dsn for master server 2'],
		    ],

		    // common configuration for slaves
		    'slaveConfig' => [
		        'username' => 'slave',
		        'password' => '',
		        'attributes' => [
		            // use a smaller connection timeout
		            PDO::ATTR_TIMEOUT => 10,
		        ],
		    ],

		    // list of slave configurations
		    'slaves' => [
		        ['dsn' => 'dsn for slave server 1'],
		        ['dsn' => 'dsn for slave server 2'],
		        ['dsn' => 'dsn for slave server 3'],
		        ['dsn' => 'dsn for slave server 4'],
		    ],
		];
	}



	/**
	 * 查询生成器Query Builder
	 */
	public function actionDbQuery()
	{
		/**
		 * 查询构建器\yii\db\Query()
		 * 1. 不需要引入model类
		 */
		$status = 1;
		$query0 = (new \yii\db\Query())
			->select(['id', 'title' => 'Title'])	//or ['id, title AS Title']
			->addSelect(['created_at'])	//添加Select
			->from('posts')	//or "yii2basic.posts";  
			               	//使用别名:['u' => 'public.user', 'p' => 'public.post']
			->where(['status' => '1'])	//1.哈希格式
			->andWhere('status=:status', [':status' => $status])	//2.字符串格式
			->orWhere('status=:status')->addParams([':status' => $status])	//字符串格式 + addParams格外参数
			->orWhere(['like', 'title', ['test', 'sample']])	//3.操作符格式: name LIKE '%test%' AND name LIKE '%sample%
			//其他操作符: and, or, between, not between, in, not in, like
			//第一个操作数应为一个字段名称或 DB 表达式， 第二个操作数可以使字符串或数组
			//->distinct()	//去除重复行
			->orderBy(['created_at' => SORT_DESC, 'title' => SORT_ASC]);

		$query1 = (new \yii\db\Query())
			->select(['title' => 'tl'])
			->filterWhere([		// filterWhere()会忽略空值
			    'id' => 22,
			    'email' => '',		//忽略
			])
			->andFilterCompare('rating', '>9')
			->groupBy(['id', 'status'])
			->addGroupBy('title')
			->having(['status' => 1]);

		$query2 = (new \yii\db\Query())
			->select(['title' => 'tl'])	
			->limit(10)->offset(20)
			->join('LEFT JOIN', 'post', 'post.user_id = user.id');	//or innerJoin(), leftJoin(), rightJoin()
			                                                      	//
		$query3 = $query1->union($query2);

		$rows0 = $query0
					//->limit(10)	//* limit()和offset()改变了$query0
					//->offset(5)
				 	->all()
				// ->one()		//第一行
				// ->column()	//返回第一列 
				// ->scalar()	//第一行第一列的标量值
				/* 以下函数会无视limit()和offset()的约束 */ 
				// ->exists()
				// ->count()
				// ->sum('id')
				// ->average('id')
				// ->max('id')
				// ->min('id')
				;

		$rows1 = $query0
					->indexBy('id')		//* 'id'作为结果集的key, 改变了$query0
					->all();

		/*
		** 批处理查询(省内存)
		** 通过给 batch() 或者 each() 方法的第一个参数传值来改变每批行数的大小。
		** 
		*/
		foreach ($query0->batch(10) as $r) {		//如果曾用过indexBy()则保持结构
		                                    		//$params($batchSize = 100, $db = null)
		    // var_dump($r);
		    // echo "<br/>--------------------------------<br/>";
		}

		// 按行输出
		foreach ($query0->each() as $r) {
		    var_dump($r);
		    echo "<br/>--------------------------------<br/>";
		}
	}

	/**
	 * 活动记录表ActiveRecord
	 */
	public function actionActiveRecord()
	{
		$testAr = new test();

		/**
		 * 查询
		 */
		// var_dump($testAr->findData());
		// var_dump($testAr->findData1());
		// $testAr->findBatch();
		// $testAr->findBatch1();

		/**
		 * 操作数据
		 */
		// $testAr->operateData();


		$testAr->validateData();
		$testAr->category_id = '2';
		$testAr->loadDefaultValues();	//读取默认值给"空值"的字段
		// var_dump($testAr);

        $testJoin = new Posts();
        echo '<br/>----------------------------------------------------------<br/>';
        $testJoin->JoinTest();

	}

    /**
     * 验证来自form的数据
     */
	public function  actionValidate()
    {
        $model = new \frontend\models\ContactForm;

        // [模型验证]用户输入数据赋值到模型属性
        $model->attributes = \Yii::$app->request->post('ContactForm');  //[块赋值]
        //块赋值只应用在模型当前yii\base\Model::scenario 场景yii\base\Model::scenarios()方法 列出的称之为 安全属性 的属性上
        //例: 当当前场景为login时候，只有username and password 可被块赋值， 其他属性不会被赋值。
        if ($model->validate()) {
            // 所有输入数据都有效 all inputs are valid
        } else {
            // 验证失败：$errors 是一个包含错误信息的数组
            $errors = $model->errors;
        }

        //[临时验证] 单个验证器
        $email = 'test@example.com';
        $validator = new \yii\validators\EmailValidator();

        if ($validator->validate($email, $error)) {
            echo '有效的 Email 地址。.<br/>';
        } else {
            echo $error;
        }

        //多个验证器
        $name = 'addams11111111112312';
        $model = \yii\base\DynamicModel::validateData(compact('name', 'email'), [
            [['name', 'email'], 'string', 'max' => 10],
            ['email', 'email'],
        ]);
            //同上
        //$model = new \yii\base\DynamicModel(compact('name', 'email'));
        //$model->addRule(['name', 'email'], 'string', ['max' => 10])
        //    ->addRule('email', 'email')
        //    ->validate();

        if ($model->hasErrors()) {
            echo '多项验证失败.<br/>';
        } else {
            echo '多项验证成功.<br/>';
        }

        //独立验证器
        $validator1 = new \frontend\components\TestValidator();
        if ($validator1->validate($name, $error)) {
            echo '有效的 name。';
        } else {
            echo $error;
        }

    }
}