<?php
namespace Flow\JSONPath;

class JSONStore extends JSONPath
{
    public function __construct($data, $options = 0)
    {
        $this->data = $data;
        $this->options = $options;
    }

}
