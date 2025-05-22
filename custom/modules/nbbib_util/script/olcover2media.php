<?php

use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

// 1. Configuration: Set your ISBN and destination file directory.
$isbn = '9780140328721';
$size = 'L'; // S, M, or L.
$cover_url = "https://covers.openlibrary.org/b/isbn/{$isbn}-{$size}.jpg";

// 2. Download the image.
$image_contents = file_get_contents($cover_url);
if ($image_contents === false) {
  throw new \Exception("Failed to fetch cover image for ISBN: $isbn");
}

// 3. Save the image as a Drupal-managed file.
$directory = 'public://openlibrary_covers';
\Drupal::service('file_system')->prepareDirectory($directory, \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY | \Drupal\Core\File\FileSystemInterface::MODIFY_PERMISSIONS);

$filename = "openlibrary_cover_{$isbn}.jpg";
$uri = "{$directory}/{$filename}";

// Remove if already exists.
if (file_exists($uri)) {
  \Drupal::service('file_system')->delete($uri);
}

$file = file_save_data($image_contents, $uri, FILE_EXISTS_REPLACE);
if (!$file) {
  throw new \Exception("Failed to save image file to Drupal file system.");
}

// 4. Create a Media entity of type 'image'.
$media = Media::create([
  'bundle' => 'image',
  'name' => "Book cover for ISBN $isbn",
  'field_media_image' => [
    'target_id' => $file->id(),
    'alt' => "Book cover for ISBN $isbn",
    'title' => "Book cover for ISBN $isbn",
  ],
  'status' => 1,
]);
$media->save();

echo "Media object created with ID: " . $media->id() . "\n";