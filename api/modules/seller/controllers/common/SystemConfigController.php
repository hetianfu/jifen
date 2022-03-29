<?php

namespace api\modules\seller\controllers\common;

use api\modules\auth\ApiAuth;
use api\modules\seller\controllers\BaseController;
use api\modules\seller\models\forms\SystemConfigQuery;
use api\modules\seller\models\forms\SystemConfigTabModel;
use api\modules\seller\models\forms\SystemConfigTabQuery;
use api\modules\seller\models\forms\SystemConfigTabValueModel;
use api\modules\seller\models\forms\SystemGroupDataQuery;
use api\modules\seller\service\common\SystemConfigService;
use api\modules\seller\service\common\SystemGroupService;
use fanyou\common\WxPayment;
use fanyou\components\SystemConfig;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\EnvEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\DoTaskInit;
use fanyou\tools\StringHelper;
use Yii;
use yii\base\Exception;
use yii\db\Connection;
use yii\web\HttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class SystemConfigController
 * @package api\modules\seller\controllers\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/6/1 9:58
 */
class SystemConfigController extends BaseController
{
    private $service;
    private $systemConfig;
    private $groupConfig;

    public function init()
    {
        parent::init();
        $this->service = new SystemConfigService();
        $this->systemConfig = new SystemConfig();
        $this->groupConfig = new SystemGroupService();

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-system-config-page', 'store-drive', 'get-env-config', 'update-env-config', 'get-common-config-list', 'test-db']
        ];
        return $behaviors;
    }
    /*********************SystemConfigTab模块控制层************************************/

    /**
     * 是否阿里云存储
     * @return mixed
     * @throws HttpException
     */
    public function actionStoreDrive()
    {
        return $_ENV['STORE_IMAGE_DRIVE'];
    }

    /**
     * 获取全局配置
     * @return array
     * @throws FanYouHttpException
     */
    public function actionGetEnvConfig()
    {
        if (file_exists(Yii::getAlias('@root') . '/.env')) {
            throw new FanYouHttpException(HttpErrorEnum::Unavailable_For_Legal_Reasons, '系统安全提示，，禁止访问！');
        }
        $file_path = Yii::getAlias('@root') . '/fanyou/common/.env';
        if (file_exists($file_path)) {
            $fp = fopen($file_path, "r");
            $str = "";
            while (!feof($fp)) {
                $thisLine = fgets($fp);
                $str .= $thisLine;//逐行读取。如果fgets不写length参数，默认是读取1k。
                if (strpos($thisLine, '=') !== false) {
                    $envList = EnvEnum::getMap();
                    foreach ($envList as $k => $v) {
                        if (strpos($thisLine, $k) !== false) {
                            $str = str_replace($k, "", $thisLine);
                            $str = str_replace('=', "", $str);
                            $a['name'] = $k;
                            $a['menuName'] = $v;
                            $a['value'] = trim($str);
                            $a['type'] = 'input';
                            if ($k == EnvEnum::STORE_IMAGE_DRIVE) {
                                $a['type'] = 'radio';
                                $a['parameter'] = [["key" => "oss", "value" => "阿里云"], ["key" => "local", "value" => "本地存储"]];
                                $a['desc'] = EnvEnum::STORE_IMAGE_DRIVE_DESC;
                            }
                            if ($k == EnvEnum::DB_ENABLE_CACHE) {
                                $a['type'] = 'switch';
                                $a['parameter'] = [["key" => 1, "value" => "开启"], ["key" => 0, "value" => "关闭"]];
                                $a['value'] = $a['value'] == 'true' ? 1 : 0;
                            }
                            $b[] = $a;
                        }
                    }
                }
            }
            fclose($fp);
        }
        return $b;
    }

    /**
     * 更新env文件
     * @return bool|int
     * @throws FanYouHttpException
     */
    public function actionUpdateEnvConfig()
    {
        if (file_exists(Yii::getAlias('@root') . '/.env')) {
            throw new FanYouHttpException(HttpErrorEnum::UNAUTHORIZED, '系统安全提示，禁止访问！');
        }
        $request = parent::getRequestPost(false, false);
        $dbConfig = array_column($request, 'value', 'name');

        try {
            $connection = new  Connection([
                'dsn' => 'mysql:host=' . $dbConfig['DB_DSN'] . ';dbname=' . $dbConfig['DB_NAME'],
                'username' => $dbConfig['DB_USERNAME'],
                'password' => $dbConfig['DB_PASSWORD'],
            ]);
            $connection->open();

            //初使化数据库
            $sql = file_get_contents(Yii::getAlias('@root') . '/docs/easymarket.sql');

            //把SQL语句以字符串读入$sql
            $a = explode(";", $sql); //用explode()函数把‍$sql字符串以“;”分割为数组
            foreach ($a as $b) { //遍历数组
                $c = $b . ";"; //分割后是没有“;”的，因为SQL语句以“;”结束，所以在执行SQL前把它加上
                if (!empty($b)) {
                    $add = $connection->createCommand($c); //执行SQL语句
                    $add->query();
                    if (stristr($b, 'SET FOREIGN_KEY_CHECKS = 1')) {
                        break;
                    }
                }
            }

            //初使化数据库

            //init task-task   domain
            $sql='select command, id from rf_task_task where 1=1 ';
            $sqlCommand=$connection->createCommand($sql);
            $taskList  = $sqlCommand->query()->readAll();
            $domain = Yii::$app->request->hostInfo;
            //订时任务初使化
            if (count($taskList)) {
                foreach ($taskList as $k => $v) {
                    $command = str_replace('http://you-domain', $domain, $v['command']);
                    $sql="update  rf_task_task set command = ".'\''.$command.'\''."  where id=".  $v['id'] ;
                    $updateCommand=$connection->createCommand($sql);
                    $updateCommand->query();
                }
            }
            //update -- basic - url domain
            $taBSql="update  rf_system_config_tab_value set value = ".'\''.$domain.'/\''."  where menu_name= ".'\''.BasicConfigEnum::BASIC_SITE .'\'';
            $updateCommand=$connection->createCommand($taBSql);
            $updateCommand->query();

            //update -- mini app config img  --- domain
            $sql='select value, id from rf_system_config_tab_value where id in (323,326,327)';
            $sqlCommand=$connection->createCommand($sql);
            $taskList  = $sqlCommand->query()->readAll();
            $domain = Yii::$app->request->hostInfo;
            //默认加载图初使化
            if (count($taskList)) {
                foreach ($taskList as $k => $v) {
                    if(strpos($v['value'],'http')===false){
                        $command = $domain.$v['value'];
                        $sql="update  rf_system_config_tab_value set value = ".'\''.$command.'\''."  where id=".  $v['id'] ;
                        $updateCommand=$connection->createCommand($sql);
                        $updateCommand->query();
                    }
                }
            }


            $connection->close();
        } catch (Exception $e) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '数据源配置错误');
        }

        $file_path = Yii::getAlias('@root') . '/fanyou/common/.env';
        $update_str = '';
        $fp = fopen($file_path, "r+");

        while (!feof($fp)) {
            $thisLine = fgets($fp);
            foreach ($request as $k => $a) {
                if (strpos($thisLine, $a['name']) !== false) {
                    if ($a['name'] == EnvEnum::DB_ENABLE_CACHE) {
                        $a['value'] = $a['value'] == 1 ? 'true' : 'false';
                    }
                    $str = str_replace($a['name'], "", $thisLine);
                    $str = trim(str_replace('=', "", $str));
                    $thisLine = str_replace($str, $a['value'], $thisLine) . "\r\n";
                    break;
                }
            }
            $update_str .= $thisLine;//逐行读取。如果fgets不写length参数，默认是读取1k。
        }
        fclose($fp);
        DoTaskInit::addInit($dbConfig['DB_DSN'],$dbConfig['DB_NAME'],$dbConfig['DB_USERNAME'],$dbConfig['DB_PASSWORD']);
        $result=file_put_contents(Yii::getAlias('@root') . '/.env', $update_str);
        return $result;
    }

    /**
     * 测试数据库
     * @return string
     * @throws FanYouHttpException
     */
    public function actionTestDb()
    {
//        if (!file_exists(Yii::getAlias('@root') . '/.env')) {
//            throw new FanYouHttpException(HttpErrorEnum::Unavailable_For_Legal_Reasons, '系统安全提示，，禁止访问！');
//        }
        if (isset($_ENV['DB_DSN'])) {
            throw new FanYouHttpException(HttpErrorEnum::Unavailable_For_Legal_Reasons, '系统安全提示，，禁止访问！');
        };

        try {
            $connection = new  Connection([
                'dsn' => 'mysql:host=' . Yii::$app->request->get('DB_DSN') . ';dbname=' . Yii::$app->request->get('DB_NAME'),
                'username' => Yii::$app->request->get('DB_USERNAME'),
                'password' => Yii::$app->request->get('DB_PASSWORD'),
            ]);
            $connection->open();
            $connection->close();
            //配置成功后，修改env文件
            $file_path = Yii::getAlias('@root') . '/.env';
            $update_str = '';
            $fp = fopen($file_path, "r+");
            while (!feof($fp)) {
                $thisLine = fgets($fp);
                $update_str .= $thisLine;//逐行读取。如果fgets不写length参数，默认是读取1k。
            }
            file_put_contents($file_path, $update_str);
            return '连接成功';
        } catch (Exception $e) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '连接失败');
        }
    }

    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddTab()
    {

        $model = new SystemConfigTabModel();
        $model->setAttributes($this->getRequestPost());
        if ($this->service->countTab($model->eng_title)) {
            throw new  UnprocessableEntityHttpException("英文标题已存在");
        }
        if ($model->validate()) {
            return $this->service->addTab($model);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetTabPage()
    {
        $query = new SystemConfigTabQuery();
        $query->setAttributes($this->getRequestGet());

        if ($query->validate()) {

            return $this->service->getTabPage($query);
        } else {
            throw new UnprocessableEntityHttpException(parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetTabWithValue()
    {
        $id = Yii::$app->request->get('id');
        return $this->service->findTabWithValue($id);

    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateTabById()
    {

        $model = new SystemConfigTabModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($this->service->verifyEngTitleRepeat($model->id, $model->eng_title)) {
            throw new  UnprocessableEntityHttpException("英文标题已存在");
        }

        if ($model->validate()) {
            return $this->service->updateTabById($model);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelTabById()
    {

        $id = Yii::$app->request->get('id');
        return $this->service->deleteTabById($id);
    }


    /*********************SystemConfig模块控制层************************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddSystemConfig()
    {

        $model = new SystemConfigTabValueModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {

            return $this->service->addSystemConfig($model);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetSystemConfigPage()
    {
        $query = new SystemConfigQuery();
        $query->setAttributes($this->getRequestGet());

        if ($query->validate()) {

            return $this->service->getSystemConfigPage($query);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($query));
        }
    }

    /**
     * 获取所有列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetSystemConfigList()
    {
        $query = new SystemConfigQuery();
        $query->setAttributes($this->getRequestGet());

        if ($query->validate()) {
            return $this->service->getSystemConfigList($query);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetSystemConfigById()
    {
        $id = Yii::$app->request->get('id');
        return $this->service->getSystemConfigById($id);
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateSystemConfigById()
    {

        $model = new SystemConfigTabValueModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {

            $result = $this->service->updateSystemConfigById($model);

            return $result;
        } else {
            throw new UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     */
    public function actionDelSystemConfigById()
    {

        $id = Yii::$app->request->get('id');
        return $this->service->deleteById($id);
    }

    /**
     * 提交配置，使配置生效
     * @return mixed
     */
    public function actionSubmitConfigById()
    {
        $id = parent::getRequestId();

        return $this->service->cleanCacheByTabId($id);
    }

    /**
     * 获取 基础配置
     * @return mixed
     */
    public function actionGetBasicConfig()
    {

        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::BASIC_CONFIG, StatusEnum::SYSTEM);
    }

    /**
     * 修改 基础配置
     * @return mixed
     */
    public function actionUpdateBasicConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::BASIC_CONFIG);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取微信 公众号配置
     * @return mixed
     */
    public function actionGetWxMpConfig()
    {
        //   print_r($this->systemConfig->getConfigInfo(true, SystemConfigEnum::WX_MP, StatusEnum::APP));exit;
        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::WX_MP, StatusEnum::APP);
    }

    /**
     * 修改微信 公众号配置
     * @return mixed
     */
    public function actionUpdateWxMpConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::WX_MP);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取微信支付配置
     * @return mixed
     */
    public function actionGetWxPayConfig()
    {
        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::WX_PAY, StatusEnum::APP);

    }

    /**
     * 修改微信支付配置
     * @return mixed
     */
    public function actionUpdateWxPayConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::WX_PAY);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取微信小程序配置
     * @return mixed
     */
    public function actionGetWxMiniAppConfig()
    {
        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::WX_MINI_APP, StatusEnum::APP);

    }

    /**
     * 修改微信小程序配置
     * @return mixed
     */
    public function actionUpdateWxMiniAppConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::WX_MINI_APP);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取 打印机配置
     * @return mixed
     */
    public function actionGetPrinterConfig()
    {

        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::PRINT_CONFIG, StatusEnum::SYSTEM);
    }

    /**
     * 修改 打印机配置
     * @return mixed
     */
    public function actionUpdatePrinterConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::PRINT_CONFIG);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取 打印机配置
     * @return mixed
     */
    public function actionGetAliOssConfig()
    {

        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::ALI_OSS, StatusEnum::SYSTEM);
    }

    /**
     * 修改 打印机配置
     * @return mixed
     */
    public function actionUpdateAliOssConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::ALI_OSS);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取积分配置
     * @return mixed
     */
    public function actionGetScoreConfig()
    {

        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::SCORE_CONFIG, StatusEnum::GROUP);

    }

    /**
     * 修改积分配置
     * @return mixed
     */
    public function actionUpdateScoreConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::SCORE_CONFIG);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取分销配置
     * @return mixed
     */
    public function actionGetDistributeConfig()
    {
        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::DISTRIBUTE_CONFIG, StatusEnum::GROUP);

    }

    /**
     * 修改分销配置
     * @return mixed
     */
    public function actionUpdateDistributeConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::DISTRIBUTE_CONFIG);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取快递配置
     * @return mixed
     */
    public function actionGetFreightConfig()
    {
        return $this->systemConfig->getConfigInfo(true, SystemConfigEnum::FREIGHT_CONFIG, StatusEnum::GROUP);

    }

    /**
     * 修改取快递配置
     * @return mixed
     */
    public function actionUpdateFreightConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::FREIGHT_CONFIG);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取小程序配置
     * @return mixed
     */
    public function actionGetIndexAppConfig()
    {
        $values = $this->systemConfig->getConfigInfo(true, SystemConfigEnum::INDEX_APP_CONFIG, StatusEnum::GROUP, true);

        $data = [];
        foreach ($values as $k => $v) {
            $r['id'] = $v['id'];
            $r['name'] = $v['info'];
            $r['type'] = $v['type'];
            $r['showOrder'] = $v['show_order'];
            $r['value'] = $v['value'];
            $r['status'] = $v['status'];
            $r['upload_type'] = $v['upload_type'];
            $r['desc'] = $v['desc'];

            if ($v['type'] == 'group') {
                $array = $this->groupConfig->getOneById($v['value']);
                if (!empty($array)) {
                    $a = ArrayHelper::toArray($array);
                    $a['cid'] = $v['id'];
                    $a['status'] = $v['status'];
                    $a['show_order'] = $v['show_order'];
                    $r['group_value'] = $a;
                    $data[] = $r;
                }
            } else {
                $data[] = $r;
            }
        }
        return $data;


    }

    public function actionUpdateIndexAppConfig()
    {
        $array = Yii::$app->request->post();
        $this->service->cleanCache(SystemConfigEnum::INDEX_APP_CONFIG);
        return $this->service->batchUpdateSystemConfigById($array);
    }

    /**
     * 获取首页配置
     * @return mixed
     */
    public function actionGetIndexPageConfig()
    {
        $values = $this->systemConfig->getConfigInfo(true, SystemConfigEnum::INDEX_PAGE, StatusEnum::GROUP, true);
        $data = [];
        foreach ($values as $k => $v) {
            $array = $this->groupConfig->getOneById($v['value']);

            //$array=$this->groupConfig->getOneById(158);

            if (!empty($array)) {
                $a = ArrayHelper::toArray($array);
                //    print_r($a);exit;
                $a['cid'] = $v['id'];
                $a['status'] = $v['status'];
                $a['show_order'] = $v['show_order'];
                $data[] = $a;
            }

        }
        return $data;
    }

    /**
     * 添加首页配置
     * @return int
     * @throws \Throwable
     */
    public function actionAddIndexPageConfig()
    {
        $info = $this->service->getOneTabByEngTitle(SystemConfigEnum::INDEX_PAGE);
        $tabId = $info['id'];
        $post = parent::getRequestPost(false);
        foreach ($post as $k => $v) {
            $model = new SystemConfigTabValueModel();
            $model->config_tab_id = $tabId;
            $model->menu_name = SystemConfigEnum::INDEX_PAGE;
            $model->info = $v['info'];
            $model->value = $v['id'];
            $model->type = 'group';
            $model->insert();
        }
        return count($post);
    }

    /**
     * 获取通用配置
     * @return mixed
     */
    public function actionGetCommonConfig()
    {
        $title = parent::getRequestId('title');
        $hiddenPart=    (($title==SystemConfigEnum::WX_PAY)  && ($_ENV['IS_SERVICE_PAY']=='true' )) ;
        $values = $this->systemConfig->getConfigInfo(true, $title, StatusEnum::GROUP, true);

        $data = [];
        if (empty($values)) {
            return $data;
        }
        foreach ($values as $k => $v) {
            $r = [];
            $r['id'] = $v['id'];
            $r['name'] = $v['info'];
            $r['type'] = $v['type'];
            $r['showOrder'] = $v['show_order'];
            $r['value'] = $v['value'];
            $r['status'] = $v['status'];
            $r['menu_name'] = $v['menu_name'];
            $r['upload_type'] = $v['upload_type'];
            $r['desc'] = $v['desc'];
            if (!empty($v['parameter'])) {
                $r['parameter'] = json_decode($v['parameter']);
            }
            if ($v['type'] == 'group') {
                $array = $this->groupConfig->getOneById($v['value']);
                $a = ArrayHelper::toArray($array);
                $gQuery = new SystemGroupDataQuery();
                $gQuery->gid = $v['value'];
                $a['list'] = $this->groupConfig->getSystemGroupDataList($gQuery);
                $a['cid'] = $v['id'];
                $a['status'] = $v['status'];
                $a['show_order'] = $v['show_order'];
                $r['group_value'] = $a;
                $data[] = $r;
            } else {
                $data[] = $r;
            }
        }
        if($hiddenPart){
            $hiddenArray=[];
            foreach ( $data as $key=>$d){
                if($d['menu_name']== 'mch_id'){
                    $hiddenArray[]= $d;
                }
                if($d['menu_name']== 'cert_path'){
                    $hiddenArray[]= $d;
                }
                if($d['menu_name']== 'key_path'){
                    $hiddenArray[]= $d;
                }
            }
            return  $hiddenArray;
        }
        return $data;
    }

    /**
     * 获取多条配置
     * @return mixed
     */
    public function actionGetCommonConfigList()
    {
        $title = parent::getRequestId('title');
        $tileList = explode(',', $title);
        $result = [];
        if (!count($tileList)) {
            return $result;
        }
        foreach ($tileList as $k => $title) {
            if ($title == 'aliOss') {
                if ($_ENV['STORE_IMAGE_DRIVE'] == 'local') {
                    continue;
                }
            }
            $values = $this->systemConfig->getConfigInfo(true, StringHelper::uncamelize($title), StatusEnum::GROUP, true);
            $data = [];
            if (empty($values)) {
                return $data;
            }
            foreach ($values as $k => $v) {
                $r = [];
                $r['id'] = $v['id'];
                $r['name'] = $v['info'];
                $r['type'] = $v['type'];
                $r['showOrder'] = $v['show_order'];
                $r['value'] = $v['value'];
                $r['status'] = $v['status'];
                $r['menu_name'] = $v['menu_name'];
                $r['upload_type'] = $v['upload_type'];
                $r['desc'] = $v['desc'];
                if (!empty($v['parameter'])) {
                    $r['parameter'] = json_decode($v['parameter']);
                }
                if ($v['type'] == 'group') {
                    $array = $this->groupConfig->getOneById($v['value']);
                    $a = ArrayHelper::toArray($array);
                    $gQuery = new SystemGroupDataQuery();
                    $gQuery->gid = $v['value'];
                    $a['list'] = $this->groupConfig->getSystemGroupDataList($gQuery);
                    $a['cid'] = $v['id'];
                    $a['status'] = $v['status'];
                    $a['show_order'] = $v['show_order'];
                    $r['group_value'] = $a;
                    $data[] = $r;
                } else {
                    $data[] = $r;
                }
                $result[$title] = $data;
            }

        }
        return $result;
    }

    public function actionUpdateCommonConfig()
    {
        $array = Yii::$app->request->post();
        $this->cleanAllRedis();
        return $this->service->batchUpdateSystemConfigById($array);
    }

    private function cleanAllRedis()
    {
        $this->service->cleanCache(SystemConfigEnum::INDEX_APP_CONFIG);
        $this->service->cleanCache(SystemConfigEnum::WX_MP);
        $this->service->cleanCache(SystemConfigEnum::WX_PAY);
        $this->service->cleanCache(SystemConfigEnum::WX_MINI_APP);
        $this->service->cleanCache(SystemConfigEnum::ALI_OSS);
        $this->service->cleanCache(SystemConfigEnum::INDEX_PAGE);
        $this->service->cleanCache(SystemConfigEnum::SCORE_CONFIG);
        $this->service->cleanCache(SystemConfigEnum::DISTRIBUTE_CONFIG);
        $this->service->cleanCache(SystemConfigEnum::PRINT_CONFIG);
        $this->service->cleanCache(SystemConfigEnum::PRINT_YLY_CONFIG);
        $this->service->cleanCache(SystemConfigEnum::BASIC_CONFIG);

        Yii::$app->cache->delete("fy-gt-do" .SystemConfigEnum::BASIC_CONFIG);
        Yii::$app->cache->delete("html_index_app_config");

    }

    /**
     * 获取通用子配置
     * @return mixed
     */
    public function actionGetCommonSonConfig()
    {
        $son = parent::getRequestId('son');
        $result = [];
        $title = parent::getRequestId('title');
        $values = $this->systemConfig->getConfigInfo(true, $title, StatusEnum::GROUP, true);

        $data = [];
        if (empty($values)) {
            return $data;
        }
        foreach ($values as $k => $v) {
            $r = [];
            $r['id'] = $v['id'];
            $r['name'] = $v['info'];
            $r['type'] = $v['type'];
            $r['showOrder'] = $v['show_order'];
            $r['value'] = $v['value'];
            $r['status'] = $v['status'];
            $r['menu_name'] = $v['menu_name'];
            $r['upload_type'] = $v['upload_type'];
            $r['desc'] = $v['desc'];
            if ($v['menu_name'] == $son) {
                if ($v['type'] == 'group') {
                    $array = $this->groupConfig->getOneById($v['value']);
                    $a = ArrayHelper::toArray($array);
                    $gQuery = new SystemGroupDataQuery();
                    $gQuery->gid = $v['value'];
                    $a['list'] = $this->groupConfig->getSystemGroupDataList($gQuery);
                    $a['cid'] = $v['id'];
                    $a['status'] = $v['status'];
                    $a['show_order'] = $v['show_order'];
                    $r['group_value'] = $a;
                    $data = $r;
                } else {
                    $data = $r;
                }
            }
        }
        return $data;

    }

}
/**********************End Of SystemConfig 控制层************************************/ 


