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
use Zend\Validator\{Between, NotEmpty};

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
        $notEmpty = new NotEmpty();
        $notEmpty->setMessage('Name is required.', NotEmpty::IS_EMPTY);

        $inputFilter = new Input('name');
        $inputFilter->getFilterChain()
            ->attach(new StripTags())
            ->attach(new StringTrim());
        $inputFilter->getValidatorChain()
            ->attach($notEmpty);

        return $inputFilter;
    }

    private function filterSku()
    {
        $notEmpty = new NotEmpty();
        $notEmpty->setMessage('SKU is required.', NotEmpty::IS_EMPTY);

        $inputFilter = new Input('sku');
        $inputFilter->getFilterChain()
            ->attach(new StripTags())
            ->attach(new StringTrim());
        $inputFilter->getValidatorChain()
            ->attach($notEmpty);

        return $inputFilter;
    }

    private function filterPrice()
    {
        $notEmpty = new NotEmpty(NotEmpty::FLOAT);
        $notEmpty->setMessage('Price is required.', NotEmpty::IS_EMPTY);

        $inputFilter = new Input('price');
        $inputFilter->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new ToFloat());
        $inputFilter->getValidatorChain()
            ->attach($notEmpty);

        return $inputFilter;
    }

    private function filterAvailableQuantity()
    {
        $notEmpty = new NotEmpty(NotEmpty::INTEGER);
        $notEmpty->setMessage('Quantity is required.', NotEmpty::IS_EMPTY);

        $between = new Between(['min' => 1, 'max' => 1000]);
        $between->setMessage("The quantity is not between '%min%' and '%max%', inclusively.", Between::NOT_BETWEEN);

        $inputFilter = new Input('available_quantity');
        $inputFilter->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new ToInt());
        $inputFilter->getValidatorChain()
            ->attach($notEmpty)
            ->attach($between);

        return $inputFilter;
    }
}
