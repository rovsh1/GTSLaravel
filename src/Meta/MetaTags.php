<?php

namespace Gsdk\Meta;

class MetaTags
{
    protected string $title = '';

    protected string $relativePath = '/';

    protected array $meta = [];

    protected array $contents = [];

    public function __get(string $name)
    {
        return match ($name) {
            'title', 'relativePath', 'baseHref' => $this->$name,
            default => null,
        };
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->getMeta('meta_name_description')?->getAttribute('content') ?? null;
    }

    public function getKeywords()
    {
        return $this->getMeta('meta_name_keywords')?->getAttribute('content') ?? null;
    }

    public function fromString(string $html): static
    {
        (new Support\HtmlParser())->buildHead($this, $html);
        return $this;
    }

    public function relativePath(string $path): static
    {
        $this->relativePath = $path;
        return $this;
    }

    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function keywords(string $keywords): static
    {
        return $this->addMetaName('keywords', $keywords);
    }

    public function description(string $description): static
    {
        return $this->addMetaName('description', $description);
    }

    public function baseHref(string $href): static
    {
        return $this->_addMeta(new Head\BaseHref($href));
    }

    public function addContent(string $content): static
    {
        $this->contents[] = $content;
        return $this;
    }

    public function addMeta(array $attributes): static
    {
        return $this->_addMeta(new Head\Meta($attributes));
    }

    public function getMeta(string $identifier): ?Head\MetaTagInterface
    {
        return $this->meta[$identifier] ?? null;
    }

    public function addLink(array $attributes): static
    {
        return $this->_addMeta(new Head\Link($attributes));
    }

    public function addLinkRel(string $rel, $href, array $attributes = []): static
    {
        $attributes['rel'] = $rel;
        $attributes['href'] = $href;
        return $this->addLink($attributes);
    }

    public function addMetaName(string $name, string $content, array $attributes = []): static
    {
        $attributes['name'] = $name;
        $attributes['content'] = $content;
        return $this->addMeta($attributes);
    }

    public function addMetaProperty(string $property, string $content, array $attributes = []): static
    {
        $attributes['property'] = $property;
        $attributes['content'] = $content;
        return $this->addMeta($attributes);
    }

    public function addMetaHttpEquiv(string $keyValue, string $content, array $attributes = []): static
    {
        $attributes['http-equiv'] = $keyValue;
        $attributes['content'] = $content;
        return $this->addMeta($attributes);
    }

    public function addStyle(string $href, array $attributes = []): static
    {
        return $this->_addMeta(new Head\Style($this->url($href, 'css'), $attributes));
    }

    public function addScript(string $src, array $attributes = []): static
    {
        return $this->_addMeta(new Head\Script($this->url($src, 'js'), $attributes));
    }

    public function url(string $url, $path = null): string
    {
        if (!str_starts_with($url, '/') && !str_starts_with($url, 'http')) {
            return $this->relativePath . ($path ? $path . '/' : '') . $url;
        }
        return $url;
    }

    public function render(): string
    {
        $html = '<title>' . $this->title . '</title>';

        foreach ($this->meta as $meta) {
            $html .= "\n\t" . $meta->getHtml();
        }

        if ($this->contents) {
            $html .= "\n\t" . implode("\n\t", $this->contents);
        }

        return $html;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    protected function _addMeta(Head\MetaTagInterface $meta): static
    {
        $uid = $meta->getIdentifier();
        if ($uid) {
            $this->meta[$uid] = $meta;
        } else {
            $this->meta[] = $meta;
        }
        return $this;
    }
}
