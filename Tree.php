<?php

namespace jekiakazer0\nsjqtree;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\bootstrap\Widget;

/**
 * Class Tree
 * @package jekiakazer0\nsjqtree
 *
 * @author zer0 <zer0.stat@mail.ru>
 * @link https://github.com/jeki-aka-zer0
 */
class Tree extends Widget
{
    /**
     * @var string - name of theme asset
     */
    public $theme = self::ASSET_CLASS;
    /**
     * @var array|null - data to show. Can be empty if $modelName is set
     */
    public $data;
    /**
     * @var string - name of Nested Set model. Required if $data is empty
     */
    public $modelName;
    /**
     * @var array - node link
     */
    public $aUrl = ['update'];
    /**
     * @var bool - display nodes from the zero level or not
     */
    public $firstLevelzero = true;
    /**
     * @var int - id of active node
     */
    public $selectedNode;
    /**
     * @var string - nested set left key
     */
    public $leftKey = self::LEFT_KEY;
    /**
     * @var string - nested set right key
     */
    public $rightKey = self::RIGHT_KEY;
    /**
     * @var string - nested set depth key
     */
    public $depthKey = self::DEPTH_KEY;
    /**
     * @var string - node name key
     */
    public $labelKey = self::LABEL_KEY;
    /**
     * @var string - node id key
     */
    public $idKey = self::ID_KEY;
    /**
     * @var string - category id key in $_GET array
     */
    public $categoryGetKey = self::CATEGORY_GET_KEY;
    /**
     * @var bool - escape node name or not
     */
    public $autoEscape = false;
    /**
     * @var bool - enable nodes drag and drop
     */
    public $dragAndDropUrl = true;

    const CATEGORY_GET_KEY = 'category';
    const LEFT_KEY = 'lft';
    const RIGHT_KEY = 'rgt';
    const DEPTH_KEY = 'depth';
    const LABEL_KEY = 'label';
    const ID_KEY = 'id';

    const ASSET_CLASS = 'jekiakazer0\nsjqtree\TreeAssets';
    const DND_URL = 'dnd';

    const TREE_CLASS = 'ns-jqtree-js';
    const ITEM_CLASS = 'ns-jqtree__item';

    private $firstIteration = true;
    private $coefficient;
    private $_dataJson = '';


    /**
     * @inheritdoc
     */
    public function run()
    {
        /**
         * @var \yii\web\AssetBundle $asset
         */
        $view = $this->getView();
        $asset = $this->theme;
        JQTreeAssets::register($view);
        $asset::register($view);

        if ($dataJson = $this->getDataJson()) {
            $options = $dataJson.
                ', autoEscape: '.(int)$this->autoEscape;

            if ($selectedNode = $this->getSelectedNode()) {
                $this->options['data-selected-node'] = $selectedNode;
                Html::addCssClass($this->options, self::TREE_CLASS);
            }

            if ($this->dragAndDropUrl) {

                if ($this->dragAndDropUrl === true) {
                    $this->dragAndDropUrl = Url::to([self::DND_URL]);
                }

                $options .= ', dragAndDrop: true';
                $this->options['data-dnd-url'] = $this->dragAndDropUrl;
            }

            $js = "$('#{$this->options['id']}').tree({{$options}});";
            $view->registerJs($js);

            echo Html::tag('div', '', $this->options).PHP_EOL;
        }
    }


    /**
     * build data json string
     * @return string
     */
    private function getDataJson()
    {
        $this->prepateData();

        if (!$this->_dataJson && $this->data) {
            $currentLevel = $counter = 0;

            foreach ($this->data as $node) {
                $level = $this->getNodeLevel($node);

                if ($level === $currentLevel AND $counter > 0) {
                    $this->_dataJson .= '},';
                } elseif ($level > $currentLevel) {
                    $this->_dataJson .= ($level == 1 ? 'data:' : ',children:').'[';
                    $currentLevel += ($level - $currentLevel);
                } elseif ($level < $currentLevel) {
                    $multiplier = $currentLevel - $level;
                    $this->_dataJson .= str_repeat('}]', $multiplier).'},';
                    $currentLevel -= $multiplier;
                }

                $this->_dataJson .= "{label:'".($this->autoEscape ? Html::encode($node[$this->labelKey]) : $node[$this->labelKey])."', id: {$node[$this->idKey]}";

                $counter++;
            }

            if ($this->_dataJson) {
                $this->_dataJson .= str_repeat('}]', $level);
            }
        }

        return $this->_dataJson;
    }

    /**
     * get levels from 0 to n
     * @param array|ActiveRecord $node
     * @return mixed
     */
    private function getNodeLevel($node)
    {
        if (!$this->firstLevelzero) {
            return $node[$this->depthKey];
        }

        if ($this->firstIteration) {
            $this->coefficient = $node[$this->depthKey] - 1;
            $this->firstIteration = false;
        }

        return $node[$this->depthKey] - $this->coefficient;
    }

    /**
     * prepare data array
     * @throws InvalidConfigException
     */
    private function prepateData()
    {
        if (is_null($this->data) && !$this->data) {
            /**
             * @var \yii\db\ActiveRecord $modelName
             */
            if (!$this->modelName) {
                throw new InvalidConfigException('You must set data or model name.');
            } elseif (!$this->categoryGetKey) {
                throw new InvalidConfigException('CategoryGetKey parameter is required.');
            }

            $modelName = $this->modelName;
            $this->data = $modelName::find()->select([
                $this->idKey,
                $this->labelKey,
                $this->leftKey,
                $this->rightKey,
                $this->depthKey,
            ])
                ->orderBy($this->leftKey)
                ->asArray()
                ->all();

            foreach ($this->data as $key => $value) {
                $this->data[$key][$this->labelKey] = Html::a(
                    Html::encode($value[$this->labelKey]),
                    array_merge($this->aUrl, [$this->categoryGetKey => $value[$this->idKey]]),
                    [
                        'class' => [
                            self::ITEM_CLASS,
                        ]
                    ]);
            }

        } else {
            $this->data = (array)$this->data;
        }
    }

    /**
     * get selected node id
     * @return int
     */
    private function getSelectedNode()
    {
        if (is_null($this->selectedNode)) {
            $this->selectedNode = \Yii::$app->request->get($this->categoryGetKey);
        }

        return $this->selectedNode;
    }
}
