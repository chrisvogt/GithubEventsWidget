<?php
/**
 * Cake 2.x Component to assist with consuming the Github API v3
 * 
 * @author   Chris Vogt <@c1v0>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     http://developer.github.com/v3/
 * @link     http://chrisvogt.me
 * @todo     [ ] Pagination | [WIP] Missing event types
 */
App::uses('HttpSocket', 'Network/Http');
App::uses('Sanitize', 'Utility');

/**
 * Github API consumer component for Cake
 * 
 * @author chrisvogt
 */
class GithubApiComponent extends Component {
    
/**
 * API path
 * 
 * @var string
 * @access private
 */
    private $apiPath = 'https://api.github.com/';
    
/**
 * Components
 * 
 * @var array $components
 */
    public $components = array('Session');

# - - - - - - -
    
/**
 * Get a user or repo's commits.
 * 
 * @param array $subject [('type' => 'users|repos', 'target' => 'username|full-reponame:chrisvogt/cakephp-devsite')]
 * @param integer $limit 
 * @return string $fields
 */
    public function recentEvents($subject = array(), $limit = 9999) {
        $accepts = array('users', 'repos');
        if (is_array($subject) && array_key_exists('type', $subject) && array_key_exists('target', $subject)) {
            $path = $this->_setSubject($subject);
        } else throw new Exception('$subject must be an array, and must contain string values for `type` and `target`.');
        
        $result = $this->_call($path);
        
        $results = array();
        $i = 0;
        foreach ( $result as $key => $item) {
            $acceptTypes = array('WatchEvent', 'PushEvent', 'GistEvent', 'CreateEvent', 'PublicEvent', 'IssuesEvent', 'IssueCommentEvent');
            if ($i < $limit) {
                if (in_array($item['type'], $acceptTypes)) {
                    $results[] = $item;
                    $i++;
                }
            }
        }
        if (!empty($results)) {
            return $results;
        } else return false;
    }
    
    public function repoInfo($fullname) {
        $result = $this->_call('repos/' . $fullname);
        $result['stats'] = $this->_call('repos/' . $fullname . '/stats/contributors');
        return $result;
    }
    
# - - - - - - -
    
/**
 * Build the query action/subject
 * 
 * @param array $subject
 * @return string
 * @throws Exception
 */
    private function _setSubject($subject = null) {
        if ($subject['type'] == 'users' || $subject['type'] == 'repos') {
            return $subject['type'] . DS . $subject['target'];
        } else throw new Exception('Unrecognized subject type. Must be `users` or `repos`.');
    }

/**
 * Add pagination settings to query
 * 
 * @param array $fields ['page' => (int), 'per_page' => (int>100)]
 * @link http://developer.github.com/v3/#pagination
 * @return string $path
 */
    private function _setPagination($fields = array()) {
        if (!is_array($fields)) {
            throw new Exception('Invalid parameter type passed to _setPagination. Only accepts arrays.');
        }
        if (is_int($fields['page'] && is_int($fields['per_page']))) {
            $path = http_build_query($fields);
        } else throw new Exception('One or more of the values passed to _setPagination is an invalid type. Must be integers.');
        return $path;
    }
    
    
    
/**
 * API call to GET Github data.
 * 
 * @param string $path
 * $param string $args
 * @return array $response
 */
    private function _call($path, $pagination = null) {
        $request = array(
            'header' => array(
                'User-Agent' => Configure::read('social.github')
            )
        );
        $sanitizedPath = str_replace('/', '-', strtolower($path));
        $result = Cache::read($sanitizedPath, 'gh');
        if(!$result) {
            $consumer = $this->_createConsumer();
            $result = $consumer->get($this->apiPath . $path, '', $request);
            if (isset($result['error'])) {
                throw new Exception('Github: ' . $result['error']['message']);
            }
            Cache::write($sanitizedPath, $result->body, 'gh');
            return json_decode($result->body, true);
        }
        return json_decode($result, true);
    }
    
/**
 * Create an API consumer using HttpSocket
 * 
 * @uses HttpSocket
 * @return HttpSocket
 */
    private function _createConsumer() {
        return new HttpSocket();
    }
    
}
