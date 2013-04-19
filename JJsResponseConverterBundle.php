<?php

namespace JJs\Bundle\ResponseConverterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use JJs\Bundle\ResponseConverterBundle\DependencyInjection\JJsResponseConverterExtension;

/**
 * Response Converter Bundle
 *
 * Provides automatic conversion of responses to various formats.
 *
 * @author Josiah <josiah@jjs.id.au>
 */
class JJsResponseConverterBundle extends Bundle
{
    /**
     * Container extension
     * 
     * @var JJsResponseConverterExtension
     */
    protected $extension;

    /**
     * Instantiates this bundle
     */
    public function __construct()
    {
        $this->extension = new JJsResponseConverterExtension();
    }

    /**
     * Returns the container extension of this bundle
     * 
     * @return JJsResponseConverterExtension
     */
    public function getContainerExtension()
    {
        return $this->extension;
    }
}