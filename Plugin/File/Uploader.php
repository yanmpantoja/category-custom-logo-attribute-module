<?php declare(strict_types=1);

namespace CasaDoAdubo\CategoryCustomAttribute\Plugin\File;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Uploader extends Action
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function aroundSetAllowedExtensions(\Magento\Framework\File\Uploader $subject, \Closure $proceed, $extensions = [])
    {
        if (!in_array('svg', $extensions)) {
            $extensions[] = 'svg';
        }

        return $proceed($extensions);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
