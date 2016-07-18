<?php
class ViewExtensionHelperNode extends Twig_Node {
    protected $cs_tags = array( // your case sensitive tags
        'html' => 'HTML',
        'form' => 'Form',
        'debug' => 'Debug',
    );

    public function compile(Twig_Compiler $compiler) {
        $params = $this->getNode('expression')->getIterator();
        $nodeTag = $this->getNodeTag();
        $nodeTag = isset($this->cs_tags[strtolower($nodeTag)]) ? $this->cs_tags[strtolower($nodeTag)] : $nodeTag;
        // Output the route     
        $compiler->write('echo ' . $nodeTag . '::' . $this->getAttribute('method') . '(');
        foreach ($params as $i => $row) {
            $compiler->subcompile($row);
            if (($params->count() - 1) !== $i) {
                $compiler->write(',');
            }
        }
        $compiler->write(')')->raw(';');
    }
}