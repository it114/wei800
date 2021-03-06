<?php
class AdminUserConfig extends UserConfig
{
    
    const CATEGORY_SYSTEM_SITE = 100;
    const CATEGORY_SYSTEM_CACHE = 110;
    const CATEGORY_SYSTEM_ATTACHMENTS = 120;
    
    const CATEGORY_DISPLAY_UI = 200;
    
    /**
     * Returns the static model of the specified AR class.
     * @return AdminUserConfig the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public static function categoryLabels()
    {
        return array(
            self::CATEGORY_SYSTEM_SITE => '网站设置',
            self::CATEGORY_SYSTEM_CACHE => '缓存设置',
            self::CATEGORY_SYSTEM_ATTACHMENTS => '附件设置',
            self::CATEGORY_DISPLAY_UI => '界面元素',
        );
    }
    
    public function flushConfig()
    {
        return self::flushAllConfig($this->user_id);
    }
    
    public static function flushAllConfig($userID)
    {
        $userID = (int)$userID;
        if ($userID === 0) return false;
        
        $rows = app()->getDb()->createCommand()
            ->select(array('config_name', 'config_value'))
            ->from(TABLE_USER_CONFIG)
            ->where('user_id = :userid', array(':userid' => $userID))
            ->queryAll();
        
        if (empty($rows)) return false;
        
        $rows = CHtml::listData($rows, 'config_name', 'config_value');
        $data = "<?php\nreturn " . var_export($rows, true) . ';';
        $filename = self::cacheFilename($userID);
        return ($filename === false) ? false : file_put_contents($filename, $data);
    }
    
    public static function cacheFilename($userID)
    {
        $userID = (int)$userID;
        if ($userID === 0) return false;
        
        $filename = dp(sprintf('user_config_%d.php', $userID));
        return $filename;
    }
    
    public static function saveUserConfig($userID, $name, $value)
    {
        $userID = (int)$userID;
        $attributes = array('user_id'=>$userID, 'config_name'=>$name);
        $model = self::model()->findByAttributes($attributes);
        if ($model === null) return false;
        
        $model->config_value = $value;
        $result = $model->save(true, array('config_value'));
        $result && $model->flushConfig();
        return $result;
    }

    protected function beforeDelete()
    {
        throw new CException('系统参数不允许删除');
    }
}

