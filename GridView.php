<?php

namespace mmonem\yii2gridview2;

/**
 *
 */
class GridView extends \yii\grid\GridView
{
    /**
     * @var bool
     */
    public $editColumns = [];

    public $createUrl;

    /**
     * Runs the widget.
     */
    public function run()
    {
        $this->layout = str_replace('{items}', '{localForm}{items}', $this->layout);
        parent::run();
    }

    public function renderSection($name)
    {
        switch ($name) {
            case '{localForm}':
                return $this->renderLocalForm();
            default:
                return parent::renderSection($name);
        }
    }

    public function renderLocalForm() {
        return $this->render('local-form', [
            'dataProvider' => $this->dataProvider,
            'editColumns' => $this->editColumns,
            'createUrl' => $this->createUrl,
        ]);
    }
}
