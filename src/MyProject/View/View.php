<?php
namespace MyProject\View;
class View
{
    private $templatesPath;
    private $extraVars = [];
    public function __construct($templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }
    public function setVar(string $name, $value)
    {
        $this->extraVars[$name] = $value;
    }
    public function renderHtml(string $templates, array $vars = [], $code = 200)
    {
        http_response_code($code);
        extract($this->extraVars);
        extract($vars);
        ob_start();
        include $this->templatesPath .'/'. $templates;
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;

    }

}