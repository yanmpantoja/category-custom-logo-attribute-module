<?php declare(strict_types=1);

namespace CasaDoAdubo\CategoryCustomAttribute\Block\Html;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\UrlInterface as UrlInterfaceAlias;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Html page top menu block
 */
class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{
    protected $identities = [];
    protected $_menu;
    protected $registry;
    protected $_categoryRepository;
    protected $_storeManager;

    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        CategoryFactory $categoryFactory,
        CategoryRepository $categoryRepository,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->_categoryRepository = $categoryRepository;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
    }

    protected function _getHtml(
        Node $menuTree,
        $childrenWrapClass,
        $limit,
         $colBrakes = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            $categoryExplode = explode('-', $child->getId());
            $categoryIdFromChild = end($categoryExplode);

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $child->setClass($outermostClass);
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';

            $customCategory = $this->_categoryRepository->get($categoryIdFromChild);
            $logoImage = $customCategory->getData('category_logo_image');

            if ($logoImage) {
                $html .= '<img class="category_logo_image" src="' . $this->_storeManager->getStore()->getBaseUrl(UrlInterfaceAlias::URL_TYPE_MEDIA) . 'catalog/category/' . $customCategory->getData('category_logo_image') . '" alt="Logo">';
            }

            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                    $child->getName()
                ) . '</span></a>' . $this->_addSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit
                ) . '</li>';

            $itemPosition++;
            $counter++;
        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }
}
