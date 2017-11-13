<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint that requires a shift start time to be less than its end time.
 * @Annotation
 */
class RequiresStartTimeBeLessThanEndTime extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public $message = 'The shift requires start time to be less than end time';
}
