<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use common\models\mongo\SampleCollection;
use common\models\mongo\ProjectTicketSequence;
use common\models\mongo\AccessTokenCollection;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\components\{CommonUtility};
use common\components\CommonUtilityTwo;
use common\models\bean\ResponseBean;
use common\models\mysql\Projects;//testing
use frontend\service\AccesstokenService;
use common\components\ServiceFactory;
use common\models\mysql\Collaborators;
use common\models\mongo\TinyUserCollection;
use common\models\mysql\ProjectTeam;
use common\service\CollaboratorService;
use common\models\mysql\RepoPermissions;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
         
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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

    /**
     * @inheritdoc
     */
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function beforeAction($action) {
    $this->enableCsrfValidation = false;
    return parent::beforeAction($action);
    }
   /**
    * @Description testing ajax
    */
    public function actionTestAjax()
    {error_log("----------1------dddddddddddd");
        //$ch = curl_init();
           // curl_setopt($ch, CURLOPT_URL, "http://10.10.73.16/test.php");
            //curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$result = json_decode(curl_exec($ch));

            //echo  print_r($result);
        //svn_checkout('http://34.194.239.160:81/svn/ProjectX/branches/Sample', dirname(__FILE__) . '/opt');
//        error_log("--------1--------dddddddddddd");
//        $constants = array_flip($this->getSvnConstants('SVN_WC_STATUS'));
//   error_log("----------2------dddddddddddd".print_r($constants,1));
//   $status = svn_status();
//
//   foreach($status as &$v)
//   {
//          $v['text_status']       = $constants[$v['text_status']];
//          $v['repos_text_status'] = $constants[$v['repos_text_status']];
//          $v['prop_status']       = $constants[$v['prop_status']];
//          $v['repos_prop_status'] = $constants[$v['repos_prop_status']];
//    }

    
        
        error_log("----------3------dddddddddddd");
        /*$model = new Projects();
       $userdata = $model->listUserData();
      $data = SampleCollection::testMongo();
       $model = new ProjectTicketSequence();
       $model->getNextSequence(2);
    */
    }
    
    /*public function getSvnConstants($filter='SVN_')
    { error_log("----------4------ssssssssssssss".$filter);
        $constants = array();
        foreach (get_defined_constants() as $key => $value)
            if (substr($key, 0, strlen($filter)) == $filter)
                $constants[$key] = $value;

        return $constants;
    }*/
    /**
     * @author Moin Hussain
     * @description This is sample method to demonstrate the response
     * @return type
     * Try this in browser http://10.10.73.33/site/sample-response
     */
    public function actionSampleResponse(){
        try{
        $data = ["firstName" => "Moin", "lastName" => "Hussain"];
        $responseBean = new ResponseBean;
        $responseBean->statusCode = ResponseBean::SUCCESS;
        $responseBean->message = ResponseBean::SUCCESS_MESSAGE;
        $responseBean->data = $data;
        $response = CommonUtility::prepareResponse($responseBean,"xml");
        return $response;   
        } catch (\Throwable $th) { 
             Yii::error("SiteController:actionSampleResponse::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        } 
       
    
    }
    /**
     * 
     * @return type
     * @Description User login action
     */
    public function actionLogin()
    {

        error_log("sssssssssssssssssssssssssssss");
        foreach ($_SERVER as $name => $value) {
        }
        $user_data = json_decode(file_get_contents("php://input"));
        $model = new LoginForm();
        $userData = $model->loginAjax($user_data);error_log("34444444444".print_r($userData,1));
        $responseBean = new ResponseBean;
        $responseBean->status = ResponseBean::SUCCESS;
        $responseBean->message = "success";
        $responseBean->data = $userData;
        $response = CommonUtility::prepareResponse($responseBean,"json");
        return $response;
            
        }
   /**
     * @author Padmaja
     * @Description This is authenticate the  Collaborator data
     * @return type json object
     * 
     */    

    public function actionUserAuthentication(){
        try{error_log("-dfdsf--df-d-sf-");
            //$ch = curl_init();
            /*$post_data = array(
                'username' => 'Sreeni',
                'password' => 'minimum8'
            );*/
            //$postfields = array();
            //$postfields['projectName'] = urlencode('Praj');
            //$postfields['field2'] = urlencode('value2');
            /*curl_setopt($ch, CURLOPT_URL, "http://10.10.73.16/test.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            
            $result = curl_exec($ch);
            //$info=curl_exec($ch);
            //$info = curl_getinfo($ch);
            error_log("-curl response-".print_r($result,1));*/
            $CollabaratorData = json_decode(file_get_contents("php://input"));
            
            $model = new LoginForm();
            //checking logged data is existed or not
            $getcollaboratorData = $model->checkLoginData($CollabaratorData);
            error_log("-curl response-".count($getcollaboratorData));
              if(count($getcollaboratorData)==1 && $getcollaboratorData !='failure'){
                $collabaratorId=$getcollaboratorData[0]['Id'];
                //fetching logged data from tiny user collection
                $collaboratorProfileData = TinyUserCollection::getMiniUserDetails($collabaratorId);
                $remembermeStatus=isset($CollabaratorData->rememberme)?1:0;
                //fetching accesstoken data from Accesstoken Collection
                $collabaratorTokenData = ServiceFactory::getCollaboratorServiceInstance()->getCollabaratorAccesstoken($collabaratorId);
                if(count($collabaratorTokenData)==0){
                    //generating access token
                    $accesstoken =  $this->GeneratingAccesstoken($collabaratorId);
                    $collabaratorArr=array('Id'=>$collabaratorId,'username'=>$getcollaboratorData[0]['UserName'],"token"=>$accesstoken,"ProfilePicture"=>$collaboratorProfileData['ProfilePicture'],"Email"=>$collaboratorProfileData['Email']);
                    $browserType=$_SERVER['HTTP_USER_AGENT'];
                    //saving collabarator  data
                    $getLastId = ServiceFactory::getCollaboratorServiceInstance()->saveCollabaratortokenData($accesstoken,$collabaratorId,$browserType,$remembermeStatus);
                    $responseBean = new ResponseBean;
                    $responseBean->status = ResponseBean::SUCCESS;
                    $responseBean->statusCode = ResponseBean::SUCCESS;
                    $responseBean->message = "success";
                    $responseBean->data =    $collabaratorArr;
                    //preparing response in the form of json
                    return  $response = CommonUtility::prepareResponse($responseBean,"json");
                }else if(count($collabaratorTokenData)>0 && $collabaratorTokenData[0]['Status']==1) {
                    $collabaratorLastToken= $collabaratorTokenData[0]['Accesstoken'];
                    $collabaratorArr=array('Id'=>$collabaratorId,'username'=>$getcollaboratorData[0]['UserName'],"token"=>$collabaratorLastToken,"ProfilePicture"=>$collaboratorProfileData['ProfilePicture'],"Email"=>$collaboratorProfileData['Email']);
                    $accesstoken="response";
                    $responseBean = new ResponseBean;
                    $responseBean->status = ResponseBean::SUCCESS;
                    $responseBean->statusCode = ResponseBean::SUCCESS;
                    $responseBean->message = "success";
                    $responseBean->data =    $collabaratorArr;
                     //preparing response in the form of json
                    return  $response = CommonUtility::prepareResponse($responseBean,"json");
                }else if(count($collabaratorTokenData)>0 && $collabaratorTokenData[0]['Status']==0){
                    $accesstoken =  $this->GeneratingAccesstoken($collabaratorId);
                    $collabaratorArr=array('Id'=>$collabaratorId,'username'=>$getcollaboratorData[0]['UserName'],"token"=>$accesstoken,"ProfilePicture"=>$collaboratorProfileData['ProfilePicture'],"Email"=>$collaboratorProfileData['Email']);
                    $browserType=$_SERVER['HTTP_USER_AGENT'];
                    $getLastId = ServiceFactory::getCollaboratorServiceInstance()->saveCollabaratortokenData($accesstoken,$collabaratorId,$browserType,$remembermeStatus);
                    $responseBean = new ResponseBean;
                    $responseBean->status = ResponseBean::SUCCESS;
                    $responseBean->statusCode = ResponseBean::SUCCESS;
                    $responseBean->message = "success";
                    $responseBean->data =    $collabaratorArr;
                     //preparing response in the form of json
                    return  $response = CommonUtility::prepareResponse($responseBean,"json");
                }
                   
           }else{
                    $response='failure';
                    $responseBean = new ResponseBean;
                    $responseBean->status = ResponseBean::FAILURE;
                    $responseBean->statusCode = ResponseBean::SUCCESS;
                    $responseBean->message = "FAILURE";
                    $responseBean->data =    $response;
                     //preparing response in the form of json
                    return  $response = CommonUtility::prepareResponse($responseBean,"json");
           }
       
            
        }  catch (\Throwable $th) { 
             Yii::error("SiteController:actionUserAuthentication::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
              //preparing response in the form of json
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        } 
    }
 
    /**
     * @Description Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {  
       Yii::$app->user->logout();
        return $this->goHome();
    }
    
    /**
     * @author Padmaja
     * @Description This is update active Collabarator status to inActive when  logout
     * @return type json object
     * 
     */ 
    public function actionUpdateCollabaratorStatus(){
        try{
            $collabaratorJson = json_decode(file_get_contents("php://input"));
            $collabaratorToken=$collabaratorJson->userInfo->token;
            //updating accesstoken status when click on logout in AccessTokenCollection
            $updateStatus  = ServiceFactory::getCollaboratorServiceInstance()->updateStatusCollabarator($collabaratorToken);
            $responseBean     = new ResponseBean;
            $responseBean->statusCode = ResponseBean::SUCCESS;
            $responseBean->message = "success";
            $responseBean->data =    $updateStatus;
            //preparing response in the form of json
            return  $response = CommonUtility::prepareResponse($responseBean,"json");
        }  catch (\Throwable $th) { 
             Yii::error("SiteController:actionUpdateCollabaratorStatus::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
              //preparing response in the form of json
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        } 
       
        
    } 
    /**
     * @Description Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @Description Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * @Description Signs user up.
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

    /**
     * @Description Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * @Description Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    /**
     * @Description Get Collaborators from sql table and insert into mongo document.
     *
     * @author Anand Singh
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionCollaborators(){
          try{
            $coll =  new Collaborators();
        $collaborators = $coll->getCollabrators();
        $response=  TinyUserCollection::createUsers($collaborators);
        $responseBean = new ResponseBean;
        $responseBean->statusCode = ResponseBean::SUCCESS;
        $responseBean->message = "success";
        $responseBean->data = $collaborators;
        $response = CommonUtility::prepareResponse($responseBean,"json");
        return $response;   
        }  catch (\Throwable $th) { 
             Yii::error("SiteController:actionCollaborators::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        } 
    }
    
    public function actionInsertCollaborators(){
          try{
        $collaborators = User::insertCollabrators(10000);
        $responseBean = new ResponseBean;
        $responseBean->statusCode = ResponseBean::SUCCESS;
        $responseBean->message = "success";
        $responseBean->data = $collaborators;
        $response = CommonUtility::prepareResponse($responseBean,"json");
        return $response;   
        }  catch (\Throwable $th) { 
             Yii::error("SiteController:actionInsertCollaborators::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        } 
    }
      /**
     * @author Padmaja
     * @Description This is used to showing search results
     * @return type json object
     * 
     */ 
    public function actionGlobalSearch(){
        try{
            $postData = json_decode(file_get_contents("php://input"));
            $searchFlag=!empty($postData->searchFlag)?$postData->searchFlag:""; 
            $projectId=!empty($postData->projectId)?$postData->projectId:"";
            $userId=!empty($postData->userInfo->Id)?$postData->userInfo->Id:"";
            $pName=!empty($postData->pName)?$postData->pName:"";
            $pageLength=Yii::$app->params['pageLength'];
            //fetching search results by search string 
            $searchData = CommonUtility::getAllDetailsForSearch($postData->searchString,$postData->page,$searchFlag,$projectId,$pageLength,$userId,$pName); 
            if(empty($searchData['mainData']['ticketCollection']) && empty($searchData['mainData']['ticketComments']) && empty($searchData['mainData']['ticketArtifacts'])&& empty($searchData['mainData']['tinyUserData'])){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "no result found";
                $responseBean->data = $searchData;
                 //preparing response in the form of json
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
           
            }else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $searchData;
                 //preparing response in the form of json
                $response = CommonUtility::prepareResponse($responseBean,"json");
            }
            return $response;
        } catch (\Throwable $th) { 
             Yii::error("SiteController:actionGlobalSearch::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message =ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
               //preparing response in the form of json
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
   /**
    * @author Padmaja
    *  @Description This is used for verifying projects is exists or not
    * @param type 
    * @return array
    */
    public function actionVerifyingProjectName(){
        try{
            $postData = json_decode(file_get_contents("php://input"));
            $getProjectDetails=ServiceFactory::getCollaboratorServiceInstance()->verifyProjectName($postData->projectName);
           if(!empty($getProjectDetails) || empty($getProjectDetails)){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $getProjectDetails;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $getProjectDetails;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }
            return $response;
         } catch (\Throwable $th) { 
             Yii::error("SiteController:actionVerifyingProjectName::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message =  $th->getMessage();
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
     /**
    * @author Padmaja
    * @Description This is used for saving project details
    * @param type 
    * @return array
    */
    public function actionSaveProjectDetails(){
        try{
            $postData = json_decode(file_get_contents("php://input")); 
            $fileExt=!empty($postData->fileExtention)?$postData->fileExtention:"";
            $returnId=ServiceFactory::getCollaboratorServiceInstance()->savingProjectDetails($postData->projectName,$postData->description,$postData->userInfo->Id,$postData->projectLogo);
            if($returnId!='failure'){
                $projectId=$returnId;
                if (strpos($postData->projectLogo,'assets') !== false) {
                  $logo=$postData->projectLogo;
                } else {
                   $extractUrl= explode('projectlogo/',$postData->projectLogo);
                   $projectLogoPath = Yii::$app->params['ProjectRoot']. Yii::$app->params['projectLogo'] ;
                    if (file_exists($projectLogoPath."/".$extractUrl[1])) {
                        rename($projectLogoPath . "/" . $extractUrl[1],$projectLogoPath . "/" . $postData->projectName."_".$returnId.".$fileExt");
                        $logo=$postData->projectName."_".$returnId.".$fileExt";
                    } else {
                        error_log("not existeddddddddd");
                    }
                
                }
                ServiceFactory::getCollaboratorServiceInstance()->updateProjectlogo($projectId,$logo);
                $getStatus=ServiceFactory::getCollaboratorServiceInstance()->savingProjectTeamDetails($projectId,$postData->userInfo->Id);
             }
             if($getStatus == 'failure' || $returnId=='failure'){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $projectId;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }else{
                EventTrait::saveEvent($projectId,"Project",$projectId,"created","create",$postData->userInfo->Id,[array("ActionOn"=>"projectcreation","OldValue"=>0,"NewValue"=>(int)$projectId)]); 
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $projectId;
                $response = CommonUtility::prepareResponse($responseBean,"json");
         
            }
            return $response;
        } catch (\Throwable $th) { 
             Yii::error("SiteController:actionSaveProjectDetails::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
        /**
    * @author Padmaja
     * @Description This is used for get all project details
    * @param type 
    * @return array
    */
    public function actionGetAllProjectsByUser(){
        try{
            $postData = json_decode(file_get_contents("php://input"));
            $projectFlag=!empty($postData->projectFlag)?$postData->projectFlag:"";
            $limit=!empty($postData->limit)?$postData->limit:"";
            $projectId=!empty($postData->ProjectId)?$postData->ProjectId:"";
            $activityDropdownFlag=!empty($postData->activityDropdownFlag)?$postData->activityDropdownFlag:"";
            $projectInfo = ServiceFactory::getStoryServiceInstance()->getProjectDetailsForDashboard($postData);
            $totalProjectCount=ServiceFactory::getCollaboratorServiceInstance()->getTotalProjectCount($postData->userInfo->Id);
   
            if(!empty($projectInfo['ProjectwiseInfo']) || empty($projectInfo['ProjectwiseInfo'])){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                if(!empty($projectInfo['ProjectwiseInfo']) || !empty($projectInfo['ActivityData'])){
                    $responseBean->data = $projectInfo;
                }else{
                     $responseBean->data = "No Results Found";
                }
                $responseBean->totalProjectCount = $totalProjectCount;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $projectInfo;
                $responseBean->totalProjectCount = $totalProjectCount;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }
            return $response;
            
        } catch (\Throwable $th) { 
             Yii::error("SiteController:actionGetAllProjectsByUser::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = $th->getMessage();
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        } 
        
    }
         /**
    * @author Padmaja
   * @Description This is used for showing list of projects in dropdoown
    * @param type 
    * @return array
    */
    public function actionGetProjectNameByUserid(){
       try{
            $postData = json_decode(file_get_contents("php://input"));
            $userId=!empty($postData->userId)?$postData->userId:"";
            $projectsInfo=ServiceFactory::getCollaboratorServiceInstance()->getProjectNameByUserId($userId);
              if(!empty($projectsInfo)){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $projectsInfo;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $projectsInfo;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionGetProjectNameByUserid::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
             /**
    * @author Padmaja
   * @Description This is used for appending image 
    * @param type 
    * @return array
    */
    public function actionGetProjectImage(){
       try{
            $postData = json_decode(file_get_contents("php://input"));
            if(!empty($postData->logoName)){
                $projectsInfo=ServiceFactory::getCollaboratorServiceInstance()->saveProjectLogo($postData->logoName);    
            }
              if(!empty($projectsInfo)){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $projectsInfo;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $projectsInfo;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }
            return $response;
            
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionGetProjectImage::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    /**
    * @author Padmaja
    * @Description This is used for updating project Details
    * @param type 
    * @return array
    */
    public function actionUpdateProjectDetails(){
        try{
            $postData = json_decode(file_get_contents("php://input")); 
            $fileExt=!empty($postData->fileExtention)?$postData->fileExtention:"";
            $projectId=!empty($postData->projectId)?$postData->projectId:"";
            $updateStatus=ServiceFactory::getCollaboratorServiceInstance()->updatingProjectDetails($postData->projectName,$postData->description,$fileExt,$postData->projectLogo,$projectId);
            if($updateStatus=='success'){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $updateStatus;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $updateStatus;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }
             return $response;
        } catch (Exception $ex) {
            Yii::log("SiteController:actionUpdateProjectDetails::".$ex->getMessage()."--".$ex->getTraceAsString(), 'error', 'application');
    }
        
    }
   /**
    * @author Padmaja
    * @Description This is used for get project dashboard details
    * @param type 
    * @return array
    */
    public function actionGetProjectDashboardDetails(){
        try{
            $postData = json_decode(file_get_contents("php://input"));
            error_log("111111111111111111111--------");
            $projectdetails=ServiceFactory::getCollaboratorServiceInstance()->getProjectDashboardDetails($postData->projectName,$postData->projectId,$postData->userInfo->Id,$postData->page);
            if(!empty($projectdetails)){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $projectdetails;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $projectdetails;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }
             return $response;
             
        } catch (\Throwable $th) { 
             Yii::error("SiteController:actionGetProjectDashboardDetails::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = $th->getMessage();
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        } 
    }
    /**
    * @author Padmaja
    * @Description This is used for get all activities for project dashboard
    * @param type 
    * @return array
    */
    public function actionGetAllActivitiesForProjectDashboard(){
        try{
            $postData = json_decode(file_get_contents("php://input"));
            $projectdetails=ServiceFactory::getCollaboratorServiceInstance()->getAllActivities($postData);
            if(!empty($projectdetails)){
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $projectdetails;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $projectdetails;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }
             return $response; 
        }  catch (\Throwable $th) { 
             Yii::error("SiteController:getAllActivitiesForProjectDashboard::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = $th->getMessage();
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        } 
        
    }
/*Pra
		 /**
    * @author Praveen
    * @description This is used for create repository via subversion
    * @param type 
    * @return array
    */
    /*public function actionCreateRepository(){
       try{
            $postData = json_decode(file_get_contents("php://input"));
            error_log("------".print_r($postData,1));
             error_log("------".$postData->repName);
            $ch = curl_init();
            $postfields = array();
            $postfields['projectName'] = $postData->repName;
            error_log("------".$postfields['projectName']);
            //$postfields['field2'] = urlencode('value2');
            curl_setopt($ch, CURLOPT_URL, "http://10.10.73.16/test.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            
            $result = curl_exec($ch);
            //$info=curl_exec($ch);
            //$info = curl_getinfo($ch);
            error_log("-curl response-".print_r($result,1));
            
            
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $result;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionGetProjectNameByUserid::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    
    /**
    * @author Praveen
    * @description This is used for for create users via subversion
    * @param type 
    * @return array
    */
    /*public function actionCreateUser(){
       try{
            $postData = json_decode(file_get_contents("php://input"));
            error_log("------".print_r($postData,1));
             error_log($postData->password."------".$postData->userName);
            $ch = curl_init();
            $post_data = array(
                'username' => $postData->userName,
                'password' => $postData->password,
                'projectname' => $postData->repName,
                'role' => $postData->role
            );
            $postfields = array();
            curl_setopt($ch, CURLOPT_URL, "http://34.194.239.160:81/user.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            
            $result = curl_exec($ch);
            //$info=curl_exec($ch);
            //$info = curl_getinfo($ch);
            error_log("-curl user response-".print_r($result,1));
            
            
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $result;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            /*}else{
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::FAILURE;
                $responseBean->message = "failure";
                $responseBean->data = $projectsInfo;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            }*/
    /*pra        return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionGetProjectNameByUserid::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    
    /**
    * @author Praveen
    * @description This is used for for create users via subversion
    * @param type 
    * @return array
    */
   /* public function actionShowSvnlog(){
       try{
            $postData = json_decode(file_get_contents("php://input"));
            error_log("------".print_r($postData,1));
             error_log("------".$postData->repName);
            $ch = curl_init();
           
            $postfields = array();
            $postfields['projectName'] = $postData->repName;
            error_log("------".$postfields['projectName']);
            //$postfields['field2'] = urlencode('value2');
            curl_setopt($ch, CURLOPT_URL, "http://10.10.73.16/log.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            
            $result = curl_exec($ch);
            //$info=curl_exec($ch);
            //$info = curl_getinfo($ch);
            error_log("-curl response-".print_r($result,1));
            
            
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $result;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionGetProjectNameByUserid::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    
    
    /**
    * @author Praveen
    * @description This is used for for create users via subversion
    * @param type 
    * @return array
    */
    /*public function actionShowlog(){
       try{
            $response ="test cural call";
            error_log("-----dsfds----dfds--".$response);
            //return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionGetProjectNameByUserid::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
           
*/

 
    
    //Subversion code start
    /**
    * @author Praveen
    * @Description This is used for create repository via subversion
    * @param type 
    * @return array
    */
    public function actionCreateRepository(){
       try{
            $postData = json_decode(file_get_contents("php://input"));
            $ch = curl_init();
           
            $postData->repName = preg_replace("/\s*/", "", $postData->repName);
            $postfields = array();
            $postfields['projectName'] = $postData->repName;
            $postfields['username'] = $postData->userData[0]->userName;
            $postfields['password'] = $postData->userData[0]->password;
            $postfields['role'] = $postData->userData[0]->role;
            curl_setopt($ch, CURLOPT_URL, Yii::$app->params['SVNServerURL']."test.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            
            $result = curl_exec($ch);
            $data = json_decode($result,true);
            if($data['status'] == "success"){
                 error_log("asssssssssssssss------".$data['status']);
               ServiceFactory::getProjectServiceInstance()->updateRepositoryStatus($postData->projectId);
               $getStatus=ServiceFactory::getProjectServiceInstance()->saveUserPermissions($postData->projectId,$postData->userData);
            }
            
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data =  $data ;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionCreateRepository::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    
    /**
    * @author Praveen
    * @Description This is used for for create users via subversion
    * @param type 
    * @return array
    */
    public function actionCreateUser(){
       try{
            $postData = json_decode(file_get_contents("php://input"));
            error_log("------".print_r($postData,1));
            $ch = curl_init();
            $postData->projectName = preg_replace("/\s*/", "", $postData->projectName);
            $postfields = array();
            $postData->projectName = preg_replace("/\s*/", "", $postData->projectName);
            $postfields['userData'] = json_encode($postData->userData);
            $postfields['projectName'] = $postData->projectName;
            
            curl_setopt($ch, CURLOPT_URL, Yii::$app->params['SVNServerURL']."user.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            
            $result = curl_exec($ch);
            $getStatus=ServiceFactory::getProjectServiceInstance()->saveUserPermissions($postData->projectId,$postData->userData);
            
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $result;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionCreateUser::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    
    /**
    * @author Praveen
    * @Description This is used for for create users via subversion
    * @param type 
    * @return array
    */
    public function actionShowSvnlog(){
       try{
            $postData = json_decode(file_get_contents("php://input"));
            $data = array();
            $ch = curl_init();
            $userPermission = ServiceFactory::getProjectServiceInstance()->getUserPermissions($postData->ProjectId,$postData->userId);
            if(is_array($userPermission)){
            $postData->repName = preg_replace("/\s*/", "", $postData->repName);
            $postfields = array();
            $postData->repName = preg_replace("/\s*/", "", $postData->repName);
            $postfields['projectName'] = $postData->repName;
            $postfields['username'] = $postData->userName;
            $postfields['password'] = $postData->password;
            curl_setopt($ch, CURLOPT_URL, Yii::$app->params['SVNServerURL']."log.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            
            $result = curl_exec($ch);
            $data = json_decode($result,true);
            if(!empty($data)){
            foreach($data as $key=>$value){
                $time = strtotime($value["date"]);
                $dateInLocal = date("M-d-Y H:i:s", $time);
                $timeInLocal = date("H:i:s", $time);
                $data[$key]["DateTimeString"]=$dateInLocal;
                $userProfile = TinyUserCollection::getMiniUserDetailsByUserName($value["author"]);
                $data[$key]["ProfilePic"]=$userProfile["ProfilePicture"];
            }
            }
         
            }
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data = $data;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionShowSvnlog::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    
    /**
     * 
     * @return type
     * @Description Gets Inner directories and files for creating a folder explorer
     */
    public function actionGetRepositoryStructure(){
        try{
            $postData = json_decode(file_get_contents("php://input"));
           $data=array();
            $postData->directory = preg_replace("/\s*/", "", $postData->directory);
            $postfields = array();
            $postfields['directory'] = $postData->directory;
            $postfields['username'] = $postData->userName;
            $postfields['password'] = $postData->password;
            $userPermission = ServiceFactory::getProjectServiceInstance()->getUserPermissions($postData->ProjectId,$postData->userId);
            if(is_array($userPermission)){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Yii::$app->params['SVNServerURL']."getRepo.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            
            $result = curl_exec($ch);
            $data = json_decode($result,true);
            foreach($data as $key=>$eachDir){
                $userProfile = TinyUserCollection::getMiniUserDetailsByUserName($eachDir["last_author"]);
                $data[$key]["ProfilePic"]=$userProfile["ProfilePicture"];
            }
            
            }
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data =  $data ;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionGetRepositoryStructure::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    /**
     * 
     * @return type
     * @Description Creates a new folder under current directory in repository
     */
    public function actionCreateFolder(){
        try{
            $postData = json_decode(file_get_contents("php://input"));
            error_log("------".print_r($postData,1));
            $ch = curl_init();
           
            $postData->curerntDirectory = preg_replace("/\s*/", "", $postData->curerntDirectory);
            $postData->newFolder = preg_replace("/\s*/", "", $postData->newFolder);
            $postfields = array();
            $postfields['curerntDirectory'] = $postData->curerntDirectory;
            $postfields['newFolder'] = $postData->newFolder;
            $postfields['userName'] = $postData->userName;
            $postfields['password'] = $postData->password;
            
            error_log("------".$postfields['curerntDirectory']);
            error_log("------".$postfields['newFolder']);
            curl_setopt($ch, CURLOPT_URL, Yii::$app->params['SVNServerURL']."createFolder.php");
            curl_setopt($ch, CURLOPT_USERPWD, "guest:guest");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            
            $result = curl_exec($ch);
            error_log("-curl response-".print_r($result,1));
            $data = json_decode($result,true);
            error_log("-cusdjhasjkdhj====se-".print_r($data,1));
            foreach($data as $key=>$eachDir){
                $userProfile = TinyUserCollection::getMiniUserDetailsByUserName($eachDir["last_author"]);
                $data[$key]["ProfilePic"]=$userProfile["ProfilePicture"];
            }
            
            
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data =  $data ;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionCreateRepository::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
    /**
     * 
     * @return type
     * @Description Gets project team for rendering in User Permissions tab
     */
    public function actionGetProjectTeam(){
        $postData = json_decode(file_get_contents("php://input"));
        $result = ServiceFactory::getCollaboratorServiceInstance()->getProjectTeam($postData->ProjectId);
        foreach($result as $key=>$user){
                $userPermission = ServiceFactory::getProjectServiceInstance()->getUserPermissions($postData->ProjectId,$user["Id"]);
                $result[$key]["role"] = (isset($userPermission["Permissions"]))?$userPermission["Permissions"]:"";
            }
            error_log("====ProjectTeam===>>".print_r($result,1));
        $responseBean = new ResponseBean;
        $responseBean->statusCode = ResponseBean::SUCCESS;
        $responseBean->message = "success";
        $responseBean->data = array("userData"=>$result,"svnUrl"=>Yii::$app->params['SVNServerURL'])  ;
        $response = CommonUtility::prepareResponse($responseBean,"json"); 
        return $response;
        
    }
    /**
     * 
     * @return type
     * @Description Gets user permission/access foe validating before navigating to repository page
     */
   public function actionGetRepoPemissionsAndAccess(){
        try{
            $postData = json_decode(file_get_contents("php://input"));
            error_log("------".print_r($postData,1));
//             error_log("------".$postData->repName);
            $data = ServiceFactory::getProjectServiceInstance()->getRepoPermissionsAndAccess($postData->ProjectId,$postData->userId);
            
            
                $responseBean = new ResponseBean;
                $responseBean->statusCode = ResponseBean::SUCCESS;
                $responseBean->message = "success";
                $responseBean->data =  $data ;
                $response = CommonUtility::prepareResponse($responseBean,"json"); 
            return $response;
       } catch (\Throwable $th) {
            Yii::error("SiteController:actionGetRepoPemissionsAndAccess::" . $th->getMessage() . "--" . $th->getTraceAsString(), 'application');
             $responseBean = new ResponseBean();
             $responseBean->statusCode = ResponseBean::SERVER_ERROR_CODE;
             $responseBean->message = ResponseBean::SERVER_ERROR_MESSAGE;
             $responseBean->data = [];
             $response = CommonUtility::prepareResponse($responseBean,"json");
             return $response;
        }
    }
           

}
?>
