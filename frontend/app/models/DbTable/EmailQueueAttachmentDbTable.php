<?php

class EmailQueueAttachmentDbTable extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $queue_id;

    /**
     *
     * @var string
     */
    public $filename;

    /**
     *
     * @var string
     */
    public $file_path;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('queue_id', 'EmailQueue', 'id', NULL);
    }

}
