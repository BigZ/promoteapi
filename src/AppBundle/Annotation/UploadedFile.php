<?php

/*
 * This file is part of the NelmioApiDocBundle package.
 *
 * (c) Nelmio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Annotation;

/**
 * @Annotation
 *
 * @Target("PROPERTY")
 */
final class UploadedFile
{
    public $type;
}
