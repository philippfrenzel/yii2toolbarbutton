<?php
/**
 * @link http://www.frenzel.net/
 * @author Philipp Frenzel <philipp@frenzel.net> 
 */

namespace yii2toolbarbutton;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class yii2toolbarbuttonAsset extends AssetBundle
{
    public $sourcePath = '@yii2toolbarbutton/assets';
    public $css = array(
        'css/jquery.toolbar.css'
    );
    public $js = array(
        'js/jquery.toolbar.min.js'
    );
    public $depends = array(
        'yii\web\JqueryAsset',
    );
}