<?php

namespace ChrisUllyott;

class AwsPhpDocArrayConverter
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $contents;

    /**
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = trim($file);

        $this->contents = file_get_contents($this->file);
    }

    /**
     * @return string
     */
    public function convertedFile()
    {
        $path = pathinfo($this->file);

        $directory = $path['dirname'];
        $filename = $path['filename'] . '_converted';

        return "{$directory}/{$filename}.php";
    }

    /**
     * @return bool
     */
    public function convert()
    {
        $this->doubleSpace();
        $this->removeEllipsesComments();
        $this->removeEllipsesFromArrays();
        $this->moveRequiredComments();
        $this->fixDataTypeExamples();
        $this->convertOptionListsIntoComments();
        $this->addReturnStatement();

        return (bool)file_put_contents($this->convertedFile(), $this->contents);
    }

    /**
     * @return $this
     */
    private function doubleSpace()
    {
        $pattern = "/(\h*)('[^']+?'\h*=>)/";
        $replace = "\n$1$2";
        $this->contents = preg_replace($pattern, $replace, $this->contents);

        $pattern = "/(\h*)\[\n\n/";
        $replace = "$1[\n";
        $this->contents = preg_replace($pattern, $replace, $this->contents);

        return $this;
    }

    /**
     * @return $this
     */
    private function moveRequiredComments()
    {
        $pattern = '/(\h*)(.*?)\/\/\h*REQUIRED\n/';
        $replace = "$1// REQUIRED\n$1$2\n";

        $this->contents = preg_replace($pattern, $replace, $this->contents);

        return $this;
    }

    /**
     * @return $this
     */
    private function removeEllipsesComments()
    {
        $pattern = '/\h*\/\/\s*\.+\n/';

        $this->contents = preg_replace($pattern, '', $this->contents);

        return $this;
    }

    /**
     * @return $this
     */
    private function removeEllipsesFromArrays()
    {
        $this->contents = preg_replace('/,\s*\.+]/', ']', $this->contents);

        return $this;
    }

    /**
     * @return $this
     */
    private function fixDataTypeExamples()
    {
        $pattern = '/<[^<]*?([A-Za-z]+)[^>]*?>/';
        $replace = "<$1>";
        $this->contents = preg_replace($pattern, $replace, $this->contents);

        $pattern = '/[\']?<([A-Za-z]+)>[\']?/';
        $replace = "'<$1>'";
        $this->contents = preg_replace($pattern, $replace, $this->contents);

        return $this;
    }

    /**
     * @return $this
     */
    private function convertOptionListsIntoComments()
    {
        $pattern = "/(\h*)(.*?\s*=>\s*)'([\w\|]+)'/";
        $replace = "$1// $3\n$1$2'<string>'";
        $this->contents = preg_replace($pattern, $replace, $this->contents);

        return $this;
    }

    /**
     * @return $this
     */
    private function addReturnStatement()
    {
        $this->contents = "<?php\n\nreturn " . trim($this->contents) . ";\n";

        return $this;
    }
}