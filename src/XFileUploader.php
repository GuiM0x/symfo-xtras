<?php

namespace Guiman\SymfoXtras;

// require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
// use Symfony\Component\DependencyInjection\ContainerBuilder;

class XFileUploader
{
    private string $targetDirectory;
    private SluggerInterface $slugger;
    // private ContainerBuilder $containerBuilder;

    public function __construct(SluggerInterface $slugger)
    {
        $this->targetDirectory = "";
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file): string
    {
        assert(
            $this->targetDirectory !== "",
            "A target directory is needed before upload a file. \nUse XFileUploader::setTargetDirectory to set it."
        );

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function setTargetDirectory(string $targetDirectory): void
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}