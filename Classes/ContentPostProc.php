<?php
namespace Qbus\DataConsent\Hooks;

/**
 * ContentPostProc
 *
 * @author Benjamin Franzke <bfr@qbus.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ContentPostProc
{
    public function postProcess(array $params)
    {
        $pObj = $params['pObj'];

        $pObj->content = preg_replace_callback(
            '/(<iframe[^>]*) src="([^"]*)"/i',
            function ($matches) {
                return sprintf(
                    '%s src="/?eID=iframe_placeholder&original_url=%s" data-src="%s"',
                    $matches[1],
                    rawurlencode($matches[2]),
                    $matches[2]
                );
            },
            $pObj->content
        );
    }
}
