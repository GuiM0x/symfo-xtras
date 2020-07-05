<?php

/*
 * (c) Guillaume Manier <guillaume.manier.pro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guiman\SymfoXtras;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class XFileUploader
{
    private SluggerInterface $slugger;

    /**
     * The slugger is needed in upload function to transform a file name to a safe file name.
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * Uploads a file to target directory and returns the new file's name.
     *
     * @param UploadedFile $file      - Symfony's object returned by an input of type file
     * @param string $targetDirectory - Path that represents the target directory where upload the file
     * @return string
     */
    public function upload(UploadedFile $file, string $targetDirectory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($targetDirectory, $fileName);
        } catch (FileException $e) {
            throw $e;
        }

        return $fileName;
    }
}