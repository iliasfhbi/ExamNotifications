<?php

require_once __DIR__ . "/../vendor/autoload.php";

include_once("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");

class ilExamNotificationsPlugin extends ilUserInterfaceHookPlugin {


    public function __construct()
    {
        parent::__construct();

        //  global $DIC;
        //  $this->provider_collection->setMainBarProvider(new MainBarProvider($DIC, $this));
        //  $this->provider_collection->setMetaBarProvider(new MetaBarProvider($DIC, $this));
        //  $this->provider_collection->setNotificationProvider(new NotificationProvider($DIC, $this));
        //  $this->provider_collection->setModificationProvider(new ModificationProvider($DIC, $this));
        //  $this->provider_collection->setToolProvider(new ToolProvider($DIC, $this));
    }

    /**
     * @return string
     */
    function getPluginName() {
        return 'ExamNotifications';
    }
}