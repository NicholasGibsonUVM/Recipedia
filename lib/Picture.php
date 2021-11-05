<?php 
class Picture {
    public function __construct($pictureArray, $imgField, $captionField = '', $linkArray = '') {
        $this->pictureArray = $pictureArray;
        $this->imgField = $imgField;
        $this->captionField = $captionField;
        $this->linkArray = $linkArray;
    }

    public function write($picture, $link = '') {
        $imgPath = $picture[$this->imgField];
        if ($this->captionField != '') {
            $caption = $picture[$this->captionField];
        } else {
            $caption = $this->captionField;
        }
        if ($link != '') {
            printf('<a href="%s">' . PHP_EOL, $link);
        }
        printf('<figure class="%s">' . PHP_EOL, $caption);
        if (substr(PATH_PARTS['dirname'], -4, 3) == 'lab') {
            printf('<img src="images/%s" alt="%s">' . PHP_EOL, $imgPath, $caption);
        } else {
            printf('<img src="../images/%s" alt="%s">' . PHP_EOL, $imgPath, $caption);
        }
        printf('<figcaption>%s</figcaption>' . PHP_EOL, $caption);
        print '</figure>' . PHP_EOL;
        if ($link != "") {
            print '</a>' . PHP_EOL;
        }
    }

    public function writeAll() {
        $i = 0;
        foreach ($this->pictureArray as $picture) {
            if ($this->linkArray != '') {
                $this->write($picture, $this->linkArray[$i]);
                $i++;
            } else {
                $this->write($picture);
            }
        }
    }
}
