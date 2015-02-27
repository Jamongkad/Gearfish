@extends("layouts/master")
@section("content")
<div class="row">
    <div class="col-lg-12">
        <h2>
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-flash fa-stack-1x"></i>
            </span>
            My Usage
        </h2>
       <?php
           $calendar->setResolution(new Solution10\Calendar\Resolution\MonthResolution());
           $viewData = $calendar->viewData();
           $months = $viewData['contents'];
       ?>
       <table class="table">
           <tr> 
               <th></th>
               <th>Usage</th>
               <th>Free Tier</th>
               <th>Cost</th>
           </tr>
           <?php foreach ($months as $month): ?>
                <?php foreach ($month->weeks() as $week): ?>
                    <?php foreach ($week->days() as $day): ?>
                        <?php if(!$day->isOverflow()): ?>
                            <tr class="<?php 
                                     $date = new DateTime('now');
                                     $dateFormat = $date->format('Y-m-d');
                                     echo ($day->date()->format('Y-m-d') == $dateFormat) ? "success" : null?>">
                                <td><?php echo $day->date()->format('Y-m-d'); ?></td>
                                <td>
                                    <?php foreach($usage as $us) :?>
                                        <?php if($us->date_created == $day->date()->format('Y-m-d')):?>
                                            <?php echo number_format($us->processed); ?> 
                                        <?php endif;?>
                                    <?php endforeach ?>
                                </td>
                                <td>2,000</td>
                                <td> 
                                    <!--
                                    <?php foreach($usage as $us) :?>
                                        <?php if($us->date_created == $day->date()->format('Y-m-d')):?>
                                            <?php echo "$".number_format(($us->processed - 2000) * 0.001, 1); ?> 
                                        <?php endif;?>
                                    <?php endforeach ?>
                                    -->
                                    N/A
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </table>
    </div>
</div>
@stop
