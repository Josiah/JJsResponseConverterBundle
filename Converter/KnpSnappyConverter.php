<?php

namespace JJs\Bundle\ResponseConverterBundle\Converter;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Knp\Snappy\GeneratorInterface;

/**
 * KNP Snappy Converter
 *
 * Utilizes the knp snappy to convert html documents to either images or pdf
 * documents.
 *
 * @author Josiah <josiah@jjs.id.au>
 */
class KnpSnappyConverter implements ConverterInterface
{
    /**
     * Generator
     * 
     * @var GeneratorInterface
     */
    protected $generator;

    /**
     * Format to generate
     * 
     * @var string
     */
    protected $format;

    /**
     * Instantiates this converter
     * 
     * @param GeneratorInterface $generator Snappy pdf or image generator
     */
    public function __construct(GeneratorInterface $generator, $format = 'pdf')
    {
        $this->generator = $generator;
        $this->format    = $format;
    }

    /**
     * Returns the inner generator
     * 
     * @return GeneratorInterface
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * Returns the format to generate
     * 
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Indicates whether a pdf document should be generated from the response
     * for the specified request.
     *
     * The request must be for the 'pdf' format and the response format must be
     * html.
     * 
     * @param Request  $request
     *        Symfony request
     *
     * @param Response $response
     *        Current response
     *
     * @return boolean
     */
    public function isConvertable(Request $request, Response $response)
    {

        // Request must be for the 'pdf' format
        if ($request->getRequestFormat() !== $this->getFormat()) return false;

        // Current response must be html
        if (!$this->isHtmlResponse($response)) return false;

        // All checks ok
        return true;
    }

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
     *        Current response
     *        
     * @return Response
     */
    public function convert(Request $request, Response $response)
    {
        $generator = $this->getGenerator();
        $options = $request->attributes->get("{$this->getFormat()}_options", []);
        $headers = array_merge($request->headers->all(), [
            'Content-Type' => 'application/pdf',
        ]);

        return new StreamedResponse(function () use ($response, $generator, $options) {

            // Buffer the original response
            ob_start();
            $response->sendContent();
            $html = ob_get_contents();
            ob_end_clean();

            // Output converted response
            echo $generator->getOutputFromHtml($html, $options);

        }, $response->getStatusCode(), $headers);
    }

    /**
     * Indicates whether the response contains html content
     * 
     * @param Response $response
     *        Response to test
     *        
     * @return boolean
     */
    protected function isHtmlResponse(Response $response)
    {
        $headers = $response->headers;

        // Check the content type of the response, when there is no content type
        // set just assume that it's html.
        return strtolower($headers->get('Content-Type', 'text/html')) === 'text/html';
    }
}