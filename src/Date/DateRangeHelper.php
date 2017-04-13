<?php

namespace YaPro\Helper\Date;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Common\Util\Debug;

class DateRangeHelper
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var Date
     */
    private $constraintDate;

    /**
     * @var \DateTime
     */
    private $minDate;

    /**
     * @var \DateTime
     */
    private $maxDate;

    /**
     * @param string $minDate
     * @param string $maxDate
     * @throws \Exception
     */
    public function __construct($minDate = null, $maxDate = null)
    {
        $this->validator = Validation::createValidator();
        $this->constraintDate = new Date();
        $this->minDate = $this->getDateTime($minDate);
        $this->maxDate = $this->getDateTime($maxDate);
    }

    /**
     * @param string $date
     * @return \DateTime
     * @throws \UnexpectedValueException
     */
    private function getDateTime($date)
    {
        if (empty($date) || !is_string($date)) {
            throw new \UnexpectedValueException(sprintf(
                'wrong date type "%s"',
                var_export(Debug::export($date, 1), true)
            ));
        }
        /** @var ConstraintViolationList $violations */
        $violations = $this->validator->validateValue($date, $this->constraintDate);
        if ($violations->count()) {
            throw new \UnexpectedValueException(sprintf('%s', $violations->__toString()));
        }

        return new \DateTime($date);
    }

    /**
     * @param string $date
     * @return \DateTime
     * @throws \UnexpectedValueException
     */
    public function getValidDate($date)
    {
        $dateTime = $this->getDateTime($date);
        if ($this->minDate <= $dateTime && $dateTime >= $this->maxDate) {
            return $dateTime;
        } else {
            throw new \UnexpectedValueException(sprintf('wrong date "%s"', $date));
        }
    }
}