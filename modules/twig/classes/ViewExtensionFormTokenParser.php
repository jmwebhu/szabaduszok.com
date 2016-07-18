<?php
class ViewExtensionFormTokenParser extends ViewExtensionHelperTokenParser {
    public function getTag() {
        return 'form'; // lowercase as all in twig
    }
}