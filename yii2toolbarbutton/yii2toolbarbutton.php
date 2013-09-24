<?php

 /**
 * This class is merely used to publish a TOC based upon the headings within a defined container
 * @copyright Frenzel GmbH - www.frenzel.net
 * @link http://www.frenzel.net
 * @author Philipp Frenzel <philipp@frenzel.net>
 *
 */

namespace yii2toolbarbutton;

use Yii;

use yii\base\Model;
use yii\base\View;
use yii\base\InvalidConfigException;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

use yii\base\Widget as Widget;

class yii2toolbarbutton extends Widget
{

    /**
     * @var array list of slides in the imageslider. Each array element represents a single
     * slide with the following structure:
     *
     * ```php
     * array(
     *     // required, slide content (HTML), such as an image tag
     *     'action' => '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-01.jpg"/>',
     * )
     * ```
     */
    public $items = array();

    /**
    * @var array the HTML attributes (name-value pairs) for the field container tag.
    * The values will be HTML-encoded using [[Html::encode()]].
    * If a value is null, the corresponding attribute will not be rendered.
    */
    public $options = array(
        'class' => 'btToolbarWrap',
    );


    /**
     * can contain all configuration options
    * @var array all attributes that be accepted by the plugin, check docs!
    * visible_items
    * scrolling_items
    * orientation
    * circular
    * autoscroll
    * interval
    * direction
    */
    public $clientOptions = array(
    );

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        //checks for the element id
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::beginTag('div', $this->options) . "\n";            
            echo $this->renderItems() . "\n";                    
        echo Html::endTag('div') . "\n";
        $this->registerPlugin();
    }

    /**
    * Registers a specific dhtmlx widget and the related events
    * @param string $name the name of the dhtmlx plugin
    */
    protected function registerPlugin()
    {
        $id = $this->options['id'];

        //get the displayed view and register the needed assets
        $view = $this->getView();
        yii2toolbarbuttonAsset::register($view);

        $js = array();
        
        $className = $this->options['class'];
        
        $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js[] = "jQuery('#$id').toolbar($options);";
        
        $view->registerJs(implode("\n", $js),View::POS_READY);
    }

    /**
     * Renders carousel items as specified on [[items]].
     * @return string the rendering result
     */
    public function renderItems()
    {
        $items = array();
        for ($i = 0, $count = count($this->items); $i < $count; $i++) {
            $items[] = $this->renderItem($this->items[$i], $i);
        }
        return Html::tag('div', implode("\n", $items), array('class' => 'als-wrapper'));
    }

    /**
     * Renders a single carousel item
     * @param string|array $item a single item from [[items]]
     * @param integer $index the item index as the first item should be set to `active`
     * @return string the rendering result
     * @throws InvalidConfigException if the item is invalid
     */
    public function renderItem($item, $index)
    {
        if (is_string($item)) {
            $content = $item;
            $caption = null;
        } elseif (isset($item['action'])) {
            $content = $item['action'];
            $caption = ArrayHelper::getValue($item, 'icon');
            if ($caption !== null) {
                $caption = Html::tag('i', '', array('class' => 'icon icon-wrench'));
            }            
        } else {
            throw new InvalidConfigException('The "action" option is required.');
        }

        Html::addCssClass($options, 'item');
        if ($index === 0) {
            Html::addCssClass($options, 'active');
        }

        return Html::tag('li', $content . "\n" . $caption, array('class'=>'als-item'));
    }

}