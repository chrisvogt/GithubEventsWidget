<?php /* /GithubEventsWidget/View/Elements/widget/github_events.ctp */
/**
 * Wrapper template for the Github user events widget 
 * 
 * @author   Chris Vogt <@c1v0>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     http://developer.github.com/v3/
 * @link     http://chrisvogt.me
 */
?>
                        <div class="container-fluid">
                            <div class="row-fluid" style="margin-bottom: 8px;">
                                <?php echo $this->GithubEvents->renderAsList($events); ?> 
                            </div><!-- /.row-fluid -->
                            <?php echo $this->Html->link('<i class="icon-github">&nbsp;</i> View all on Github', 'https://github.com/' . Configure::read('social.github') . '?tab=activity', array('class' => 'btn btn-small pull-right', 'escape' => false)); ?> 
                        </div><!-- /.container-fluid -->