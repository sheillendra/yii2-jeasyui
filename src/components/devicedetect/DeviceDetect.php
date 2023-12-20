<?php

namespace common\components\devicedetect;

use Yii;
use Detection\MobileDetect;

class DeviceDetect extends \yii\base\Component {

    /**
     * @var MobileDetect
     */
    private $_mobileDetect;

    public function __call($name, $parameters) {
        return call_user_func_array(
                array($this->_mobileDetect, $name), $parameters
        );
    }

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function init() {
        $this->_mobileDetect = new MobileDetect();
        parent::init();

//        if ($this->_mobileDetect->isMobile()) {
//            Yii::$app->setComponents([
//                'view' => [
//                    'class' => 'yii\web\View',
//                    'theme' => [
//                        'pathMap' => [
//                            '@app/views' => [
//                                '@app/themes/fw7/views',
//                                '@app/themes/jeasyui/views',
//                            ],
//                            '@app/modules' => [
//                                '@app/themes/fw7/modules',
//                                '@app/themes/jeasyui/modules',
//                            ],
//                            '@app/widgets' => [
//                                '@app/themes/fw7/widgets',
//                                '@app/themes/jeasyui/widgets',
//                            ]
//                        ],
//                    ],
//                ]
//            ]);
//        }
    }

}
