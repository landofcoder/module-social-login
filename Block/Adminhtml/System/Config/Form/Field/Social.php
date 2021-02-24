<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_SocialLogin
 *
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\SocialLogin\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Lof\SocialLogin\Model\Config;

class Social extends Field
{

    /**
     * Render element value
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _renderValue(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->addClass('social_nestable');
        $elementId = $element->getHtmlId();
        $data      = Config::getSocialNetworks();
        $request   = json_decode($element->getValue());
        if($request && is_array($request)){ 
            foreach ($request as $value) {
                if($data && is_array($data)){ 
                    foreach ($data as $key1 => $value1) {
                        if ($value->id === $key1) {
                            unset($data[$key1]);
                        }
                    } 
                }
            }
        }
        if ($element->getTooltip()) {
            $html = '<td class="value with-tooltip">';
            $html .= $this->_getElementHtml($element);
            $html .= '<div class="tooltip"><span class="help"><span></span></span>';
            $html .= '<div class="tooltip-content">' . $element->getTooltip() . '</div></div>';
        } else {
            $html = '<td class="value">';
            $html .= $this->_getElementHtml($element);
        }
        if ($element->getComment()) {
            $html .= '<p class="note"><span>' . $element->getComment() . '</span></p>';
        }
        $html .= '<div class="cf nestable-lists lof-social-login">';
        $html .= ' <div class="dd" id="nestable"> ';
        $html .= '<span class="dd_tittle">Hidden</span>';
        if (empty($data)) {
            $html .= ' <div class="dd-empty"></div> ';
        } else {
            $html .= '    <ol class="dd-list">';
            foreach ($data as $key => $value) {
                $html .= '        <li class="dd-item" data-id="'.$key.'">';
                $html .= '            <div class="dd-handle '.$key.'-login"><span><i class="fa fa-'.$key.' icon-social" aria-hidden="true"></i>'.ucwords($value).'</span></div> ';
                $html .= '       </li> ';
            }
            $html .= '   </ol> ';
        }
        $html .= ' </div> ';
        $html .= ' <div class="dd" id="nestable2"> ';
        $html .= '<span class="dd_tittle">Show</span>';
        if (empty($request)) {
            $html .= ' <div class="dd-empty"></div> ';
        } else {
            $html .= '    <ol class="dd-list">';
            foreach ($request as $k => $v) {
                $html .= '        <li class="dd-item" data-id="'.$v->id.'">';
                $html .= '            <div class="dd-handle '.$v->id.'-login"><span><i class="fa fa-'.$v->id.' icon-social" aria-hidden="true"></i>'.ucwords($v->id).'</span></div> ';
                $html .= '       </li> ';
            }

            $html .= '   </ol> ';
        }
        $html .= ' </div> ';
        $html .= '</div>';

        $html .= "<script>
    require(['jquery','Lof_SocialLogin/js/jquery.nestable'], function($){
        var updateOutput = function(e)
        {
            var list   = e.length ? e : $(e.target),
            output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        }; 
        $('#nestable').nestable({
            group: 1,
            maxDepth: 1
        })
        .on('change', updateOutput); 
        $('#nestable2').nestable({
            group: 1,
            maxDepth: 1
        })
        .on('change', updateOutput); 
        updateOutput($('#nestable').data('output', $('#nestable-output')));
        updateOutput($('#nestable2').data('output', $('.social_nestable')));
    });
</script>";
        $html .= '</td>';
        return $html;
    }
}
