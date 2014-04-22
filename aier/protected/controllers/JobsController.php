<?php

class JobsController extends Controller
{
	public $layout='column1';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated users to access all actions
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	public function actionReloadProduct()
	{
		$conn = mysql_connect('localhost','root','eprint');
		mysql_select_db('osstore_mg17',$conn);
		$sql = ' INSERT INTO `catalog_product_entity_int` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`)

SELECT `entity_type_id`, (SELECT `attribute_id` FROM `eav_attribute` WHERE `entity_type_id` = (SELECT `entity_type_id` FROM `eav_entity_type` WHERE `entity_type_code` = "catalog_product") AND `attribute_code` = "status") AS `attribute_id`, 0 AS `store_id`, `entity_id`, 1 AS `value` FROM `catalog_product_entity` WHERE `entity_id` NOT IN (SELECT `entity_id` FROM `catalog_product_entity_int` WHERE `attribute_id` = (SELECT `attribute_id` FROM `eav_attribute` WHERE `entity_type_id` = (SELECT `entity_type_id` FROM `eav_entity_type` WHERE `entity_type_code` = "catalog_product") AND `attribute_code` = "status")); ';
		mysql_query($sql);
		$rc = mysql_affected_rows();
		echo "Records inserted: " . $rc;
 	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
//		$u = new User();
		//var_dump($u->hashPassword('b17h6c28'));
		if(isset($_POST['act']))
		{
			$branch =$_POST['act'];
			if(strstr($branch,'1'))
			{
				$predictions = Jobs::model()->findAll("branch=:branch",array(":branch"=>$branch)); 
				if(empty($predictions))
				{
				$model = new Jobs();
				$model->branch= $branch;
				if($model->save())
					$this->redirect(array('index'));
				}
			}
		}
		
		 
		exec("git branch", $outs);
		$id = 0 ;
		$branches = array();
		$selectedV = 0;
		foreach($outs as $b)
		{
			$branches[] = array("id"=>trim($b),"title"=> trim($b));
			if(strstr($b,"*"))
			{
				$selectedV = trim($b);
			}
			$id++;
		}
		exec("cat out.log",$outs2);
		$gitlog = implode("\r\n",$outs2);
		 
		//var_dump($branches); die();
		$predictions = Jobs::model()->findAll();
		$jobscount = count($predictions);

		$this->render('index',array(
			'model'=>$branches,
				'selectedV'=>$selectedV,
				"gitlog"=>$gitlog,
				"jobscount"=>$jobscount,
		));
	}

	 
}
