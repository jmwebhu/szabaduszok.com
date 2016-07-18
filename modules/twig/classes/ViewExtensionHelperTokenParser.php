<?php
abstract class ViewExtensionHelperTokenParser extends Twig_TokenParser {
    public function parse(Twig_Token $token) {
        $lineno = $token->getLine();
        // Methods are called like this: html.method, expect a period
        $this->parser->getStream()->expect(Twig_Token::PUNCTUATION_TYPE, '.');
        // Find the html method we're to call
        $method = $this->parser->getStream()->expect(Twig_Token::NAME_TYPE)->getValue();
        $params = $this->parser->getExpressionParser()->parseMultiTargetExpression();
        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        return new ViewExtensionHelperNode(array('expression' => $params), array('method' => $method), $lineno, $this->getTag());
    }
}