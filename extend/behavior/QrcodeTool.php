<?php

namespace behavior;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

//二维码生成工具类
class QrcodeTool
{
    private $data;
    
    private $foregroundColor;

    public function getResult()
    {
        $writer = new PngWriter();
        // Create QR code
        $qrCode = QrCode::create($this->data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor($this->foregroundColor)
            ->setBackgroundColor(new Color(255, 255, 255));
        
        // Create generic logo
        //$logo = Logo::create(__DIR__.'/assets/symfony.png')->setResizeToWidth(50);

        // Create generic label
        //$label = Label::create('Label')->setTextColor(new Color(255, 0, 0));

        //$result = $writer->write($qrCode, $logo, $label);
        $result = $writer->write($qrCode);
        return $result;
    }

    public function setColor($color)
    {
        if($color == 'green') {
            $this->foregroundColor = new Color(94, 163, 108);
        }else if($color == 'yellow') {
            $this->foregroundColor = new Color(255, 255, 0);
        }else if($color == 'red') {
            $this->foregroundColor = new Color(255, 0, 0);
        }else{
            $this->foregroundColor = new Color(0, 0, 0);
        }
    }
    
    public function getString($url, $color)
    {
        $this->data = $url;
        $this->setColor($color);
        $result = $this->getResult();
        // Directly output the QR code
        header('Content-Type: '.$result->getMimeType());
        return $result->getString();
    }

    public function saveToFile($url, $color, $path)
    {
        $this->data = $url;
        $this->setColor($color);
        $result = $this->getResult();
        // Save it to a file
        $result->saveToFile($path);
        return true;
    }

    public function getDataUri($url, $color)
    {
        $this->data = $url;
        $this->setColor($color);
        $result = $this->getResult();
        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();
        return $dataUri;
    }
}
