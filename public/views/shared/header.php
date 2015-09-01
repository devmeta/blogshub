    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
          </button>        
    			<a href="/" class="navbar-brand">
  					<strong><?php echo $config['blog']['title'];?></strong>
  					<small><?php echo $config['blog']['caption'];?></small>
    			</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
          <ul class="nav navbar nav-pills pull-right"> 
          <?php if( isset($config['blog']['id']) ):?>
            <li>
                <a href="#" class="pull-left search">
                  <i class='fa fa-search'></i>
                  <input type="text" class="form-control" placeholder="<?php echo _l('Search');?>" /> &nbsp;&nbsp;
                </a>
            </li>
          <?php endif;?>
            <li>
                <a href="<?php echo $config['baseurl'];?>" title="<?php echo _l('Create_Account');?>" data-placement="bottom" target="_blank">
                  <i class='ion-speakerphone x2'></i>
                </a>
            </li>          
          </ul>
        </div>
      </div>
    </nav>

