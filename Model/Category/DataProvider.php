<?php declare(strict_types=1);

namespace CasaDoAdubo\CategoryCustomAttribute\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{

    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = 'category_logo_image'; // custom image field

        return $fields;
    }
}
