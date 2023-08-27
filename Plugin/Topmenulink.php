<?php

namespace CasaDoAdubo\CategoryCustomAttribute\Plugin;

use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Event\ObserverInterface;

class Topmenulink implements ObserverInterface
{
    protected $_categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->_categoryRepository = $categoryRepository;
    }

    public function afterGetHtml(\Magento\Theme\Block\Html\Topmenu $topmenu, $html)
    {
        $category = $this->_categoryRepository->get($categoryId, $storeId);

        $imageUrl = 'magento_media_url_path'.'catalog/category/'.$category->getCustomImage();

        $testArray = explode('<li  class="level0', $html);
        $search_pattern = '/(<a\s+[^>]*>)/i';

        foreach ($testArray as $key => $testItem) {
            $modified_html = preg_replace($search_pattern, '<img class="category-logo-image" data-role="image-element" src="" alt=""/>' . '$1', $testItem);
            $testArray[$key] = $modified_html;
        }

        $html = implode('<li  class="level0', $testArray);

        return $html;
    }

    public function execute(EventObserver $observer)
    {
        // TODO: Implement execute() method.
    }
}
