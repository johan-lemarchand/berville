<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


class UploaderHelper
{
    private $targetDirectory;
    private SluggerInterface $slugger;

    public function __construct(private readonly string $avatarDirectory, private readonly string $articleDirectory, private readonly string $mainArticleDirectory, private readonly string $eventDirectory, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, $targetDirectory): array|string
    {
        $ext = $file->guessExtension();

        if ($ext == 'jpg' || $ext == 'png' || $ext == 'bmp') {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            try {
                switch ($targetDirectory) {
                    case 'avatar':
                        $this->targetDirectory =  $this->avatarDirectory;
                        break;
                    case 'article':
                        $this->targetDirectory =  $this->articleDirectory;
                        break;
                    case 'mainArticle':
                        $this->targetDirectory =  $this->mainArticleDirectory;
                        break;
                    case 'event':
                        $this->targetDirectory =  $this->eventDirectory;
                }
                $file->move($this->targetDirectory, $fileName);

            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            return array(
                'error' => false,
                'name' => $fileName
            );
        }
        return array(
            'error' => true,
            'name' => $file->getClientOriginalName()
        );
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}