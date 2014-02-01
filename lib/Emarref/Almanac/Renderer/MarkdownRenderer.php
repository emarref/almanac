<?php

namespace Emarref\Almanac\Renderer;

class MarkdownRenderer implements RendererInterface
{
    const FILE_EXTENSION = 'md';
    const MIME_TYPE      = 'text/x-markdown; charset=UTF-8'; // @see http://stackoverflow.com/questions/10701983/what-is-the-mime-type-for-markdown

    protected function formatTable(array $data)
    {
        $buffer = '';
        $column_info = array();

        foreach ($data as $y => $row) {
            foreach ($row as $x => $column) {
                if (!isset($column_info[$x])) {
                    $column_info[$x] = array('length' => 0, 'numeric' => true);
                }

                if ($column_info[$x]['length'] < strlen($column)) {
                    $column_info[$x]['length'] = strlen($column);
                }

                if (0 < $y && !is_numeric($column)) {
                    $column_info[$x]['numeric'] = false;
                }
            }
        }

        foreach ($data as $y => $row) {
            $buffer .= '|';

            foreach ($row as $x => $column) {
                if ($column_info[$x]['numeric']) {
                    $buffer .= sprintf(' %'.$column_info[$x]['length'].'s |', $column);
                } else {
                    $buffer .= sprintf(' %-'.$column_info[$x]['length'].'s |', $column);
                }
            }

            $buffer .= "\n";

            if (0 === $y) {
                // Header row is followed by border row
                $buffer .= '|';

                foreach ($row as $x => $column) {
                    if ($column_info[$x]['numeric']) {
                        $border = str_repeat('-', $column_info[$x]['length']-1).':';
                    } else {
                        $border = str_repeat('-', $column_info[$x]['length']);
                    }

                    $buffer .= sprintf(' %-'.$column_info[$x]['length'].'s |', $border);
                }

                $buffer .= "\n";
            }
        }

        return $buffer;
    }

    /**
     * {@inheritdoc}
     */
    public function render(array $content)
    {
        $buffer = '';

        $buffer .= sprintf("# %s", $content['heading']);
        $buffer .= sprintf("\n\n%s", $content['introduction']);

        foreach ($content['results'] as $result) {
            $buffer .= sprintf("\n\n## %s", $result['heading']);
            $buffer .= sprintf("\n\n%s", $result['introduction']);
            $buffer .= sprintf("\n\n%s", $this->formatTable($result['content']));
        }

        return $buffer;
    }
}
