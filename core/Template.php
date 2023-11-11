<?php

namespace app\core;

class Template
{
    private $__content = null;
    public function run($content, $data = [])
    {
        $this->__content = $content;

        $this->parse_foreach_loop();
        $this->parse_for_loop();
        $this->parse_while_loop();
        $this->parse_if_condition();
        $this->parse_php_begin();
        $this->parse_php_end();
        $this->parse_html_entities();
        $this->parse_raw();
        // echo $this->__content;
        eval("?> " . $this->__content . " <?php");
    }

    private function parse_html_entities()
    {
        preg_match_all("~{{\s*(.+?)\s*}}~is", $this->__content, $match);

        if (!empty($match[1])) {
            foreach ($match[1] as $key => $item) {
                $this->__content = str_replace($match[0][$key], '<?php echo htmlentities(' . $item . '); ?>', $this->__content);
            }
        }
    }

    private function parse_raw()
    {
        preg_match_all("~{!\s*(.+?)\s*!}~is", $this->__content, $match);

        if (!empty($match[1])) {
            foreach ($match[1] as $key => $item) {
                $this->__content = str_replace($match[0][$key], '<?php echo ' . $item . '; ?>', $this->__content);
            }
        }
    }

    private function parse_if_condition()
    {
        preg_match_all("~@if\s*\(\s*(.+?)\s*\)\s*$~im", $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php if(' . $value . '): ?>', $this->__content);
            }
        }
        preg_match_all("~@elif\s*\(\s*(.+?)\s*\)\s*$~im", $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php elseif(' . $value . '): ?>', $this->__content);
            }
        }
        preg_match_all("~@else~im", $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php else: ?>', $this->__content);
            }
        }
        preg_match_all("~@endif~im", $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php endif; ?>', $this->__content);
            }
        }
    }
    private function parse_php_begin()
    {
        preg_match_all("~@php~is", $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php', $this->__content);
            }
        }
    }
    private function parse_php_end()
    {
        preg_match_all("~@endphp~is", $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '?>', $this->__content);
            }
        }
    }

    private function parse_for_loop()
    {
        preg_match_all("~@for\s*\(\s*(.+?)\s*\)\s*~im", $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php for(' . $value . '): ?>', $this->__content);
            }
        }

        preg_match_all("~@endfor\s*~im", $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php endfor; ?>', $this->__content);
            }
        }
    }

    private function parse_while_loop()
    {
        preg_match_all("~@while\s*\(\s*(.+?)\s*\)\s*~im", $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php while(' . $value . '): ?>', $this->__content);
            }
        }

        preg_match_all("~@endwhile\s*~im", $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php endwhile; ?>', $this->__content);
            }
        }
    }
    private function parse_foreach_loop()
    {
        preg_match_all("~@foreach\s*\(\s*(.+?)\s*\)\s*~im", $this->__content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php foreach(' . $value . '): ?>', $this->__content);
            }
        }

        preg_match_all("~@endforeach\s*~im", $this->__content, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $this->__content = str_replace($matches[0][$key], '<?php endforeach; ?>', $this->__content);
            }
        }
    }
}
