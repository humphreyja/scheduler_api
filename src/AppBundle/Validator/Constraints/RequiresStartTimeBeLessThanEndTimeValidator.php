<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class RequiresStartTimeBeLessThanEndTimeValidator extends ConstraintValidator
{
    public function validate($shift, Constraint $constraint)
    {
        if ($shift->getStartTime() > $shift->getEndTime()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
