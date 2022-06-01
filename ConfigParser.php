<?php

class ConfigParser
{
    private string $filename;
    private array $config = [];

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function parseConfig(): array
    {
        $handle = fopen($this->filename, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                //If it's a comment, skip line
                if (str_starts_with($line, '# ')) continue;

                $elems = explode('=', $line);
                // If line format wrong, skip line
                if (count($elems) != 2) continue;

                $strPathElems = explode(' ', trim($elems[0]));
                $strPath = $strPathElems[count($strPathElems) - 1];
                $arrPath = explode('.', $strPath);
                $this->addConfigItem($arrPath, trim($elems[1]));
            }
            fclose($handle);
        }
        return $this->config;
    }

    private function addConfigItem($arrPath, $value)
    {
        $out = &$this->config;
        foreach ($arrPath as $key) {
            if (!isset($out[$key])) $out[$key] = [];
            $out = &$out[$key];
        }
        $out = $this->parseValue($value);
    }

    private function parseValue($value): mixed
    {
        if (str_starts_with($value, "\"")) {
            return str_replace("­", "\0", trim($value, "\""));
        } else if (intval($value) == $value) {
            return intval($value);
        } else if (in_array($value, ['true', 'false'])) {
            return (bool)$value;
        }
        return $value;
    }
}
