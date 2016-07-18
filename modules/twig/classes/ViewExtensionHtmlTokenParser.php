<?php
class ViewExtensionHtmlTokenParser extends ViewExtensionHelperTokenParser {
    public function getTag() {
        return 'html'; // lowercase as all in twig
    }
}