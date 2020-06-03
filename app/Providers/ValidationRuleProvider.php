<?php

namespace App\Providers;

use Validator;
use App\Rules\DifferentIgnoreCaseRule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ValidationRuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('differentIgnoreCase', function($attribute, $value, $parameters, $validator) {
            $rule = new DifferentIgnoreCaseRule($validator->getData()[$parameters[0]]);
            return $rule->passes($attribute, $value);
        });

        Validator::replacer('differentIgnoreCase', function($message, $attribute, $rule, $parameters) {
            return trans('validation.different_ignore_case',
                [
                    'attribute' => trans('validation.attributes.'.$attribute),
                    'other' => trans('validation.attributes.'.$parameters[0])
                ],
                App::getLocale()
            );
        });
    }
}
