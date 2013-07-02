<?php /* /GithubEventsWidget/View/Helper/GithubEventsHelper.php */
App:: uses('AppHelper', 'View/Helper');
App::import('Helper', 'Html');

/**
 * Cake Events Widget helper
 * 
 * Helps process events received from the Github API v3
 * and passes data to the widget templates.
 * 
 * @author   Chris Vogt <@c1v0>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     http://developer.github.com/v3/
 * @link     http://chrisvogt.me
 */

/**
 * Github events helper for Cake
 * 
 * @author chrisvogt
 */
class GithubEventsHelper extends AppHelper {

    public $helpers = array('Html');
    
/**
 * Outputs the Github Events Widget
 * 
 * @param array $events Event data to be worked into the widget template
 * @return string Process widget markup to output
 * @throws BadFunctionCallException
 */
    public function makeWidget($events) {
        return $this->_View->element('GithubEventsWidget.widget/github_events', $events);
    }
    
    public function renderAsList($events) {
        return $this->_View->element('GithubEventsWidget.widget/event_list', $events);
    }
    
    public function renderIcon($type) {
        $type = strtolower(str_replace('Event', '', $type));
        $icnMap = array(
            'create' => 'icon-code-fork',
            'fork'   => 'icon-code-fork',
            'watch'  => 'icon-star',
            'gist'   => 'icon-map-marker',
            'issues'        => 'icon-bug',
            'issuecomment'  => 'icon-comments-alt'
        );
        return "<i class='{$icnMap[$type]}'>&nbsp;</i>";
    }
    
    public function renderActionText($event) {
        $type = strtolower(str_replace('Event', '', $event['type']));
        
        if ($event['repo'])
            $repo = &$event['repo'];
        if ($event['payload'])
            $payload = &$event['payload'];
        $txtMap = array(
            'gist'   => 'created a new gist ',
            'issues'        => 'opened a new issue ',
            'issuecomment'  => 'commented on an issue '
        );
        switch ($type) {
            case 'create':
                $txtMap[$type] = "created {$payload['ref_type']} <span class=\"label label-info\">{$payload['ref']}</span> {$this->Html->link($repo['name'], $repo['url'])}";
                break;
            case 'fork':
                $txtMap[$type] = "forked {$this->Html->link($repo['name'], $repo['url'])} ";
                break;
            case 'watch':
                $txtMap[$type] = "starred {$this->Html->link($repo['name'], $repo['url'])} ";
                break;
        }
        return $txtMap[$type];
    }
    
}
?>