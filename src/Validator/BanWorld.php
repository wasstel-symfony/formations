<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWorld extends Constraint
{
    public function __construct(
        public string $message = 'This contained a ban word "{{ banWord }}".',
        public array $banWords =  ["spam", "viagra", "porno", 'video adulte']
    )
    {
        parent::__construct();
    }

//    public $word = ["spam", "viagra"];
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
//    public string $message = 'The value "{{ value }}" is not valid.';
}
