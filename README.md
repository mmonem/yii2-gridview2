yii2-gridview2
==============

yii2-gridview2 is a wrapper for the original Yii2 GridView with local model Create and Update features


## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

To install, either run

```
$ php composer.phar require mmonem/yii2-gridview2 "@dev"
```

or add

```
"mmonem/yii2-gridview2": "@dev"
```

to the ```require``` section of your `composer.json` file.

## Usage

1. In your view add the original Yii GridView as usual
1. Change the `use` directive from `yii\grid\GridView` to `mmonem\yii2gridview2\GridView;`
1. Add `editColumns` and `createUrl` parameters to the configuration array of
the GridView as the following:
    ```php
    'editColumns' => [
        'column_1',
         ['attr' =>'column_2', 'select' => [1 => 'Name 1', 2 => 'Name 2']],
        'column_3',
    ],
   'createUrl' => Url::to(['some-controller/createajax']),
    ```
1. In one of your controllers add the the action to be used for adding models
using AJAX. In the following example I make an action called `createajax` for
this. You need also to specify the ActiveRecord model class name. Don't forget
to fix permissions for that new action. 

    ```php
        public function actions()
        {
            return [
                'createajax' => [
                    'class' => 'mmonem\yii2gridview2\CreateAction',
                    'modelClass' => SomeModel::className()
                ],
            ];
        }
    ```

## Change Log

- **v0.0.1 2017-12-11**
    - Initial pre-alpha version

## License

**yii2-gridview2** is released under the BSD 3-Clause License. Refer to `LICENSE.md` for details.
