<?php
namespace Task\CustomShipping\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class CityOptions extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (null === $this->_options) {
            $this->_options=[
                ['label' => __('Banglore'), 'value' => "Banglore"],
                ['label' => __('Jaipur'), 'value' => "Jaipur"],
                ['label' => __('Indore'), 'value' => "Indore"],
                ['label' => __('Jodhpur'), 'value' => "Jodhpur"]
            ];
        }
        return $this->_options;
    }
}