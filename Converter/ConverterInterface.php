<?php

namespace JJs\Bundle\ResponseConverterBundle\Converter;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Response Converter
 *
 * Converts a response from one format to another.
 *
 * @author Josiah <josiah@jjs.id.au>
 */
interface ConverterInterface
{
    /**
     * Indicates whether the converter is capable of converting the request and
     * respose pair.
     *
     * Typically a converter will use the requested format as the target format
     * and derive the current response format from the response object.
     * 
     * @param Request  $request
     *        Symfony request
     *
     * @param Response $response
     *        Original response
     *
     * @return boolean
     */
    public function isConvertable(Request $request, Response $response);

    /**
     * Converts the response
     *
     * The replacement response should be returned from this method and will
     * replace the current response.
     * 
     * @param Request $request
     *        Symfony request
     *        
     * @param Response $response
     *        Original response
     *        
     * @return Response
     */
    public function convert(Request $request, Response $response);
}