<?php

class IMDbRipper {

    public function __construct() {
    }

    private function loadHTML($url) {
        // TODO: implement some cache mechanism
        $html = file_get_contents($url);
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
        return preg_replace('/_S.*_\./', '', $url);
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
        $data['year'] = $this->getValue($xpath->query('//*[@id="overview-top"]/h1/span[2]/a'));
        $data['duration'] = $this->getValue($xpath->query('//*[@id="overview-top"]/div[2]/time'));
        $data['score'] = $this->getValue($xpath->query('//*[@id="overview-top"]/div[3]/div[1]'));
        $data['content_rating'] = $this->getAttr($xpath->query('//*[@id="overview-top"]/div[2]/span[1]')->item(0), 'title');
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

