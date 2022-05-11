<?php
namespace App\Components;

/**
 * Класс содержит методы для загрузки и обработки файлов изображений.
 * Оригинал см. http://sanchiz.net/blog/resizing-images-with-php
 */
class SimpleImage {
   /**
   * Имя файла.
   *
   * @var string
   */
   protected $image;

   /**
   * Тип файла.
   *
   * @var string
   */
   protected $image_type;

   public function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];

      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }

   public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }

   public function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }

   public function getWidth() {
      return imagesx($this->image);
   }

   public function getHeight() {
      return imagesy($this->image);
   }

   public function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }

   public function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }

   public function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }

   public function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }

   /**
    * Функция перевода байтов в Mb, kB или b в зависимости от их количества
    *
    * @param int $bytes - количество байт
    * 
    * @return string $bytes - количество байт, переведенное в Mb, kB или b в зависимости от их количества
    */
    public static function formatSize($bytes) {

        if ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' Mb';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        } else {
            $bytes = $bytes . ' b';
        }

        return $bytes;
    }

    /**
    * Проверка загружаемого файла на предмет ошибки загрузки, допустимых типов и размера.
    *
    * @param array $file - массив $_FILES[]
    * @param array $types - массив с допустимыми типами файла
    * @param int $size - максимальный размер файла
    *
    * @return mixed - bool false - если валидация пройдена успешно
    *               - array $errors - массив со списком ошибок
    */
    public static function fileValidation($types, $size, $file)
    {
      $errors = false;

      if (!empty($file['myfile']['error'])) { // Проверяем наличие ошибок
        $errors['file']['LoadingError'] = $file['myfile']['error'];
       }
   // Проверить тип загружаемых файлов, это должны быть только картинки (jpeg, png, jpg).
       if (!in_array(mime_content_type($file['myfile']['tmp_name']), $types)) { 
           $errors['file']['TypeError'] = 'Неправильный тип ' . mime_content_type($file['myfile']['tmp_name']) . 'загружаемого файла ' . $file['myfile']['name'];
       }
   // Проверить размер загружаемого файла (файл не должен превышать 2 Мб).
       if ($file['myfile']['size'] > $size) {
          $errors['file']['SizeError'] = 'Файл ' . $file['myfile']['name'] . ' не может быть загружен на сервер, так как его размер составляет ' . static::formatSize($file['myfile']['size']) . ', что больше допустимых ' . static::formatSize($size);
       }

       return $errors;

    }
}
