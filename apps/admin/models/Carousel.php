<?php

namespace News\Admin\Models;

class Carousel extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $image;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $active;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'carousel';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Carousel[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Carousel
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
