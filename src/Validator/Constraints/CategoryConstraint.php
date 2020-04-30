<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CategoryConstraint extends Constraint
{

    /**
     * Returns the name of the class that validates this constraint.
     *
     * By default, this is the fully qualified name of the constraint class
     * suffixed with "Validator". You can override this method to change that
     * behaviour.
     *
     * @return string
     */
    public function validatedBy()
    {
        // return \get_class($this).'Validator';
        return 'App\Validator\CategoryValidator';
    }


    /**
     * Returns whether the constraint can be put onto classes, properties or
     * both.
     *
     * This method should return one or more of the constants
     * Constraint::CLASS_CONSTRAINT and Constraint::PROPERTY_CONSTRAINT.
     *
     * @return string|array One or more constant values
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}