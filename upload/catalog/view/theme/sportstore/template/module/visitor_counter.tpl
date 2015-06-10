<div class="box-color-1 box-shadow">

    <!-- Title -->
    
      

    <!-- Text -->

    

  <h3 class="box-color-1-title"><span><?php echo $heading_title; ?></span></h3> 

  <div class="box-color-1-text">
      <div id="vvisit_counter106" class="vacenter">
        <div class="vdigit_counter"><?php echo $visitor_counter_num; ?></div>
        <div class="vstats_counter">
          <div class="vstats_icon vfleft varight">
            <div class="vrow"><img src="<?php echo $image_path ?>/today.png"></div>
            <div class="vfclear"></div>
            <div class="vrow"><img src="<?php echo $image_path ?>/yesterday.png"></div>
            <div class="vfclear"></div>
            <div class="vrow"><img src="<?php echo $image_path ?>/mvcweek.png"></div>
            <div class="vfclear"></div>
            <div class="vrow"><img src="<?php echo $image_path ?>/mvclastweek.png"></div>
            <div class="vfclear"></div>
            <div class="vrow"><img src="<?php echo $image_path ?>/month.png"></div>
            <div class="vfclear"></div>
            <div class="vrow"><img src="<?php echo $image_path ?>/mvcyear.png"></div>
            <div class="vfclear"></div>
            <div class="vrow"><img src="<?php echo $image_path ?>/mvctotal.png"></div>
            <div class="vfclear"></div>
            <div class="vrow"></div>
            <div class="vfclear"></div>
          </div>
          <div class="vstats_title vfleft valeft">
            <div class="vrow">Hôm nay</div>
            <div class="vfclear"></div>
            <div class="vrow">Hôm qua</div>
            <div class="vfclear"></div>
            <div class="vrow">Tuần này</div>
            <div class="vfclear"></div>
            <div class="vrow">Tuần trước</div>
            <div class="vfclear"></div>
            <div class="vrow">Trong tháng</div>
            <div class="vfclear"></div>
            <div class="vrow">Trong năm</div>
            <div class="vfclear"></div>
            <div class="vrow">Tổng số</div>
            <div class="vfclear"></div>
            <div class="vrow"></div>
            <div class="vfclear"></div>
          </div>
          <div class="vstats_number varight">
            <div class="vrow"><?php echo $today_visitor; ?></div>
            <div class="vrow"><?php echo $yesterday_visitor; ?></div>
            <div class="vrow"><?php echo $week_visitor; ?></div>
            <div class="vrow"><?php echo $lastweek_visitor ?></div>
            <div class="vrow"><?php echo $month_visitor ?></div>
            <div class="vrow"><?php echo $year_visitor ?></div>
            <div class="vrow"><?php echo $total_visitor ?></div>
            <div class="vrow"></div>
          </div>
          <div class="vfclear"></div>
        </div>
        <?php if ($text_ip || $text_server_time) {?>
        <hr class="hr">
        <?php }?>
        <?php if ($text_ip) {?>
        <div class="hr"><?php echo $ip_title.$ip_visitor ?></div>
        <?php }?>
        <?php if ($text_server_time) {?>
        <div><?php echo $server_time_title.$server_time  ?></div>
        <?php }?>
      </div>
  </div>
</div>

