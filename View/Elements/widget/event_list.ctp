<?php /* /GithubEventsWidget/View/Elements/widget/event-list.ctp */
/**
 * Template for the Github user events widget 
 * 
 * Helps process events received from the Github API v3
 * and passes data to the widget templates.
 * 
 * @author   Chris Vogt <@c1v0>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     http://developer.github.com/v3/
 * @link     http://chrisvogt.me
 */
?>
                            <ul id="list-events">
                            <?php foreach ($events as $key => $event) : ?> 
                                <li<?php echo (fmod($key, 2) ? '' : ' class="odd"'); ?>>
                                    <?php echo $this->GithubEvents->renderIcon($event['type']); ?> 
                                    <?php echo $this->GithubEvents->renderActionText($event); ?> 

                                    <span><?php echo $this->Time->timeAgoInWords($event['created_at']); ?></span> 
                                </li>
                            <?php endforeach; ?>
                            </ul>