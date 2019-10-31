<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Product\Filter;

use Nogues\Common\Filter\FilterInterface;
use Zend\Filter\{StringTrim, StripTags, ToFloat, ToInt};
use Zend\InputFilter\{InputFilter, Input};
use Zend\Validator\NotEmpty;

final class ProductFilter implements FilterInterface
{
    private $isValid;

    private $filter;

    /**
     * @inheritDoc
     */
    public function setData(array $data): void
    {
        $inputFilter = new InputFilter();

        // Add filters
        $inputFilter->add($this->filterName());
        $inputFilter->add($this->filterSku());
        $inputFilter->add($this->filterPrice());
        $inputFilter->add($this->filterAvailableQuantity());

        // Values to filter
        $inputFilter->setData($data);

        $this->isValid = $inputFilter->isValid();
        $this->filter  = $inputFilter;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): \Generator
    {
        foreach ($this->filter->getInvalidInput() as $error) {
            yield $error->getMessages();
        }
    }

    private function filterName()
    {
        $inputFilter = new Input('name');
        $inputFilter->getFilterChain()
            ->attach(new StripTags())
            ->attach(new StringTrim());
        $inputFilter->getValidatorChain()
                ->attach(new NotEmpty());

        return $inputFilter;
    }

    private function filterSku()
    {
        $inputFilter = new Input('sku');
        $inputFilter->getFilterChain()
            ->attach(new StripTags())
            ->attach(new StringTrim());
        $inputFilter->getValidatorChain()
                ->attach(new NotEmpty());

        return $inputFilter;
    }

    private function filterPrice()
    {
        $inputFilter = new Input('price');
        $inputFilter->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new ToFloat());
        $inputFilter->getValidatorChain()
                ->attach(new NotEmpty(NotEmpty::FLOAT));

        return $inputFilter;
    }

    private function filterAvailableQuantity()
    {
        $inputFilter = new Input('available_quantity');
        $inputFilter->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new ToInt());
        $inputFilter->getValidatorChain()
                ->attach(new NotEmpty(NotEmpty::INTEGER));

        return $inputFilter;
    }
}
