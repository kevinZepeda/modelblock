<?php  namespace App\Modules;

use App\Models\ktProject;
use Parsedown;

class ParsedownExtension extends Parsedown
{
    private $project_id;
    private $account_id;

    function __construct($project_id, $account_id)
    {
        $this->project_id = $project_id;
        $this->account_id = $account_id;
        $this->InlineTypes['{'] []= 'ColoredText';

        $this->inlineMarkerList .= '{';
    }

    protected function inlineColoredText($Excerpt)
    {
        if (preg_match('/^{page}([^{]+){\/page}/', $Excerpt['text'], $matches))
        {
            $page_name = urlencode(strtolower($matches[1]));
            $link = '/office/project/'.$this->project_id.'/wiki/'.$page_name;
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $matches[1],
                    'attributes' => array(
                        'href' => $link
                    ),
                ),
            );
        }
    }
    
}