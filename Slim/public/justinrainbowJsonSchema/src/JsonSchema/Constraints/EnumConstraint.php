<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Constraints;

use Icecave\Parity\Parity;
use JsonSchema\ConstraintError;
use JsonSchema\Entity\JsonPointer;

/**
 * The EnumConstraint Constraints, validates an element against a given set of possibilities
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * @author Bruno Prieto Reis <bruno.p.reis@gmail.com>
 */
class EnumConstraint extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function check(&$element, $schema = null, JsonPointer $path = null, $i = null)
    {
        // Only validate enum if the attribute exists
        if ($element instanceof UndefinedConstraint && (!isset($schema->required) || !$schema->required)) {
            return;
        }
        $type = gettype($element);

        foreach ($schema->enum as $enum) {
            $enumType = gettype($enum);
            if ($this->factory->getConfig(self::CHECK_MODE_TYPE_CAST) && $type == 'array' && $enumType == 'object') {
                if (Parity::isEqualTo((object) $element, $enum)) {
                    return;
                }
            }

            if ($type === gettype($enum)) {
                if (Parity::isEqualTo($element, $enum)) {
                    return;
                }
            }
        }

        $this->addError(ConstraintError::ENUM(), $path, array('enum' => $schema->enum));
    }
}