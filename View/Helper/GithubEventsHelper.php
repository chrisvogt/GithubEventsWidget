<?php /* /GithubEventsWidget/View/Helper/GithubEventsHelper.php */
App:: uses('AppHelper', 'View/Helper');

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
        return $this->_View->element('GithubEventsWidget.widget/events_list', $events);
    }
    
}
?>