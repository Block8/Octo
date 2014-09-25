<?php

/**
 * InvoiceAdjustment store for table: invoice_adjustment */

namespace Octo\Invoicing\Store;

use Octo;

/**
 * InvoiceAdjustment Store
 */
class InvoiceAdjustmentStore extends Octo\Store
{
    use Base\InvoiceAdjustmentStoreBase;

    /**
     * Process donation amount
     * @param $invoiceId
     * @return float DonationAmount
     */
    public function getDonationAmount($invoiceId)
    {
        /** @var \Octo\Invoicing\Model\InvoiceAdjustment[] $adjustments */
        $adjustments = $this->getByInvoiceId($invoiceId);

        $donationAmount = 0.00;
        foreach ($adjustments as $adjustment) {
            if ($adjustment->getScope() == 'donation') {
                $donationAmount = $adjustment->getDisplayValue();
            }
        }
        return $donationAmount;
    }


    /**
     * Check if GiftAid declaration Yes
     * @param int $invoiceId
     * @return bool
     */
    public function isGiftAid($invoiceId)
    {
        $adjustments = $this->getByInvoiceId($invoiceId);

        foreach ($adjustments as $adjustment) {
            if ($adjustment->getScope() == 'donation' && $adjustment->getGiftAid() == 1) {
                    return true;
            }
        }
        return false;
    }

}
