<?php
declare(strict_types=1);

namespace Jajuma\PotQuickTranslation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Config extends AbstractHelper
{
    public const XML_PATH_ENABLE = 'power_toys/pot_quick_translation/is_enabled';

    public const XML_PATH_AUTO_FLUSH_TRANSLATE_CACHE = 'power_toys/pot_quick_translation/auto_flush_translate_cache';

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Is enable
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE);
    }

    /**
     * Auto flush translate cache
     *
     * @return bool
     */
    public function isAutoFlushTranslateCache(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_AUTO_FLUSH_TRANSLATE_CACHE);
    }
}
