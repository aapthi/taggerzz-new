<?php
/**
 * Copyright (c) 2014 Leonardo Cardoso (http://leocardz.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.3.0
 */

/** This class handles the content analysis */

include_once "Regex.php";

class Content
{

    static function crawlCode($text)
    {
        $contentSpan = Content::getTagContent("span", $text);
        $contentParagraph = Content::getTagContent("p", $text);
        $contentDiv = Content::getTagContent("div", $text);
        if (strlen($contentParagraph) > strlen($contentSpan) && strlen($contentParagraph) >= strlen($contentDiv))
            $content = $contentParagraph;
        else if (strlen($contentParagraph) > strlen($contentSpan) && strlen($contentParagraph) < strlen($contentDiv))
            $content = $contentDiv;
        else
            $content = $contentParagraph;
        return $content;
    }

    static function getTagContent($tag, $string)
    {
        $pattern = "/<$tag(.*?)>(.*?)<\/$tag>/i";

        preg_match_all($pattern, $string, $matches);
        $content = "";
        for ($i = 0; $i < count($matches[0]); $i++) {
            $currentMatch = strip_tags($matches[0][$i]);
            if (strlen($currentMatch) >= 120) {
                $content = $currentMatch;
                break;
            }
        }
        if ($content == "") {
            preg_match($pattern, $string, $matches);
            $content = $matches[0];
        }
        return str_replace("&nbsp;", "", $content);
    }

    static function isImage($url)
    {
        if (preg_match(Regex::$imagePrefixRegex, $url))
            return true;
        else
            return false;
    }

