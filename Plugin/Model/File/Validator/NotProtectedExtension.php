<?php

namespace CasaDoAdubo\CategoryCustomAttribute\Plugin\Model\File\Validator;

class NotProtectedExtension
{
    /**
     * Remove svg from Not Protected Extensions
     *
     * @param \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $extension
     * @param array $result
     *
     * @return array
     */
    public function afterGetProtectedFileExtensions(
        \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $extension,
        $result
    )
    {
        if (in_array('svg', $result)) {
            unset($result['svg']);
        }

        return $result;
    }
}
