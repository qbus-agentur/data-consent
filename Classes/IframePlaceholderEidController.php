<?php
namespace Qbus\DataConsent\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * IframePlaceholderEidController
 *
 * @author Benjamin Franzke <bfr@qbus.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class IframePlaceholderEidController
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * Retrieves the image and redirect to the url
     *
     * @param  ServerRequestInterface $request  the current request object
     * @param  ResponseInterface      $response the available response
     * @return ResponseInterface      the modified response
     */
    public function processRequest(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = $request->getQueryParams()['original_url'] ?? null;
        $escapedUrl = htmlspecialchars($url);
        $parsed  = parse_url($url);
        $escapedHost = htmlspecialchars($parsed['host']);
        $type = 'marketing';

        $title = 'iframe';
        if ($escapedHost === 'www.youtube.com') {
            $title = 'video';
        }

        $result = <<<EOT
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta http-equiv=X-UA-Compatible content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
*,*:before,*:after{box-sizing:border-box;margin:0;padding:0;}
html,body{min-height:100vh}
body{display:flex;justify-content:center;align-items:center;padding:2em;flex-direction:column;
font: 1.4em/1.4 Verdana,Arial,Helvetica,sans-serif;
background: #494949;
color:rgba(255,255,255,.5);
}
p{text-align: center}
a{color: inherit; text-decoration: underline}
.buttons{display:flex;margin-top:2em;}
.button {
font-size:.95em;
border:1px solid currentColor;
margin-right:1em;
padding:.5em .75em;
text-decoration:none;
margin-right:1em;
}
.button:hover,.button:focus{color:white}
</style>
<p>
An iframe from the Host <strong>${escapedHost}</strong> has not been loaded as <strong>${type}</strong> cookies have not been enabled.<br>
<!--You may <a href="javascript:parent.openConsent()">change data privacy settings</a> to permanently load this video or make a temporary <a href="${escapedUrl}">exception</a>.-->
</p>
<div class="buttons">
    <a class="button" href="javascript:parent.openConsent()">Privacy Settings</a>
    <a class="button" href="${escapedUrl}">Load ${title} now</a>
</div>
<script>
EOT;

        $response->getBody()->write($result);
        return $response;
    }
}
