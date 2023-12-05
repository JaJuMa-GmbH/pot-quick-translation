<?php
declare(strict_types=1);

namespace Jajuma\PotQuickTranslation\Magewire;

use Magewirephp\Magewire\Component;
use Jajuma\PotQuickTranslation\Helper\Data;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\View\DesignInterface;
use Magento\Framework\Module\Manager;

class QuickAction extends Component
{
    protected $loader = true;

    protected $status = '';

    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var RemoteAddress
     */
    protected RemoteAddress $remoteAddress;

    /**
     * @var DesignInterface
     */
    protected DesignInterface $design;

    /**
     * @var Manager
     */
    protected Manager $moduleManager;

    /**
     * @param Data $helper
     * @param RemoteAddress $remoteAddress
     * @param DesignInterface $design
     * @param Manager $moduleManager
     */
    public function __construct(
        Data $helper,
        RemoteAddress $remoteAddress,
        DesignInterface $design,
        Manager $moduleManager
    ) {
        $this->helper = $helper;
        $this->remoteAddress = $remoteAddress;
        $this->design = $design;
        $this->moduleManager = $moduleManager;
    }

    /**
     * Save coupon
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->validateHyvaTranslation()) {
            $message = 'Please enable Jajuma_HyvaTranslation extension';
            $this->dispatchBrowserEvent('finish-quick-translation', ['message' => $message]);
            return;
        }

        $value = '1';
        $currentIp = $this->remoteAddress->getRemoteAddress();
        if ($this->helper->getTranslateInlineConfig() == '1') {
            $value = '0';
        }
        $isHyvaTranslation = $this->moduleManager->isEnabled('Jajuma_HyvaTranslation');
        $this->helper->setTranslateAllowIps($currentIp, $isHyvaTranslation);
        $this->helper->setTranslateInlineConfig($value, $isHyvaTranslation);
        $this->status = $value;
        $this->dispatchBrowserEvent('finish-quick-translation', ['status' => $value]);
    }

    /**
     * Backend execute
     *
     * @return void
     */
    public function backendExecute()
    {
        $value = '1';
        $currentIp = $this->remoteAddress->getRemoteAddress();
        if ($this->helper->getTranslateInlineConfigBackend() == '1') {
            $value = '0';
        }

        $this->helper->setTranslateAllowIps($currentIp);
        $this->helper->setTranslateInlineConfigBackend($value);
        $this->dispatchBrowserEvent('finish-quick-translation');
    }

    /**
     *  Get current status
     *
     * @return bool|mixed
     */
    public function getCurrentStatus()
    {
        if ($this->status !== '') {
            return $this->status;
        }

        $isHyvaTranslation = $this->moduleManager->isEnabled('Jajuma_HyvaTranslation');
        return $this->helper->getTranslateInlineConfig($isHyvaTranslation);
    }

    /**
     * Get current status backend
     *
     * @return mixed
     */
    public function getCurrentStatusBackend()
    {
        return $this->helper->getTranslateInlineConfigBackend();
    }

    /**
     * Validate hyva translation
     *
     * @return bool
     */
    protected function validateHyvaTranslation(): bool
    {
        $theme = $this->design->getDesignTheme();
        while ($theme) {
            if (strpos($theme->getCode(), 'Hyva/') === 0) {
                return $this->moduleManager->isEnabled('Jajuma_HyvaTranslation');
            }
            $theme = $theme->getParentTheme();
        }

        return true;
    }
}
