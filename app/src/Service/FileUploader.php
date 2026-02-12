<?php

namespace App\Service;

use App\Dto\UploadResult;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\NoFileException;
use Symfony\Component\HttpFoundation\File\Exception\IniSizeFileException;
use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;

class FileUploader
{
    public function __construct(
        private readonly SluggerInterface    $slugger,
        private readonly TranslatorInterface $translator,
        private readonly KernelInterface     $kernel,
    ) {}

    public function upload(UploadedFile $file, string $targetDirectory): UploadResult
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $fullTargetDirectory  = $this->kernel->getProjectDir() . '/public/' . $targetDirectory;

        try {
            $file->move($fullTargetDirectory, $fileName);
        } catch (IniSizeFileException $e) {
            return new UploadResult(
                false,
                $this->translator->trans(
                    'file_to_big',
                    [
                        '%file_name%' => $originalFilename,
                        '%upload_max_filesize%' => ini_get('upload_max_filesize'),
                    ],
                    'file_uploader'
                ),
                $originalFilename,
                $targetDirectory
            );
        } catch (NoFileException $e) {
            return new UploadResult(
                false,
                $this->translator->trans(
                    'no_file_uploaded',
                    [],
                    'file_uploader'
                ),
                $originalFilename,
                $targetDirectory
            );
        } catch (FileException $e) {
            return new UploadResult(
                false,
                $this->translator->trans(
                    'unexpected_error',
                    [],
                    'file_uploader'
                ),
                $originalFilename,
                $targetDirectory
            );
        }
        //if successful
        //return processed fileName to save in db
        //else return orignalName for user message purposes
        return new UploadResult(
            true,
            $this->translator->trans('file_upload_success', [], 'file_uploader'),
            $fileName,
            $targetDirectory
        );
    }
}
