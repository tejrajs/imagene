<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\components\GDI_image;
use app\models\Setting;
use yii\web\Session;
use Facebook\Facebook;
use app\models\TipsDetail;
use app\models\Imagine;
use yii\data\Pagination;

class SiteController extends Controller
{
	public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
    	$session = new Session();
    	$session->open();
    	
    	$fb = new Facebook([
					'app_id' => Setting::getValue('FB_APP_ID'),
					'app_secret' => Setting::getValue('FB_APP_SECRET'),
					'default_graph_version' => 'v2.5',
		]);
		$helper = $fb->getCanvasHelper ();
		
		try {
				$accessToken = $helper->getAccessToken ();
				// Returns a `Facebook\FacebookResponse` object
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
		}
		if (!isset($accessToken)) {
	    	if(!isset($session['facebook_access_token'])){
	    	    $helper = $fb->getRedirectLoginHelper();
	    	    $permissions = ['email', 'user_likes','user_posts','publish_actions']; // optional
    	        $loginUrl = $helper->getLoginUrl(Setting::getValue('APP_CALLBACK_URL'), $permissions);
	    		echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
                exit;
	    	}		
	    }	
    	
    	$query = TipsDetail::find()->where(['active' => 1])->orderBy('RAND()');
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count()]);
    	$models = $query->offset($pages->offset)
    		->limit($pages->limit)
    		->all();
    	
        return $this->render('index',[
        		'models' => $models,
        		'pages' => $pages,
        ]);
    }
	
    public function actionProcess($id)
    {
    	$tips = TipsDetail::findOne(['id' => $id, 'active' => '1']);
    	$model = new Imagine();
    	$model->text = $tips->tips;
    	$model->font_size = '20';
    	$model->font_color = '#000000';
    	$model->outline_color = '#ffffff';
    	if ($model->load(Yii::$app->request->post())) {
    		$post = Yii::$app->request->post();
    		$model->font_name = BASE_PATH.'/'.$post['Imagine']['font_name'];//.$model->font_name;
    		$model->font_color = $post['Imagine']['font_color'];
    		$model->outline_color = $post['Imagine']['outline_color'];
    		$model->image_directory = BASE_PATH.'/images/';
    		$model->file_name = 'post';
    		$model->save_to_file = true;
    		$model->create();
    			
    		$fb = new Facebook([
					'app_id' => Setting::getValue('FB_APP_ID'),
					'app_secret' => Setting::getValue('FB_APP_SECRET'),
					'default_graph_version' => 'v2.5',
			]);
			$session = new Session();
			
			$linkData['message'] = $post['Imagine']['message'];
			$linkData['source'] =  $fb->fileToUpload($model->image_directory.$model->file_name.'.png');
			$helper = $fb->getCanvasHelper ();
			try {
				$accessToken = $helper->getAccessToken ();
				// Returns a `Facebook\FacebookResponse` object
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
			if (!isset($accessToken)) {
	    		if(!isset($session['facebook_access_token'])){
	    			return $this->redirect(['/site/connect']);
	    		}else{
	    			$accessToken = $session['facebook_access_token'];
	    		}   		
	    	}
			$response = $fb->post('/me/photos', $linkData, $accessToken);
    		\Yii::$app->session->setFlash('success', 'Sucessfully Post in Your Wall');
    		return $this->redirect(['/site/index']);
    	}
    	return $this->render('process', [
    			'model' => $model,
    	]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionPolicy()
    {
        return $this->render('about');
    }
    
    public function actionService()
    {
        return $this->render('about');
    }
    
    public function actionMarketing()
    {
        return $this->render('about');
    }
    
    public function actionSupport()
    {
        return $this->render('about');
    }
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
    	$model = new SignupForm();
    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->signup()) {
    			if (Yii::$app->getUser()->login($user)) {
    				return $this->goHome();
    			}
    		}
    	}
    
    	return $this->render('signup', [
    			'model' => $model,
    	]);
    }
    
    public function actionConnect()
    {
    	$session = new Session();
    	$session->open();
    	 
    	$fb = new Facebook([
    			'app_id' => Setting::getValue('FB_APP_ID'),
				'app_secret' => Setting::getValue('FB_APP_SECRET'),
    			'default_graph_version' => 'v2.5',
    			//'default_access_token' => '{access-token}', // optional
    	]);
    	 
    	$helper = $fb->getRedirectLoginHelper();
    	try {
    		$token = $helper->getAccessToken ();
    	} catch ( \Facebook\Exceptions\FacebookResponseException $e ) {
    		echo 'Graph returned an error: ' . $e->getMessage ();
    		exit ();
    	} catch ( \Facebook\Exceptions\FacebookSDKException $e ) {
    		echo 'Facebook SDK returned an error: ' . $e->getMessage();
    		exit;
    	}
    	 
    	$permissions = ['email', 'user_likes','user_posts','publish_actions']; // optional
    	$loginUrl = $helper->getLoginUrl(Setting::getValue('APP_CALLBACK_URL'), $permissions);
    	echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
    }
    
    public function actionCallback()
    {
    	$session = new Session();
    	$session->open();
    	 
    	$fb = new Facebook([
    			'app_id' => Setting::getValue('FB_APP_ID'),
				'app_secret' => Setting::getValue('FB_APP_SECRET'),
    			'default_graph_version' => 'v2.5',
    			//'default_access_token' => '{access-token}', // optional
    	]);
    	$helper = $fb->getRedirectLoginHelper();
    	 
    	try {
    		$accessToken = $helper->getAccessToken();
    	} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    		// When Graph returns an error
    		echo 'Graph returned an error: ' . $e->getMessage();
    		exit;
    	} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    		// When validation fails or other local issues
    		echo 'Facebook SDK returned an error: ' . $e->getMessage();
    		exit;
    	}
    	 
    	if (isset($accessToken)) {
    		// Logged in!
    		$session['facebook_access_token'] = (string) $accessToken;
    		return $this->redirect(Setting::getValue('FB_APP_URL'));
    		// Now you can redirect to another page and use the
    		// Now you can redirect to another page and use the
    		// access token from $_SESSION['facebook_access_token']
    	}else{
    		return $this->redirect(['/site/connect']);
    	}
    }
}
