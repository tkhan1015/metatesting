<?php
  /*   $_src_chunks = array();
          $_fp         = \fopen('Ayaka_Bubbles.png', 'r');
          $chunks      = array();

          if(!$_fp)
          {
              // Unable to open file
              return false;
          }

          // Read the magic bytes and verify
          $header = \fread($_fp, 8);

		  var_dump($header);

          if($header != "\x89PNG\x0d\x0a\x1a\x0a")
          {
              // Not a valid PNG image
			  echo "False";
              return false;
          }

		  echo "True";*/

/* IPTC */

use CSD\Image\Image;
use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelTag;

define("IPTC_OBJECT_NAME", "005");
define("IPTC_EDIT_STATUS", "007");
define("IPTC_PRIORITY", "010");
define("IPTC_CATEGORY", "015");
define("IPTC_SUPPLEMENTAL_CATEGORY", "020");
define("IPTC_FIXTURE_IDENTIFIER", "022");
define("IPTC_KEYWORDS", "025");
define("IPTC_RELEASE_DATE", "030");
define("IPTC_RELEASE_TIME", "035");
define("IPTC_SPECIAL_INSTRUCTIONS", "040");
define("IPTC_REFERENCE_SERVICE", "045");
define("IPTC_REFERENCE_DATE", "047");
define("IPTC_REFERENCE_NUMBER", "050");
define("IPTC_CREATED_DATE", "055");
define("IPTC_CREATED_TIME", "060");
define("IPTC_ORIGINATING_PROGRAM", "065");
define("IPTC_PROGRAM_VERSION", "070");
define("IPTC_OBJECT_CYCLE", "075");
define("IPTC_BYLINE", "080");
define("IPTC_BYLINE_TITLE", "085");
define("IPTC_CITY", "090");
define("IPTC_PROVINCE_STATE", "095");
define("IPTC_COUNTRY_CODE", "100");
define("IPTC_COUNTRY", "101");
define("IPTC_ORIGINAL_TRANSMISSION_REFERENCE", "103");
define("IPTC_HEADLINE", "105");
define("IPTC_CREDIT", "110");
define("IPTC_SOURCE", "115");
define("IPTC_COPYRIGHT_STRING", "116");
define("IPTC_CAPTION", "120");
define("IPTC_LOCAL_CAPTION", "121");

class IPTC
{
    var $meta = [];
    var $file = null;

    function __construct($filename)
    {
        $info = null;

        $size = getimagesize($filename, $info);

        if(isset($info["APP13"])) $this->meta = iptcparse($info["APP13"]);

        $this->file = $filename;
    }

    function getValue($tag)
    {
        return isset($this->meta["2#$tag"]) ? $this->meta["2#$tag"][0] : "";
    }

    function setValue($tag, $data)
    {
        $this->meta["2#$tag"] = [$data];

        $this->write();
    }

    private function write()
    {
        $mode = 0;

        $content = iptcembed($this->binary(), $this->file, $mode);

        $filename = $this->file;

        if(file_exists($this->file)) unlink($this->file);

        $fp = fopen($this->file, "w");
        fwrite($fp, $content);
        fclose($fp);
    }

    private function binary()
    {
        $data = "";

        foreach(array_keys($this->meta) as $key)
        {
            $tag = str_replace("2#", "", $key);
            $data .= $this->iptc_maketag(2, $tag, $this->meta[$key][0]);
        }

        return $data;
    }

    function iptc_maketag($rec, $data, $value)
    {
        $length = strlen($value);
        $retval = chr(0x1C) . chr($rec) . chr($data);

        if($length < 0x8000)
        {
            $retval .= chr($length >> 8) .  chr($length & 0xFF);
        }
        else
        {
            $retval .= chr(0x80) .
                       chr(0x04) .
                       chr(($length >> 24) & 0xFF) .
                       chr(($length >> 16) & 0xFF) .
                       chr(($length >> 8) & 0xFF) .
                       chr($length & 0xFF);
        }

        return $retval . $value;
    }

    function dump()
    {
        echo "<pre>";
        print_r($this->meta);
        echo "</pre>";
    }

    #requires GD library installed
    function removeAllTags()
    {
        $this->meta = [];
        $img = imagecreatefromstring(implode(file($this->file)));
        if(file_exists($this->file)) unlink($this->file);
        imagejpeg($img, $this->file, 100);
    }
}

$file = "img/Ayaka_Bubbles.jpg";
$objIPTC = new IPTC($file);

//set title
/*$objIPTC->setValue(IPTC_HEADLINE, "A title for this picture");

//set description
$objIPTC->setValue(IPTC_CAPTION, "Some words describing what can be seen in this picture.");

//set description
$objIPTC->setValue(IPTC_HEADLINE, "Schneeballschlact");

//set description
$objIPTC->setValue(IPTC_COUNTRY, "Schweiz");*/

echo $objIPTC->getValue(IPTC_CAPTION); //Corruptions by Enemy.


//EXIF

// Peel testing -> JPG ONLY x.x
$jpeg = new PelJpeg($file);
$ifd0 = $jpeg->getExif()->getTiff()->getIfd();
$entry = $ifd0->getEntry(PelTag::IMAGE_DESCRIPTION);
$entry->setValue('Edited by PEL');
$jpeg->saveFile($file);

// PNGImagick
$image = new Imagick();
$image->newImage($file); // or load your PNG into Imagick

$image->setImageProperty('keywords', 'Imagick');
echo $image->getImageProperty('keywords');

//Framelight

$framelight = Image::fromFile($file);
$headline = $framelight->getXmp()->getHeadline();
$camera = $framelight->getExif()->getCamera();


?>
