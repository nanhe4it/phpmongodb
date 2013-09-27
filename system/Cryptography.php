<?php

class Cryptography {

    protected $data;

    public function decode($cursor) {
        while ($cursor->hasNext()) {
            $document = $cursor->getNext();
            $php = $this->__convertPHP($document);
            $this->data['document'][] = $document;
            $this->data['php'][] = $php;
            $this->data['json'][] = $this->__convertJSON($php);
            $this->data['array'][] = $this->highlight($this->arrayToString($document));
        }

        return $this->data;
    }

    public function highlight($string) {
        $string = highlight_string("<?php " . $string, true);
        $string = preg_replace("/" . preg_quote('<span style="color: #0000BB">&lt;?php&nbsp;</span>', "/") . "/", '', $string, 1);
        return $string;
    }

    public function arrayToString($array,$tab="\t") {
        $string = 'array(';
        foreach ($array as $key => $value) {
            $string.="\n".$tab;
            if (gettype($value) === 'array') {
                $string.="\"$key\"" . '=>' . $this->arrayToString($value,"$tab\t");
            } else if (is_object($value)) {
                $string.="\"$key\"" . '=>' . $this->objectToString($value);
            } else {

                $string.="\"$key\"" . '=>' . $value;
            }
            $string.=',';
        }
        $string.="\n".$tab.')';
        return str_replace(',)', ')', $string);
        echo $data;
        die;
    }

    public function objectToString($object) {
        switch (get_class($object)) {
            case "MongoId":
                $string = 'new MongoId("' . $object->__toString() . '")';
                break;
            case "MongoInt32":
                $string = 'new MongoInt32(' . $object->__toString() . ')';
                break;
            case "MongoInt64":
                $string = 'new MongoInt64(' . $object->__toString() . ')';
                break;
            case "MongoDate":
                $string = 'new MongoDate(' . $object->sec . ', ' . $object->usec . ')';
                break;
            case "MongoRegex":
                $string = 'new MongoRegex(\'/' . $object->regex . '/' . $object->flags . '\')';
                break;
            case "MongoTimestamp":
                $string = 'new MongoTimestamp(' . $object->sec . ', ' . $object->inc . ')';
                break;
            case "MongoMinKey":
                $string = 'new MongoMinKey()';
                break;
            case "MongoMaxKey":
                $string = 'new MongoMaxKey()';
                break;
            case "MongoCode":
                //$string = 'new MongoCode("' . addcslashes($object->code, '"') . '", ' . var_export($object->scope, true) . ')';
                break;
            default:
                if (method_exists($object, "__toString")) {
                    return $object->__toString();
                }
        }
        return $string;
    }

    protected function __convertJSON($array) {

        return str_replace(array('[{', '}]', '\""', '\"'), array('{', '}', '"'), json_encode($array));
        return json_encode($array);
    }

    protected function __convertArray($array) {

        $data = 'array(';
        foreach ($array as $key => $value) {

            if (gettype($value) === 'array') {
                $data.="\"$key\"" . '=>' . $this->__convertArray($value);
            } else {
                $data.="\"$key\"" . '=>' . $value;
            }
            $data.=',';
        }
        $data.=')';
        return str_replace(',)', ')', $data);
    }

    protected function __convertPHP($array) {
        $data = array();
        foreach ($array as $key => $value) {

            if (gettype($value) === 'array') {
                $data[$key] = $this->__convertPHP($value);
            } else if (gettype($value) === 'object') {
                $data[$key] = get_class($value) . '("' . $value . '")';
            } else if (gettype($value) === 'integer' || gettype($value) === 'double') {
                $data[$key] = $value;
            } else {
                $data[$key] = '"' . $value . '"';
            }
        }

        return $data;
    }

    protected function stringToArray($string) {
        $string = "return " . $string . ";";
        if (function_exists("token_get_all")) {
            $php = "<?php\n" . $string . "\n?>";
            $tokens = token_get_all($php);
            foreach ($tokens as $token) {
                $type = $token[0];
                if (is_long($type)) {
                    if (in_array($type, array(
                                T_OPEN_TAG,
                                T_RETURN,
                                T_WHITESPACE,
                                T_ARRAY,
                                T_LNUMBER,
                                T_DNUMBER,
                                T_CONSTANT_ENCAPSED_STRING,
                                T_DOUBLE_ARROW,
                                T_CLOSE_TAG,
                                T_NEW,
                                T_DOUBLE_COLON
                            ))) {
                        continue;
                    }

                    if ($type == T_STRING) {
                        $keyword = strtolower($token[1]);
                        if (in_array($keyword, array(
                                    "mongoid",
                                    "mongocode",
                                    "mongodate",
                                    "mongoregex",
                                    "mongobindata",
                                    "mongoint32",
                                    "mongoint64",
                                    "mongodbref",
                                    "mongominkey",
                                    "mongomaxkey",
                                    "mongotimestamp",
                                    "true",
                                    "false",
                                    "null",
                                    "__set_state"
                                ))) {
                            continue;
                        }
                    }
                    exit("For your security, we stoped data parsing at '(" . token_name($type) . ") " . $token[1] . "'.");
                }
            }
        }

        return eval($string);
    }

    protected function debug($a) {
        echo "<pre>";
        print_r($a);
        echo "<pre>";
    }

}