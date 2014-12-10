<?php
namespace Flow\JSONPath;


use Flow\JsonPath\Filters\AbstractFilter;

class JSONPath
{
    protected static $tokenCache = [];

    protected $data;
    protected $options;
    protected $Lexer;

    const ALLOW_MAGIC = 1;

    public function __construct($data, $options = 0)
    {
        $this->data = $data;
        $this->options = $options;
    }

    /**
     * Evaluate an expression
     * @param $expression
     * @return array
     */
    public function find($expression)
    {
        $tokens = $this->parseTokens($expression);

        $collectionData = [$this->data];

        while (count($tokens)) {
            $token = array_shift($tokens);
            $filter = $this->buildFilter($token);

            $collectionData = $this->filterData($filter, $collectionData);
        }

        return $collectionData;
    }


    /**
     * Operate on a data set using a pre-constructed filter
     *
     * @param AbstractFilter $filter The filter to apply
     * @param ArrayAccess $collectionData The data to filter
     *
     * @return mixed A reference to the 
     */
    protected function &filterData(Filters\AbstractFilter $filter, $collectionData) {
        $filteredData = [];

        foreach ($collectionData as $value) {
            if ($this->isFilterable($value)) {
                $filteredData = array_merge($filteredData, $filter->filter($value));
            }
        }

        return $filteredData;
    }

    public function isFilterable($value)
    {
        return is_array($value) || is_object($value);
    }

    /**
     * Evaluate an expression and return the first result
     * @param $expression
     * @return array|null
     */
    public function first($expression)
    {
        $result = $this->find($expression);
        return isset($result[0]) ? $result[0] : null;
    }

    /**
     * Evaluate an expression and return the last result
     * @param $expression
     * @return mixed
     */
    public function last($expression)
    {
        $result = $this->find($expression);
        $length = count($result);
        return array_key_exists($length - 1, $result) ? $result[$length - 1] : null;
    }

    /**
     * @param $token
     * @return AbstractFilter
     * @throws \Exception
     */
    public function buildFilter($token)
    {
	if ((!is_array($token) && !($token instanceof \ArrayAccess)) || !isset($token['type'], $token['value'])) {
            throw new JSONPathException("Attempting to build a filter with an invalid token");
        }

        $filterClass = 'Flow\\JSONPath\\Filters\\' . ucfirst($token['type']) . 'Filter';

        if (! class_exists($filterClass)) {
            throw new JSONPathException("No filter class exists for token [{$token['type']}]");
        }

        return new $filterClass($token['value'], $this->options);
    }

    /**
     * @param $expression
     * @return array
     * @throws \Exception
     */
    public function parseTokens($expression)
    {
        $cacheKey = md5($expression);

        if (isset(static::$tokenCache[$cacheKey])) {
            return static::$tokenCache[$cacheKey];
        }

        $expression = trim($expression);
        $expression = preg_replace('/^\$/', '', $expression);
        $tokens = $this->getLexer()->setExpression($expression)->parseExpression();

        static::$tokenCache[$cacheKey] = $tokens;

        return $tokens;
    }

    public function getLexer()
    {
        if (is_null($this->Lexer)) {
            $this->Lexer = new JSONPathLexer('.*');
        }

        return $this->Lexer;
    }
}
