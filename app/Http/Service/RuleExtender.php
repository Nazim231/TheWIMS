<?php

namespace App\Http\Service;

use Illuminate\Validation\InvokableValidationRule;

class RuleExtender {

    public static function extend(string $ruleClass) {

        return function($attribute, $value, $parameters, $validator) use ($ruleClass) {
            $rule = InvokableValidationRule::make(new $ruleClass(...$parameters));
            $rule->setValidator($validator);
            $rule->setData($validator->getData());
            $result = $rule->passes($attribute, $value);
            if (!$result) {
                $validator->customMessages[$attribute] = $rule->message()[0];
            }
            return $result;
        };
    }
}