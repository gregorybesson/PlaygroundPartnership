<?php

namespace PlaygroundPartnership\Options;

class ModuleOptions
{
    /**
     * @var string
     */
    protected $actionEntityClass = 'PlaygroundPartnership\Entity\Partner';

    /**
     * @var bool
     */
    protected $enableDefaultEntities = true;

    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * drive path to game media files
     */
    protected $media_path = 'public/media/partner';

    /**
     * url path to game media files
     */
    protected $media_url = 'media/partner';

    protected $partnerMapper = 'PlaygroundPartnership\Mapper\Partner';

    /**
     * Set media path
     *
     * @param  string                                  $media_path
     * @return \PlaygroundPartnership\Options\ModuleOptions
     */
    public function setMediaPath($media_path)
    {
        $this->media_path = $media_path;

        return $this;
    }

    /**
     * @return string
     */
    public function getMediaPath()
    {
        return $this->media_path;
    }

    /**
     *
     * @param  string                                  $media_url
     * @return \PlaygroundPartnership\Options\ModuleOptions
     */
    public function setMediaUrl($media_url)
    {
        $this->media_url = $media_url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->media_url;
    }
}
