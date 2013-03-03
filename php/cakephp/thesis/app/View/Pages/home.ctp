 <!-- Main hero unit for a primary marketing message or call to action -->
  <div class="hero-unit">
    <h1>Welcome in api.geum!</h1>
    <br /><p>
        Apigeum is a product that will let you create Flat File Database and share it through REST API (for free, or not).
    </p>
      <br />
    <p>
        <?php if ($currentUser): ?>
        <?php echo $this->Html->link('Check out the list of repositories! &raquo;', array('controller'=>'feeds','action'=>'index'), array('class'=>'btn btn-primary btn-large','escape'=>false)) ?>
        <?php else: ?>
            <?php echo $this->Html->link('Register &raquo;', array('controller'=>'users','action'=>'register'), array('class'=>'btn btn-primary btn-large','escape'=>false)) ?>
        <?php endif; ?>
  </div>

  <!-- Example row of columns -->
  <div class="row">
    <div class="span4 <?php if ($currentUser): ?> disabled <?php endif ?>">
      <h2>1. Register</h2>
       <p>Once you register, you have the opportunity to create REST API and share for money.</p>
        <p> <?php echo $this->Html->link('Register &raquo;', array('controller'=>'users','action'=>'register'), array('class'=>'btn','escape'=>false)) ?></p>
    </div>
    <div class="span4 <?php if (!$currentUser): ?> disabled <?php endif ?>">
      <h2>2. Create the repository</h2>
       <p>You can create your API by hand, or through CSV import.</p>
      <p><?php echo $this->Html->link('Create the repository', array('controller'=>'feeds','action'=>'add'), array('class'=>'btn','escape'=>false)) ?></p>
   </div>
    <div class="span4 <?php if (!$currentUser): ?> disabled <?php endif ?>">
      <h2>3. Profit</h2>
      <p>When someone will buy your API, you can see it under My stuff &raquo; Customers section.</p>
      <p><?php echo $this->Html->link('Customers', array('controller'=>'keys','action'=>'sold'), array('class'=>'btn ','escape'=>false)) ?></p>
    </div>
  </div>