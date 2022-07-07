<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 * 
 */
class SourceFile extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Ошибка! Загружаемое изображение не соответсвует параметрам(ширина, высота, кол-во точек на дюйм) изображения области.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
