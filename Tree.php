<?php

namespace jekiakazer0\nsjqtree;

use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\bootstrap\Widget;

class Tree extends Widget
{
    public $data = [];
    public $depthKey = self::DEPTH_KEY;
    public $labelKey = self::LABEL_KEY;
    public $idKey = self::ID_KEY;
    public $firstLevelzero = true;
    public $selectedNode;

    public $autoEscape = false;
    public $dragAndDropUrl = true;

    const DEPTH_KEY = 'depth';
    const LABEL_KEY = 'label';
    const ID_KEY = 'id';

    private $firstIteration = true;
    private $coefficient;
    private $_dataJson = '';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->data = (array)$this->data;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /**
         * @var \yii\web\AssetBundle $asset
         */
        $view = $this->getView();
        TreeAssets::register($view);

        if ($this->dataJson) {
            $options = $this->dataJson.
                ', autoEscape: '.(int)$this->autoEscape;

            if ($this->selectedNode) {
                $this->options['data-selected-node'] = $this->selectedNode;
                Html::addCssClass($this->options, 'ns-jqtree-js');
            }

            if ($this->dragAndDropUrl) {
                $options .= ', dragAndDrop: true';
                $this->options['data-dnd-url'] = $this->dragAndDropUrl;
            }

            $js = "$('#{$this->options['id']}').tree({{$options}});";
            $view->registerJs($js);

            echo Html::tag('div', '', $this->options)."\n";
        }
    }


    /**
     * build data json string
     * @return string
     */
    protected function getDataJson()
    {
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
}
