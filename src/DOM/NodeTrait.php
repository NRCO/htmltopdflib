<?php
/**
 * Copyright (c)2014-2014 heiglandreas
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIBILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category 
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2018-2018 NR-Communication
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.1
 * @since     04.01.2018
 * @link      https://github.com/NRCommunication/htmltopdflib
 */

namespace NRCommunication\HtmlToPdflib\DOM;

use DOMAttr;
use NRCommunication\HtmlToPdflib\Style\Macro;

trait NodeTrait {

    /**
     * [getPdfLibString description]
     * @return [type] [description]
     */
    public function getPdfLibString() {

        $content = '';
        $strPrefix = $this->getAttribute('strprefix');
        $strPostfix = $this->getAttribute('strpostfix');

        if($this->parentNode->firstChild === $this) {
            $strPrefix = '';
        }

        if($this->parentNode->lastChild === $this) {
            $strPostfix = '';
        }
        
        $macroTag = '';
        if($this->getAttribute('macro')) {
            $macroTag = '<&' . $this->getAttribute('macro') . '>';
        }
        
        $prevMacroTag = '';
        $prevMacroName = $this->getParentNameMacro();

        if($prevMacroName) {
            $prevMacroTag = '<&' . $prevMacroName . '>';
        }

        foreach ($this->childNodes as $child) {
            if(XML_TEXT_NODE === $child->nodeType) {
                $content .= htmlentities($child->textContent);
            } else {
                $content .= $child->getPdfLibString();
            }
        }

        return $macroTag
             . $strPrefix
             . $content
             . $strPostfix
             . $prevMacroTag;
    }

    private function getParentNameMacro($node = false) {
        
        $macro = false;
        
        if($node === false) {
            $node = $this->parentNode;
        }

        if($node->nodeType !== XML_HTML_DOCUMENT_NODE) {
            $macro = $node->getAttribute('macro');
            if(!$macro) {
                if(isset($node->parentNode)) {
                    $macro = $this->getParentNameMacro($node->parentNode);
                }
            }
        }

        return $macro;
    }
}