    static function getImages($text, $url, $imageQuantity)
    {
        $content = array();
        if (preg_match_all(Regex::$imageRegex, $text, $matching)) {

            for ($i = 0; $i < count($matching[0]); $i++) {
                $src = "";
                $pathCounter = substr_count($matching[0][$i], "../");
                preg_match(Regex::$srcRegex, $matching[0][$i], $imgSrc);
                $imgSrc = Url::canonicalImgSrc($imgSrc[2]);
                if (!preg_match(Regex::$httpRegex, $imgSrc)) {
                    $src = Url::getImageUrl($pathCounter, Url::canonicalLink($imgSrc, $url));
                }
                if ($src . $imgSrc != $url) {
                    if ($src == "")
                        array_push($content, $src . $imgSrc);
                    else
                        array_push($content, $src);
                }
            }
        }

        $content = array_unique($content);
        $content = array_values($content);

        $maxImages = $imageQuantity != -1 && $imageQuantity < count($content) ? $imageQuantity : count($content);

        $images = "";
        for ($i = 0; $i < count($content); $i++) {
            if (!($size = @getimagesize($content[$i]))) {
                continue;
            }
            $size = getimagesize($content[$i]);
            if ($size[0] > 100 && $size[1] > 15) {// avoids getting very small images
                $images .= $content[$i] . "|";
                $maxImages--;
                if ($maxImages == 0)
                    break;
            }
        }
		
		$taggerzzImageInserted = false;
		
		$extensionUrl = explode( "/",$url );
		$urlExtension = "";
		if( count($extensionUrl) > 1 )
		{
			$urlExtArr = explode( ".",$extensionUrl[count($extensionUrl)-1] );
			$urlExtension = $urlExtArr[1];
		}
		// Dileep On 09-12-2015
		$urlsArray = array('2ch.net','4shared.com','6pm.com','9gag.com','39.net','163.com','360.cn','about.com','abs-cbnnews.com',
'accuweather.com','adcash.com','addthis.com','adf.ly','adobe.com','alegro.pl','alibaba.com','aliexpress.com','alipay.com',
'allrecipes.com','amazon.ca','amazon.cn','amazon.co.jp','amazon.co.uk','amazon.com','amazon.de','amazon.es','amazon.fr',
'amazon.in','amazon.it','amazonaws.com','ameblo.jp','americanexpress.com','answers.com','aol.com','apple.com','archive.org',
'ask.com','ask.fm','asos.com','att.com','avg.com','avito.ru','babytree.com','badoo.com','baidu.com','bankofamerica.com',
'battle.net','bbc.co.uk','bbc.com','bestbuy.com','bet365.com','bhaskar.com','bild.de','billibilli.com','bing.com','Bitauto.com',
'bleacherreport.com','blog.livedoor.com','blogger.com','blogspot.com','blogspot.in','blogtamsu.vn','bloomberg.com','bongacams.com',
'booking.com','businessinsider.com','buzzfeed.com','caijing.com.cn','capitalone.com','cbssports.com','chase.com','china.com',
'china.com.cn','chinadaily.com','chip.de','citi.com','clickadu.com','cnet.com','cnn.com','cnnic.cn','cntv.cn','cnzz.com',
'comcast.net','costco.com','craiglist.org','dailymail.co.uk','dailymotion.com','daum.net','dell.com','detik.com','devinatart.com',
'diply.com','directrev.com','dmm.co.jp','dmm.com','douban.com','dropbox.com','ebates.com','ebay.co.uk','ebay.com','ebay.com.au',
'ebay.de','ebay.in','ebay.it','Ebay-kleinanzeigen.de','eksisozluk.com','elmundo.es','elpais.com','engadget.com','espn.go.com',
'espncricinfo.com','etsy.com','evernote.com','exoclick.com','extratorrent.cc','facebook.com','fbcdn.net','fc2.com','fedex.com',
'feedly.com','files.wordpress.com','fiverr.com','flickr.com','flipkart.com','flirchi.com','foodnetwork.com','forbes.com',
'foxnews.com','free.fr','gamefaqs.com','gameforge.com','gamer.com.tw','gap.com','gearbest.com','gfycat.com','github.com',
'github.io','global.alipay.com','globo.com','gmw.cn','gmx.net','go.com','goal.com','godaddy.com','goo.ne.jp','goodreads.com',
'google.ae','google.at','google.az','google.be','google.ca','google.ch','google.cl','google.cn','google.co.id','google.co.il',
'google.co.in','google.co.jp','google.co.kr','google.co.th','google.co.uk','google.co.ve','google.co.za','Google.com','google.com.ar',
'google.com.au','google.com.br','google.com.co','google.com.hd','google.com.hk','google.com.mx','google.com.ng','google.com.pe',
'google.com.ph','google.com.pk','google.com.sa','google.com.sg','google.com.tr','google.com.tw','google.com.ua','google.cz',
'google.de','google.dz','google.es','google.fr','google.gr','google.hu','google.it','google.nl','google.no','google.pl','google.pt',
'google.ro','google.ru','google.se','groupon.com','gsmarena.com','hao123.com','haosou.com','hclips.com','hdfcbank.com','hm.com',
'homedepot.com','hootsuite.com','hp.com','huanqiu.com','huffingtonpost.com','hulu.com','hurriyet.com.tr','icicibank.com','icloud.com',
'ifeng.com','ign.com','ikea.com','imdb.com','imgur.com','in.ign.com','indeed.com','independent.co.uk','indiatimes.com',
'infusionsoft.com','instagram.com','irctc.co.in','jabong.com','jcpenney.com','jd.com','kakaku.com','kat.cr','kickstarter.com',
'kinogo.co','kinopoisk.ru','kohls.com','kompas.com','kouclo.com','leboncoin.fr','lemonde.fr','lenovo.com','libero.it',
'lifehacker.com','likes.com','linkedin.com','liputan6.com','livedoor.jp','liveinternet.ru','livejasmin.com','livejournal.com',
'lowes.com','macys.com','mail.ru','mailchimp.com','marca.com','mashable.com','media.tumblr.com','mediafire.com','medium.com',
'mega.nz','mercadolivre.com.br','microsoft.com','milliyet.com.tr','mozilla.org','msn.com','mystart.com','nametests.com',
'naukri.com','naver.com','nba.com','nbcnews.com','ndtv.com','netflix.com','newegg.com','nfl.com','nicovideo.jp','nih.com',
'nike.com','nordstrom.com','nytimes.com','office.com','ok.ru','onclickads.net','onedio.com','oneindia.com','onet.pl',
'onlinesbi.com','oracle.com','orange.fr','outbrain.com','overstock.com','pandora.com','paypal.com','paytm.com',
'photobucket.com','Pinimg.com','pinterest.com','pixiv.net','pixnet.net','plarium.com','playstation.com','popads.net',
'pornhub.com','ppomppu.co.kr','putlocker.is','qq.com','quikr.com','quora.com','rakuten.co.jp','rambler.ru','rbc.ru',
'reddit.com','rediff.com','redtube.com','reference.com','reimageplus.com','repubblica.it','reuters.com','rt.com',
'sahibinden.com','salesforce.com','samsung.com','savefrom.net','sberbank.ru','scribd.com','sears.com','seznam.cz',
'shop.nordstrom.com','shopclues.com','shopify.com','shutterstock.com','sina.com.cn','skype.com','slack.com','slickdeals.net',
'slideshare.net','smzdm.com','snapdeal.com','softonic.com','sogou.com','sohu.com','soso.com','soundcloud.com','souq.com',
'sourceforge.net','speedtest.net','spiegel.de','spotify.com','stackexchange.com','stackoverflow.com','steamcommunity.com',
'steampowered.com','stumbleupon.com','taboola.com','taobao.com','target.com','telegraph.co.uk','telegraphindia.com',
'thefreedictionary.com','theguardian.com','thehindu.com','theladbible.com','themeforest.net','thepiratebay.cr','thesaurus.com',
'tianya.cn','time.com','tmall.com','t-online.de','torrentz.eu','torrentz.in','toysrus.com','trello.com','tripadvisor.com',
'tube8.com','tudou.com','tumblr.com','twitch.tv','twitter.com','udn.com','uk.businessinsider.com','uol.com.br','uploaded.net',
'upornia.com','ups.com','uptodown.com','usatoday.com','usps.com','varzesh3.com','verizonwireless.com','vice.com','vid.me',
'vimeo.com','vk.com','w3schools.com','walmart.com','washingtonpost.com','weather.com','web.de','webmd.com','weebly.com',
'weibo.com','wellsfargo.com','whatsapp.com','wikia.com','wikihow.com','wikimedia.org','wikipedia.com','wix.com',
'wordpress.com','wordpress.org','wordreference.com','wp.pl','wsj.com','xfinity.com','xhamster.com','xinhuanet.com','xnxx.com',
'xuite.net','xvideos.com','yahoo.co.jp','yahoo.com','Yandex.ru','yandex.ua','yelp.com','youku.com','youm7.com','youporn.com',
'youth.cn','youtube.com','zendesk.com','zhihu.com','zippyshare.com','zol.com.cn','zollow.com','ssl.gstatic.com');
		$path = parse_url($url);
		$domainPath = $path['host'];
		foreach($urlsArray as $urlName){
			if (strpos($domainPath, $urlName) !== false) { 
				$comDomain = explode('.',$urlName);
				$imageName = strtolower($comDomain[0]);
				$taggerzzImageInserted = true;
				$images = $imageName;
			}
		}
		
		// End
		
		// if (strpos($url, "facebook.com") !== false) {
			// $taggerzzImageInserted = true;
            // $images = "facebook";
        // }
		// else if (strpos($url, "twitter.com") !== false) {
			// $taggerzzImageInserted = true;
            // $images = "twitter";
        // }
		// else if (strpos($url, "plus.google.com") !== false) {
			// $taggerzzImageInserted = true;
            // $images = "gplus";
        // }
		
		if( $urlExtension == "pdf" )
		{
			$taggerzzImageInserted = true;
            $images = "pdf";
		}
		else if( ($urlExtension == "doc") || ($urlExtension == "docx") )
		{
			$taggerzzImageInserted = true;
            $images = "doc";
		}
		else if( $urlExtension == "txt" )
		{
			$taggerzzImageInserted = true;
            $images = "txt";
		}
		else if( ($urlExtension == "xls") || ($urlExtension == "xlsx") )
		{
			$taggerzzImageInserted = true;
            $images = "xls";
		}
		else if( ($urlExtension == "ppt") || ($urlExtension == "pptx") )
		{
			$taggerzzImageInserted = true;
            $images = "ppt";
		}
		else if( $urlExtension == "zip" )
		{
			$taggerzzImageInserted = true;
            $images = "zip";
		}
		else if( $urlExtension == "torrent" )
		{
			$taggerzzImageInserted = true;
            $images = "torrent";
		}
		else if( $urlExtension == "css" )
		{
			$taggerzzImageInserted = true;
            $images = "css";
		}
		else if( $urlExtension == "esp" )
		{
			$taggerzzImageInserted = true;
            $images = "esp";
		}
		else if( $urlExtension == "cdr" )
		{
			$taggerzzImageInserted = true;
            $images = "cdr";
		}
		else if( $urlExtension == "ai" )
		{
			$taggerzzImageInserted = true;
            $images = "ai";
		}
		if( $taggerzzImageInserted )
		{
			return $images;
		}
		else
		{
			return substr($images, 0, -1);
		}
    }

