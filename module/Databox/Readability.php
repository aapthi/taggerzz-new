<?php
// define("READABILITY_VERSION", 1.21);
class Readability {
    const ATTR_CONTENT_SCORE = "contentScore";
    const DOM_DEFAULT_CHARSET = "utf-8";
    const MESSAGE_CAN_NOT_GET = "Readability was unable to parse this page for content.";
    protected $DOM = null;
    protected $source = "";
    private $parentNodes = array();
    // Note: added extra tags from https://github.com/ridcully
    private $junkTags = Array("style", "form", "iframe", "script", "button", "input", "textarea", 
                                "noscript", "select", "option", "object", "applet", "basefont",
                                "bgsound", "blink", "canvas", "command", "menu", "nav", "datalist",
                                "embed", "frame", "frameset", "keygen", "label", "marquee", "link");
    private $junkAttrs = Array("style", "class", "onclick", "onmouseover", "align", "border", "margin");
    function __construct($source, $input_char = "utf-8") {
        $this->source = $source;
        $source = mb_convert_encoding($source, 'HTML-ENTITIES', $input_char);
        $source = $this->preparSource($source);
		$this->DOM = new DOMDocument('1.0', $input_char);
        try {
            //libxml_use_internal_errors(true);
            if (!@$this->DOM->loadHTML('<?xml encoding="'.Readability::DOM_DEFAULT_CHARSET.'">'.$source)) {
                throw new Exception("Parse HTML Error!");
            }
            foreach ($this->DOM->childNodes as $item) {
                if ($item->nodeType == XML_PI_NODE) {
                    $this->DOM->removeChild($item); // remove hack
                }
            }
            // insert proper
            $this->DOM->encoding = Readability::DOM_DEFAULT_CHARSET;
        } catch (Exception $e) {
            // ...
        }
    }
    private function preparSource($string) {
        preg_match("/charset=([\w|\-]+);?/", $string, $match);
        if (isset($match[1])) {
            $string = preg_replace("/charset=([\w|\-]+);?/", "", $string, 1);
        }
        // Replace all doubled-up <BR> tags with <P> tags, and remove fonts.
        $string = preg_replace("/<br\/?>[ \r\n\s]*<br\/?>/i", "</p><p>", $string);
        $string = preg_replace("/<\/?font[^>]*>/i", "", $string);
        // @see https://github.com/feelinglucky/php-readability/issues/7
        //   - from http://stackoverflow.com/questions/7130867/remove-script-tag-from-html-content
        $string = preg_replace("#<script(.*?)>(.*?)</script>#is", "", $string);
        return trim($string);
    }
    private function removeJunkTag($RootNode, $TagName) {
        $Tags = $RootNode->getElementsByTagName($TagName);
        //Note: always index 0, because removing a tag removes it from the results as well.
        while($Tag = $Tags->item(0)){
            $parentNode = $Tag->parentNode;
            $parentNode->removeChild($Tag);
        }
        return $RootNode;
    }
	private function removeJunkAttr($RootNode, $Attr) {
        $Tags = $RootNode->getElementsByTagName("*");
        $i = 0;
        while($Tag = $Tags->item($i++)) {
            $Tag->removeAttribute($Attr);
        }
        return $RootNode;
    }
    private function getTopBox() {
        $allParagraphs = $this->DOM->getElementsByTagName("p");
		
		if($allParagraphs->length==0)
		{
			$allParagraphs = $this->DOM->getElementsByTagName("td");
		}
		//echo "<pre>";print_r($allParagraphs);exit;
		if($allParagraphs->length==0){
		$allParagraphs = $this->DOM->getElementsByTagName("div");
		}
		if($allParagraphs->length==0){
		$allParagraphs = $this->DOM->getElementsByTagName("b");
		}
		//echo "<pre>";print_r($allParagraphs);exit;
        // Study all the paragraphs and find the chunk that has the best score.
        // A score is determined by things like: Number of <p>'s, commas, special classes, etc.
        $i = 0;
        while($paragraph = $allParagraphs->item($i++)) {
            $parentNode   = $paragraph->parentNode;
            $contentScore = intval($parentNode->getAttribute(Readability::ATTR_CONTENT_SCORE));
            $className    = $parentNode->getAttribute("class");
            $id           = $parentNode->getAttribute("id");
            // Look for a special classname
            if (preg_match("/(comment|meta|footer|footnote)/i", $className)) {
                $contentScore -= 50;
            } else if(preg_match(
                "/((^|\\s)(post|hentry|entry[-]?(content|text|body)?|article[-]?(content|text|body)?)(\\s|$))/i",
                $className)) {
                $contentScore += 25;
            }
            // Look for a special ID
            if (preg_match("/(comment|meta|footer|footnote)/i", $id)) {
                $contentScore -= 50;
            } else if (preg_match(
                "/^(post|hentry|entry[-]?(content|text|body)?|article[-]?(content|text|body)?)$/i",
                $id)) {
                $contentScore += 25;
            }
            // Add a point for the paragraph found
            // Add points for any commas within this paragraph
            if (strlen($paragraph->nodeValue) > 10) {
                $contentScore += strlen($paragraph->nodeValue);
            }
            $parentNode->setAttribute(Readability::ATTR_CONTENT_SCORE, $contentScore);
			array_push($this->parentNodes, $parentNode);
        }
        $topBox = null;
        // Assignment from index for performance. 
        //     See http://www.peachpit.com/articles/article.aspx?p=31567&seqNum=5 
        for ($i = 0, $len = sizeof($this->parentNodes); $i < $len; $i++) {
            $parentNode      = $this->parentNodes[$i];
            $contentScore    = intval($parentNode->getAttribute(Readability::ATTR_CONTENT_SCORE));
            $orgContentScore = intval($topBox ? $topBox->getAttribute(Readability::ATTR_CONTENT_SCORE) : 0);

            if ($contentScore && $contentScore > $orgContentScore) {
                $topBox = $parentNode;
            }
        }
        return $topBox;
    }
    public function getTitle() {
        $split_point = ' - ';
        $titleNodes = $this->DOM->getElementsByTagName("title");

        if ($titleNodes->length 
            && $titleNode = $titleNodes->item(0)) {
            // @see http://stackoverflow.com/questions/717328/how-to-explode-string-right-to-left
            $title  = trim($titleNode->nodeValue);
            $result = array_map('strrev', explode($split_point, strrev($title)));
            return sizeof($result) > 1 ? array_pop($result) : $title;
        }
        return null;
    }
    /**
     * Get Leading Image Url
     *
     * @return String
     */
    public function getLeadImageUrl($node) {
        $images = $node->getElementsByTagName("img");
        if ($images->length && $leadImage = $images->item(0)) {
            return $leadImage->getAttribute("src");
        }
        return null;
    }
    public function getContent() {
        if (!$this->DOM) return false;
        $ContentTitle = $this->getTitle();
        $ContentBox = $this->getTopBox();
        //Check if we found a suitable top-box.
        //if($ContentBox === null)
        //    throw new RuntimeException(Readability::MESSAGE_CAN_NOT_GET);
        $Target = new DOMDocument;
		if($ContentBox != null){
        $Target->appendChild($Target->importNode($ContentBox, true));
		}
		foreach ($this->junkTags as $tag) {
            $Target = $this->removeJunkTag($Target, $tag);
        }
		foreach ($this->junkAttrs as $attr) {
            $Target = $this->removeJunkAttr($Target, $attr);
        }
        $content = mb_convert_encoding($Target->saveHTML(), Readability::DOM_DEFAULT_CHARSET, "HTML-ENTITIES");
		return Array(
            'lead_image_url' => $this->getLeadImageUrl($Target),
            'word_count' => mb_strlen(strip_tags($content), Readability::DOM_DEFAULT_CHARSET),
            'title' => $ContentTitle ? $ContentTitle : null,
            'content' => $content
        );
    }
    function __destruct() { }
}