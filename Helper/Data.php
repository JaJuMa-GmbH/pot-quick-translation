<?php
declare(strict_types=1);

namespace Jajuma\PotQuickTranslation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Cache\TypeListInterface;

class Data extends AbstractHelper
{
    protected const XML_PATH_DEV_TRANSLATE_INLINE_ACTIVE = 'dev/translate_inline/active';

    protected const XML_PATH_DEV_TRANSLATE_INLINE_ACTIVE_ADMIN = 'dev/translate_inline/active_admin';

    protected const XML_PATH_DEV_RESTRICT_ALLOW_IPS = 'dev/restrict/allow_ips';

    protected const XML_PATH_JAJUMA_TRANSLATE_IS_ENABLED = 'jajuma_translation/general/is_enabled';

    protected const XML_PATH_JAJUMA_TRANSLATE_INLINE_ACTIVE = 'jajuma_translation/general/translate_inline';

    protected const XML_PATH_JAJUMA_RESTRICT_ALLOW_IPS = 'jajuma_translation/general/allow_ips';

    /**
     * @var WriterInterface
     */
    protected WriterInterface $writer;

    /**
     * @var TypeListInterface
     */
    protected TypeListInterface $typeList;

    /**
     * @param Context $context
     * @param WriterInterface $writer
     * @param TypeListInterface $typeList
     */
    public function __construct(
        Context $context,
        WriterInterface $writer,
        TypeListInterface $typeList
    ) {
        parent::__construct($context);
        $this->writer = $writer;
        $this->typeList = $typeList;
    }

    /**
     * Get translate inline config
     *
     * @return mixed
     */
    public function getTranslateInlineConfig($isHyvaTranslation = false)
    {
        $translateInlineActive = $this->scopeConfig->getValue(self::XML_PATH_DEV_TRANSLATE_INLINE_ACTIVE);
        if ($isHyvaTranslation) {
            $hyvaTranslationEnabled = $this->scopeConfig->getValue(self::XML_PATH_JAJUMA_TRANSLATE_IS_ENABLED);
            $hyvaTranslationInlineActive = $this->scopeConfig->getValue(self::XML_PATH_JAJUMA_TRANSLATE_INLINE_ACTIVE);
            return $translateInlineActive && $hyvaTranslationEnabled && $hyvaTranslationInlineActive;
        }
        return $translateInlineActive;
    }

    /**
     * Get translate inline config backend
     *
     * @return mixed
     */
    public function getTranslateInlineConfigBackend()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DEV_TRANSLATE_INLINE_ACTIVE_ADMIN);
    }

    /**
     * Set translate inline config
     *
     * @param $value
     * @param bool $isHyvaTranslation
     * @return void
     */
    public function setTranslateInlineConfig($value, bool $isHyvaTranslation = false)
    {
        if ($isHyvaTranslation) {
            $this->writer->save(self::XML_PATH_JAJUMA_TRANSLATE_IS_ENABLED, $value);
            $this->writer->save(self::XML_PATH_JAJUMA_TRANSLATE_INLINE_ACTIVE, $value);
        }
        $this->writer->save(self::XML_PATH_DEV_TRANSLATE_INLINE_ACTIVE, $value);

        $types = array_keys($this->typeList->getTypes());
        foreach ($types as $type) {
            $this->typeList->cleanType($type);
        }
    }

    /**
     * Set translate inline config backend
     *
     * @param $value
     * @return void
     */
    public function setTranslateInlineConfigBackend($value)
    {
        $this->writer->save(self::XML_PATH_DEV_TRANSLATE_INLINE_ACTIVE_ADMIN, $value);
        $this->typeList->cleanType('config');
        $this->typeList->cleanType('block_html');
    }

    /**
     * Set translate allow ips
     *
     * @param $value
     * @param bool $isHyvaTranslation
     * @return void
     */
    public function setTranslateAllowIps($value, bool $isHyvaTranslation = false)
    {
        if ($isHyvaTranslation) {
            $this->writer->save(self::XML_PATH_JAJUMA_RESTRICT_ALLOW_IPS, $value);
        }
        $this->writer->save(self::XML_PATH_DEV_RESTRICT_ALLOW_IPS, $value);
    }
}
