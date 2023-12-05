<?php

namespace Jajuma\PotQuickTranslation\Plugin\Magento\Translation\Model\Inline;

use Magento\Translation\Model\Inline\CacheManager;
use Jajuma\PotQuickTranslation\Helper\Config;
use Magento\Framework\App\Cache\TypeListInterface;

class CacheManagerPlugin
{
    /**
     * @var Config
     */
    protected Config $configHelper;

    /**
     * @var TypeListInterface
     */
    protected TypeListInterface $typeList;

    /**
     * @param Config $configHelper
     * @param TypeListInterface $typeList
     */
    public function __construct(
        Config $configHelper,
        TypeListInterface $typeList
    ) {
        $this->configHelper = $configHelper;
        $this->typeList = $typeList;
    }

    /**
     * After update and get translations
     *
     * @param CacheManager $subject
     * @param $result
     * @return void
     */
    public function afterUpdateAndGetTranslations(
        CacheManager $subject,
        $result
    ) {
        if ($this->configHelper->isAutoFlushTranslateCache()) {
            $this->typeList->cleanType('translate');
        }

        return $result;
    }
}