    static function getMetaTags($contents)
    {

        $result = false;

        if (isset($contents)) {

            $list = array(
                "UTF-8",
                "EUC-CN",
                "EUC-JP",
                "EUC-KR",
                'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
                'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
                'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
                'Windows-1251', 'Windows-1252', 'Windows-1254',
            );

            $encoding_check = mb_detect_encoding($contents, $list, true);
            $encoding = ($encoding_check === false) ? "UTF-8" : $encoding_check;

            $metaTags = Content::getMetaTagsEncoding($contents, $encoding);


            $result = $metaTags;
        }

        return $result;
    }

    static function getMetaTagsEncoding($contents, $encoding)
    {
        $result = false;
        $metaTags = array("url" => "", "title" => "", "description" => "", "image" => "");

        if (isset($contents)) {

            $doc = new DOMDocument('1.0', 'utf-8');
            @$doc->loadHTML($contents);

            $metas = $doc->getElementsByTagName('meta');

            for ($i = 0; $i < $metas->length; $i++) {
                $meta = $metas->item($i);
                if ($meta->getAttribute('name') == 'description')
                    $metaTags["description"] = $meta->getAttribute('content');
                if ( strtolower($meta->getAttribute('name')) == 'keywords' )
                    $metaTags["keywords"] = $meta->getAttribute('content');
                if ($meta->getAttribute('property') == 'og:title')
                    $metaTags["title"] = $meta->getAttribute('content');
                if ($meta->getAttribute('property') == 'og:image')
                    $metaTags["image"] = $meta->getAttribute('content');
                if ($meta->getAttribute('property') == 'og:description')
                    $metaTags["og_description"] = $meta->getAttribute('content');
                if ($meta->getAttribute('property') == 'og:url')
                    $metaTags["url"] = $meta->getAttribute('content');
            }

            if (!empty($metaTags["og_description"])) {
                $metaTags["description"] = $metaTags["og_description"];
            }
			
			if( ! isset($metaTags["keywords"]) )
			{
				$metaTags["keywords"] = "";
			}

            if (empty($metaTags["title"])) {
                $nodes = $doc->getElementsByTagName('title');
                $metaTags["title"] = $nodes->item(0)->nodeValue;
            }

            $result = $metaTags;
        }
        return $result;
    }

