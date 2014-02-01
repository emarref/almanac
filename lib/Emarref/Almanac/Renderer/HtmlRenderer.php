<?php

namespace Emarref\Almanac\Renderer;

class HtmlRenderer implements RendererInterface
{
    const FILE_EXTENSION = 'html';
    const MIME_TYPE      = 'text/html';

    /**
     * @var array
     */
    protected $stylesheets = array();

    /**
     * @var array
     */
    protected $scripts = array();

    protected $classes = array(
        'table' => null
    );

    public function formatTable(array $data)
    {
        $buffer = '';

        if (isset($this->classes['table'])) {
            $table_class = sprintf(' class="%s"', htmlspecialchars($this->classes['table']));
        } else {
            $table_class = '';
        }

        $buffer .= sprintf("<table%s>\n", $table_class);
        $buffer .= "<thead>\n";

        foreach ($data as $y => $row) {
            $buffer .= "<tr>\n";

            $tag = (0 === $y) ? 'th' : 'td';

            foreach ($row as $x => $column) {
                $buffer .= sprintf("<%1\$s>%2\$s</%1\$s>", $tag, $column);
            }

            $buffer .= "\n</tr>\n";

            if (0 === $y) {
                $buffer .= "</thead>\n<tbody>\n";
            }
        }

        $buffer .= "</tbody>\n";
        $buffer .= "</table>\n";

        return $buffer;
    }

    protected function template($content, $title = null)
    {
        $buffer =  "<!DOCTYPE HTML>\n";
        $buffer .= "<html lang=\"en\">\n";
        $buffer .= "    <head>\n";

        if ($title) {
            $buffer .= sprintf("        <meta name=\"title\" value=\"%s\"/>\n", $title);
            $buffer .= sprintf("        <title>%s</title>\n", $title);
        }

        foreach ($this->stylesheets as $stylesheet) {
            $buffer .= sprintf("        <link rel=\"stylesheet\" type=\"text/css\" href=\"%s\"/>\n", $stylesheet);
        }

        $buffer .= "    </head>\n";
        $buffer .= "    <body>\n";
        $buffer .= "        " . $content . "\n";

        foreach ($this->scripts as $script) {
            $buffer .= sprintf("        <script src=\"%s\"></script>\n", $script);
        }

        $buffer .= "    </body>\n";
        $buffer .= "</html>";

        return $buffer;
    }

    public function render(array $content)
    {
        $buffer = '';

        $buffer .= sprintf("<h1>%s</h1>\n", $content['heading']);
        $buffer .= sprintf("<p>%s</p>\n", nl2br($content['introduction']));

        foreach ($content['results'] as $result) {
            $buffer .= sprintf("<h2>%s</h2>\n", $result['heading']);
            $buffer .= sprintf("<p>%s</p>\n", nl2br($result['introduction']));
            $buffer .= sprintf("%s\n", $this->formatTable($result['content']));
        }

        return $this->template($buffer, $content['heading']);
    }
}