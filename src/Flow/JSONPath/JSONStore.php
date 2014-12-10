<?php
namespace Flow\JSONPath;

class JSONStore extends JSONPath
{

    public function __get($key) {
	$tokens = $this->getLexer()->setExpression(".{$key}")->parseExpression();
        
        if (!isset($tokens[0])) {
	    throw new JSONPathException('Invalid key');
        }

        $filter = $this->buildFilter($tokens[0]);

	$result = $this->filterData($filter, $this->data);
var_dump($tokens);
var_dump($result);die();
        return $this->filterData($filter, $this->data);
    }


}
