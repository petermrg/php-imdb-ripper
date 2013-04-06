<?php
/*
IMDbRipper v.0.1

Copyright 2013 Peter Morgan <petermrg@ymail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class IMDbRipper {

    private $cache = false;
    private $cacheType = 'file';
    private $cacheDir = '/tmp/';

    public function __construct($options = []) {

        if (isset($options['cache']) && ($options['cache'] == true)) $this->cache = true;

        if ($this->cache && ($this->cacheType == 'file')) {
            if (isset($options['cache_dir'])) $this->cacheDir = $options['cache_dir'];
            if (substr($this->cacheDir, -1) != DIRECTORY_SEPARATOR) {
                $this->cacheDir.= DIRECTORY_SEPARATOR;
            }
            if (!is_writable ($this->cacheDir)) {
                $this->cache = false;
                trigger_error("Cache disabled. ".
                    "Directory '{$this->cacheDir}' doesn't exists or isn't writable");
            }
        }
        else {
            $this->cache == false;
        }

    }

    private function loadHTML($url) {
        if ($this->cache == true) {
            $fn = $this->cacheDir.'imdbripper-'.md5($url);
            if (file_exists($fn)) $html = file_get_contents($fn);
            else {
                $html = file_get_contents($url);
                file_put_contents($fn, $html);
            }
        }
        else  {
            $html = file_get_contents($url);
        }
        return $html;
    }

    private function getAttr($item, $name) {
        $attributes = $item->attributes;
        foreach ($attributes as $attr) {
            if ($attr->name == $name) {
                $value = trim($attr->nodeValue);
                if ($name == 'href') $value = preg_replace('/\?.*/', '', $value);
                return $value;
            }
        }
        return '';
    }

    private function getLinks($items) {
        $data = array();
        foreach ($items as $item) {
            $data[] = array(
                'name' => trim($item->nodeValue),
                'href' => $this->getAttr($item, 'href')
            );
        }
        return $data;
    }

    private function getValues($items) {
        $data = array();
        foreach ($items as $item) {
            $data[] = trim($item->nodeValue);
        }
        return $data;
    }

    private function getValue($item) {
        return trim($item->item(0)->nodeValue);
    }

    private function getTable($xpath, $tr, $fields) {
        $items = $xpath->query($tr);
        $rows = array();
        foreach ($items as $i => $item) {
            $row = array();
            foreach ($fields as $name => $func) {
                $row[$name] = $func($xpath, $tr, $i+1);
                if ($row[$name] === false) continue(2);
            }
            $rows[] = $row;
        }
        return $rows;
    }

    private function fullImage($url) {
        return preg_replace('/_S.*_\./', '.', $url);
    }

    public function main($code) {
        $url = 'http://www.imdb.com/title/tt'.$code.'/';
        $html = $this->loadHTML($url);
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXpath($doc);
        $data = array();

        $data['title'] = $this->getValue($xpath->query('//*[@id="overview-top"]/h1/span[1]'));

        $data['image'] = $this->fullImage($this->getAttr(
            $xpath->query('//*[@id="img_primary"]/div/a/img')->item(0), 'src')
        );
        $data['original_title'] = $this->getValue($xpath->query('//*[@id="overview-top"]/h1/span[3]/text()'));

        $data['country'] = $this->getValues($xpath->query('//*[@id="titleDetails"]/div[2]/a'));

        $data['year'] = $this->getValue($xpath->query('//*[@id="overview-top"]/h1/span[2]/a'));

        $data['duration'] = $this->getValue($xpath->query('//*[@id="overview-top"]/div[2]/time'));

        $data['score'] = $this->getValue($xpath->query('//*[@id="overview-top"]/div[3]/div[1]'));

        $data['content_rating'] = $this->getAttr(
            $xpath->query('//*[@id="overview-top"]/div[2]/span[1]')->item(0), 'title'
        );
        $data['director'] = $this->getLinks($xpath->query('//*[@id="overview-top"]/div[4]/a'));

        $data['writers'] = $this->getLinks($xpath->query('//*[@id="overview-top"]/div[5]/a'));

        $data['stars'] = $this->getLinks($xpath->query('//*[@id="overview-top"]/div[6]/a'));

        $data['genres'] = $this->getValues($xpath->query('//*[@id="titleStoryLine"]/div[4]/a'));

        $data['cast'] = $this->getTable($xpath, '//*[@id="titleCast"]/table/tr', array(
            'name' => function($xpath, $tr, $n) {
                $item = $xpath->query($tr.'['.$n.']/td[2]/a');
                return ($item->length == 0) ? false : $this->getValue($item);
            },
            'href' => function($xpath, $tr, $n) {
                $item = $xpath->query($tr.'['.$n.']/td[2]/a');
                return ($item->length == 0) ? false : $this->fullImage(
                    $this->getAttr($item->item(0), 'href')
                );
            },
            'image' => function($xpath, $tr, $n) {
                $item = $xpath->query($tr.'['.$n.']/td[1]/a/img');
                return ($item->length == 0) ? false : $this->fullImage(
                    $this->getAttr($item->item(0), 'loadlate')
                );
            }
        ));

        return $data;
    }

    public function keywords($code) {
        // TODO:
    }

    public function fullCredits($code) {
        // TODO:
    }

}

