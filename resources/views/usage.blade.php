@extends("layouts/master")
@section("content")
<div class="row">
    <div class="col-lg-12">
        <h2>
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle-o fa-stack-2x"></i>
                <i class="fa fa-ioxhost fa-stack-1x"></i>
            </span>
            My Usage
        </h2>
       <?php
           $calendar->setResolution(new Solution10\Calendar\Resolution\MonthResolution());
           // That's it! Let's grab the view data and render:
           $viewData = $calendar->viewData();
           $months = $viewData['contents'];
       ?>
       <?php foreach ($months as $month): ?>
        <div>
            <?php foreach ($month->weeks() as $week): ?>
                <?php foreach ($week->days() as $day): ?>
                <p>
                        <?php
                            if ($day->isOverflow()) {
                                if ($calendar->resolution()->showOverflowDays()) {
                                    echo '<span style="color: #ccc">'.$day->date()->format('d').'</span>';
                                }   
                            } else {
                                echo $day->date()->format('M d, Y');
                            }
                        ?>
                </p>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
@stop
