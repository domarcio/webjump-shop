<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Category\Filter;

use Zend\Filter\{StringTrim, StripTags};
use Zend\InputFilter\{InputFilter, Input};
use Zend\Validator\NotEmpty;

final class CategoryFilter implements FilterInterface
{
    private $isValid;

    private $filter;

    /**
     * @inheritDoc
     */
    public function setData(array $data): void
    {
        $name = new Input('name');
        $name->getFilterChain()
            ->attach(new StripTags())
            ->attach(new StringTrim());
        $name->getValidatorChain()
                ->attach(new NotEmpty());

        $inputFilter = new InputFilter();
        $inputFilter->add($name);
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
}
