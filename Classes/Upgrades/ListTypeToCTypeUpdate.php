<?php

declare(strict_types=1);

namespace Tollwerk\TwEprivacy\Upgrades;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('TwEprivacyListTypeToCTypeUpdate')]
class ListTypeToCTypeUpdate extends AbstractListTypeToCTypeUpdate
{
    /**
     * GetTitle
     *
     * @inheritDoc
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'TwEprivacy: Plugin list_type to CType';
    }

    /**
     * GetDescription
     *
     * @inheritDoc
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105076-PluginContentElementAndPluginSubTypes.html#deprecation-105076-1726923626';
    }

    /**
     * Mapping of the old `tt_content.list_type` values to the new `tt_content.CType` values.
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'tweprivacy_eprivacy' => 'tweprivacy_eprivacy',
        ];
    }
}
