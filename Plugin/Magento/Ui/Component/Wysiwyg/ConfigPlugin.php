<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_MagefanCompatible
 * @copyright  Copyright (c) 2021 Landofcoder (https://landofcoder.com/)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */
namespace Lof\MagefanCompatible\Plugin\Magento\Ui\Component\Wysiwyg;

use Magefan\WysiwygAdvanced\Plugin\Magento\Ui\Component\Wysiwyg\ConfigPlugin as MagefanConfigPlugin;

/**
 * Class Config Plugin
 */
class ConfigPlugin extends MagefanConfigPlugin
{

    /**
     * Return WYSIWYG configuration
     *
     * @param \Magento\Ui\Component\Wysiwyg\ConfigInterface $configInterface
     * @param \Magento\Framework\DataObject|array|mixed|null $result
     * @return \Magento\Framework\DataObject
     */
    public function afterGetConfig(
        \Magento\Ui\Component\Wysiwyg\ConfigInterface $configInterface,
        $result = []
    ) {
        if (!$this->activeEditor || is_array($result) || !is_object($result) || !$result) {
            return $result;
        }

        // Get current wysiwyg adapter's path
        $editor = $this->activeEditor->getWysiwygAdapterPath();

        // Is the current wysiwyg tinymce v4?
        if (strpos($editor, 'tinymce4Adapter')) {

            if (($result->getDataByPath('settings/menubar')) || ($result->getDataByPath('settings/toolbar')) || ($result->getDataByPath('settings/plugins'))) {
                // do not override ui_element config (unsure if this is needed)
                return $result;
            }

            $settings = $result->getData('settings');

            if (!is_array($settings)) {
                $settings = [];
            }

            // configure tinymce settings
            $settings['menubar'] = true;
            $settings['image_advtab'] = true;

            $settings['plugins'] = 'advlist autolink code colorpicker directionality hr imagetools link media noneditable paste print table textcolor toc visualchars anchor charmap codesample contextmenu help image insertdatetime lists nonbreaking pagebreak preview searchreplace template textpattern visualblocks wordcount magentovariable magentowidget';

            $settings['toolbar1'] = 'magentovariable magentowidget | formatselect | styleselect | fontsizeselect | forecolor backcolor | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent';
            $settings['toolbar2'] = ' undo redo  | link anchor table charmap | image media insertdatetime | widget | searchreplace visualblocks  help | hr pagebreak';
            $settings['force_p_newlines'] = false;

            $settings['valid_children'] = '+body[style]';

            $result->setData('settings', $settings);
            return $result;
        } else { // don't make any changes if the current wysiwyg editor is not tinymce 4
            return $result;
        }
    }
}
