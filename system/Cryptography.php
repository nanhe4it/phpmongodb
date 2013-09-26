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
            $this->data['array'][] = $this->__convertArray($php);
        }

        return $this->data;
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