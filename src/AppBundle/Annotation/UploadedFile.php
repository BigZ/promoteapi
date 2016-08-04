<?php

namespace AppBundle\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class UploadedFile
{
    public $type;
}