    /*
    static function getMetaTags($contents)
    {
        $result = false;
        $metaTags = array("url" => "", "title" => "", "description" => "", "image" => "");

        if (isset($contents)) {

            preg_match_all(Regex::$metaRegex, $contents, $match);

            foreach ($match[1] as $value) {

                if ((strpos($value, 'property="og:url"') !== false || strpos($value, "property='og:url'") !== false) || (strpos($value, 'name="url"') !== false || strpos($value, "name='url'") !== false))
                    $metaTags["url"] = Content::separeMetaTagsContent($value);
                else if ((strpos($value, 'property="og:title"') !== false || strpos($value, "property='og:title'") !== false) || (strpos($value, 'name="title"') !== false || strpos($value, "name='title'") !== false))
                    $metaTags["title"] = Content::separeMetaTagsContent($value);
                else if ((strpos($value, 'property="og:description"') !== false || strpos($value, "property='og:description'") !== false) || (strpos($value, 'name="description"') !== false || strpos($value, "name='description'") !== false))
                    $metaTags["description"] = Content::separeMetaTagsContent($value);
                else if ((strpos($value, 'property="og:image"') !== false || strpos($value, "property='og:image'") !== false) || (strpos($value, 'name="image"') !== false || strpos($value, "name='image'") !== false))
                    $metaTags["image"] = Content::separeMetaTagsContent($value);
            }

            $result = $metaTags;
        }
        return $result;
    } */

    static function separeMetaTagsContent($raw)
    {
        preg_match(Regex::$contentRegex1, $raw, $match);
        if (count($match) == 0) {
            preg_match(Regex::$contentRegex2, $raw, $match);
        }
        return $match[1];
    }

    static function extendedTrim($content)
    {
        return trim(str_replace("\n", " ", str_replace("\t", " ", preg_replace("/\s+/", " ", $content))));
    }

    static function stripIrrelevantTags($content)
    {
        $tags = array('style', 'script');
        $content = preg_replace('#<(' . implode('|', $tags) . ')>.*?</\1>#s', '', $content);
        return $content;
    }
}
