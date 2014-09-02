<?php

namespace Pumukit\SchemaBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Pumukit\SchemaBundle\Document\Track
 *
 * @MongoDB\EmbedDocument
 */
class Track extends Element
{
    /**
	 * @var string $language
	 *
	 * @MongoDB\String
	 */
    private $language;

    /**
	 * @var string $acodec
	 *
	 * @MongoDB\String
	 */
    private $acodec;

    /**
	 * @var string $vcodec
	 *
	 * @MongoDB\String
	 */
    private $vcodec;

    /**
	 * @var int $bitrate
	 *
	 * @MongoDB\Int
	 */
    private $bitrate;

    /**
	 * @var int $framerate
	 *
	 * @MongoDB\Int
	 */
    private $framerate;

    /**
	 * @var boolean $only_audio
	 *
	 * @MongoDB\Boolean
	 */
    private $only_audio;

    /**
	 * @var int $channels
	 *
	 * @MongoDB\Int
	 */
    private $channels;

    /**
	 * @var int $duration
	 *
	 * @MongoDB\Int
	 */
    private $duration = 0;

    /**
	 * @var int $width
	 *
	 * @MongoDB\Int
	 */
    private $width;

    /**
	 * @var int $height
	 *
	 * @MongoDB\Int
	 */
    private $height;

    /**
	 * Set language
	 *
	 * @param string $language
	 */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
	 * Get language
	 *
	 * @return string
	 */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
	 * Set acodec
	 *
	 * @param string $acodec
	 */
    public function setAcodec($acodec)
    {
        $this->acodec = $acodec;
    }

    /**
	 * Get acodec
	 *
	 * @return string
	 */
    public function getAcodec()
    {
        return $this->acodec;
    }

    /**
	 * Set vcodec
	 *
	 * @param string $vcodec
	 */
    public function setVcodec($vcodec)
    {
        $this->vcodec = $vcodec;
    }

    /**
	 * Get vcodec
	 *
	 * @return string
	 */
    public function getVcodec()
    {
        return $this->vcodec;
    }

    /**
	 * Set bitrate
	 *
	 * @param integer $bitrate
	 */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
    }

    /**
	 * Get bitrate
	 *
	 * @return integer
	 */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
	 * Set framerate
	 *
	 * @param integer $framerate
	 */
    public function setFramerate($framerate)
    {
        $this->framerate = $framerate;
    }

    /**
	 * Get framerate
	 *
	 * @return integer
	 */
    public function getFramerate()
    {
        return $this->framerate;
    }

    /**
	 * Set only_audio
	 *
	 * @param boolean $onlyAudio
	 */
    public function setOnlyAudio($onlyAudio)
    {
        $this->only_audio = $onlyAudio;
    }

    /**
	 * Get only_audio
	 *
	 * @return boolean
	 */
    public function getOnlyAudio()
    {
        return $this->only_audio;
    }

    /**
	 * Set channels
	 *
	 * @param integer $channels
	 */
    public function setChannels($channels)
    {
        $this->channels = $channels;
    }

    /**
	 * Get channels
	 *
	 * @return integer
	 */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
	 * Set duration
	 *
	 * @param integer $duration
	 */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
	 * Get duration
	 *
	 * @return integer
	 */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
	 * Set width
	 *
	 * @param integer $width
	 */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
	 * Get width
	 *
	 * @return integer
	 */
    public function getWidth()
    {
        return $this->width;
    }

    /**
	 * Set height
	 *
	 * @param integer $height
	 */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
	 * Get height
	 *
	 * @return integer
	 */
    public function getHeight()
    {
        return $this->height;
    }
}