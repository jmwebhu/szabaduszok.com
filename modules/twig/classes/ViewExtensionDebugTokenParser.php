<?php
class ViewExtensionDebugTokenParser extends ViewExtensionHelperTokenParser {
    public function getTag() {
        return 'debug'; // lowercase as all in twig
    }
}