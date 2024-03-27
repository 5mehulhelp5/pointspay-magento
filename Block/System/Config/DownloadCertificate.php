<?php

namespace Pointspay\Pointspay\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class DownloadCertificate extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Pointspay_Pointspay::system/config/collect.phtml';

    /**
     * @var string
     */
    protected $buttonName = 'Button';

    /**
     * @var string
     */
    protected $actionPath = "admin/*/*";

    /**
     * @var string
     */
    protected $returnPath = 'adminhtml/system_config/edit/section/payment';

    /**
     * @var false|mixed|string
     */
    protected $virtualMethodCode;

    /**
     * @var string
     */
    protected $elementScopeId;

    /**
     * @var string
     */
    protected $elementScope;

    /**
     * @param Context $context
     * @param string $actionPath
     * @param string $returnPath
     * @param string $buttonName
     * @param string $template
     * @param array $data
     */
    public function __construct(
        Context $context,
        string  $actionPath,
        string  $returnPath,
        string  $buttonName,
        string  $template,
        array $data = []
    ) {
        $this->actionPath = $actionPath;
        $this->buttonName = $buttonName;
        $this->_template = $template;
        $this->returnPath = $returnPath;
        parent::__construct($context, $data);
    }

    /**
     * Remove scope label
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $this->elementScope = $element->getScope();
        $this->elementScopeId = !empty($element->getScopeId()) ? $element->getScopeId() : 0;
        $element->unsCanUseDefaultValue();
        if (strpos($element->getName(), 'merchant_certificate') !== false) {
            $htmlId = $element->getHtmlId();
            $intermediateScopeString = explode('_access_settings_merchant_certificate', $htmlId);
            $explodedByUnderscore = explode('_', reset($intermediateScopeString));
            $this->virtualMethodCode = end($explodedByUnderscore);
        }
        return parent::render($element);
    }

    /**
     * @return string
     */
    public function getButtonName()
    {
        return $this->buttonName;
    }

    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function getActionUrl()
    {
        return $this->getUrl($this->getActionPathProperty());
    }

    /**
     * @return string
     */
    public function getActionPathProperty()
    {
        return $this->actionPath;
    }

    /**
     * Generate collect button html
     *
     * @param string|null $uniqueId
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonHtml($uniqueId = null)
    {
        $button = $this->getLayout()->createBlock(
            Button::class
        )->setData(
            [
                'id' => $uniqueId ?: $this->filterManager->translitUrl($this->getButtonName()),
                'label' => __($this->getButtonName()),
            ]
        );
        $this->getElementScope() == 'default' && $button->setData('disabled', 'disabled');

        return $button->toHtml();
    }

    /**
     * @return false|mixed|string
     */
    public function getVirtualMethodCode()
    {
        return $this->virtualMethodCode;
    }

    /**
     * @return mixed
     */
    public function getElementScopeId()
    {
        return $this->elementScopeId;
    }

    /**
     * @return mixed
     */
    public function getElementScope()
    {
        return $this->elementScope;
    }

    /**
     * Return element html
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
