<?php
namespace MVC\Views;

class MarkdownView extends ViewFactory
{
    const LAYOUT = <<<MD
# {{{title}}}

{{{body}}}
MD;

    protected $replacements;

    public function __construct(object $decorator)
    {
        $body = $decorator->body();
        $body = str_replace(['<strong>', '</strong>'], '**', $body);
        $body = preg_replace('/<(?:br\s*\/?|\/p|\/li|\/div)>/i', "\n", $body);
        $body = strip_tags($body);

        $lines = array_filter(array_map('trim', explode("\n", $body)));
        $markdownBody = implode("\n", array_map(fn($line) => "- {$line}", $lines));

        $this->replacements = [
            '{{{title}}}' => $decorator->title(),
            '{{{body}}}'  => $markdownBody
        ];
    }

    public function render() : string
    {
        return str_replace(
            array_keys($this->replacements),
            array_values($this->replacements),
            self::LAYOUT
        );
    }
}