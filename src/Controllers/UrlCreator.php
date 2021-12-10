<?php

namespace App\Controllers;

use App\Repository\UrlRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class UrlCreator
{
    /**
     * @var UrlRepository
     */
    private $repository;
    protected static $checkUrlExists = false;

    /**
     * The constructor.
     *
     * @param UrlRepository $repository The repository
     */
    public function __construct(UrlRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchUrl(string $url): array
    {
        // Input validation
        $this->validateUrlFormat($url);
        
        // fetch url
        $fetchUrl = $this->repository->fetchUrl($url);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $fetchUrl;
    }

    /**
     * Input validation.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function validateUrlFormat(string $url): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if(empty($url)){
            throw new ValidationException("No URL was supplied.");
        }

        if($this->validateUrl($url) == false){
         //   throw new ValidationException("URL does not have a valid format.");
        }

        if(self::$checkUrlExists){
            if (!$this->verifyUrlExists($url)){
                throw new ValidationException("URL does not appear to exist.");
            }
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }

    protected function validateUrl($url){
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    protected function verifyUrlExists($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }
}